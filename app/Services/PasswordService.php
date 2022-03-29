<?php

namespace App\Services;

use App\Models\Password;
use Illuminate\Support\Facades\Crypt;

class PasswordService
{
    private Password $password;

    public function setPassword(Password $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function create(string $url, string $password): self
    {
        $this->password = new Password();
        $this->password->user_id = auth()->user()->id;
        $this->password->url = $url;
        $this->password->password = $this->encrypt($password);
        $this->password->save();

        if (! $this->password->user->isActive()) {
            /** @UserService */
            app(UserService::class)
                ->setUser($this->password->user)
                ->active();
        }

        return $this;
    }

    public function encrypt(string $password): string
    {
        return Crypt::encryptString($password);
    }

    public function decrypt(): string
    {
        return Crypt::decryptString($this->password->password);
    }
}
