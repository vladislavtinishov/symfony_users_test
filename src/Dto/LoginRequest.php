<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class LoginRequest
{
    #[Assert\NotBlank(message: "Email is required.")]
    #[Assert\Email(message: "Invalid email format.")]
    public ?string $email = null;

    #[Assert\NotBlank(message: "Password is required.")]
    public ?string $password = null;

    public function __construct(?string $email, ?string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}