<?php

namespace App\Interfaces;

interface AuthInterface
{
    public function login($data);

    public function sendResetEmail($email);

    public function resetPassword($email, $token, $password);
}
