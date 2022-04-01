<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Events\AccountActivatedEvent;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    private User $user;

    public function create (
        string $name,
        string $email,
        string $password,
    ) : self {
        $this->user = new User();
        $this->user->name = $name;
        $this->user->email = $email;
        $this->user->password = $password;
        $this->user->secret = $this->createSecret();
        $this->user->status = UserStatus::Pending;
        $this->user->save();

        activity('user.created')
            ->performedOn($this->user)
            ->log('New user has been created.');

        return $this;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function createSecret(): string
    {
        return Hash::make(Str::random());
    }

    public function checkSecret(string $secret): bool
    {
        activity('secret.checked')
            ->performedOn($this->user)
            ->causedBy(auth()->user())
            ->log('Current user has check this secret.');

        return Hash::check($secret, $this->user->secret);
    }

    public function sendEmailVerification()
    {
        $verificationCode = rand(1000, 9999);
        Cache::put('verificationCode', $verificationCode, now()->addMinutes(5));

        $this->user->notify(new EmailVerificationNotification($verificationCode));

        activity('user.send_email_verification')
            ->performedOn($this->user)
            ->causedBy(auth()->user())
            ->log('Current user has send email verification.');
    }

    /**
     * Check Verification Code.
     *
     * @throws Exception
     */
    public function checkVerificationCode(int $verificationCode): self
    {
        if (Cache::get('verificationCode') !== $verificationCode) {
            throw new Exception('Código de verificação invalido', 400);
        }

        return $this;
    }

    public function verifyEmail(): self
    {
        $this->user->email_verified_at = now();
        $this->user->status = UserStatus::Verified;
        $this->user->save();

        activity('user.email_verified')
            ->performedOn($this->user)
            ->causedBy(auth()->user())
            ->log('Current user email has been verified.');

        Cache::forget('verificationCode');

        return $this;
    }

    /**
     * Active user.
     *
     * @return void
     * @throws Exception
     */
    public function active()
    {
        $this->checkUser();

        $this->user->status = UserStatus::Active;
        $this->user->save();

        activity('user.activated')
            ->performedOn($this->user)
            ->log('Current user activated.');

        event(new AccountActivatedEvent($this->user));
    }

    /**
     * Check user is set;
     *
     * @return $this
     * @throws Exception
     */
    public function checkUser(): self
    {
        if (blank($this->user)) {
            throw new Exception('Usuário não foi definido', 500);
        }

        return $this;
    }
}
