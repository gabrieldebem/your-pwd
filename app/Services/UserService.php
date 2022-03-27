<?php

namespace App\Services;

use App\Enums\UserStatus;
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
        return Hash::check($secret, $this->user->secret);
    }

    public function sendEmailVerification()
    {
        $verificationCode = rand(1000, 9999);
        Cache::put('verificationCode', $verificationCode, now()->addMinutes(5));

        $this->user->notify(new EmailVerificationNotification($verificationCode));
    }

    /**
     * Check Verification Code.
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

        return $this;
    }
}
