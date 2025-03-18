<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordDTO
{
    #[Assert\NotBlank(message: "Hasło nie może być puste.")]
    #[Assert\Length(
        min: 12,
        minMessage: "Hasło musi mieć co najmniej {{min}} znaków."
    )]
    #[Assert\Regex(
        pattern: "/.*[\W_].*[\W_].*/",
        message: "Hasło musi zawierać co najmniej 2 znaki specjalne"
    )]
    #[Assert\Regex(
        pattern: "/.*[A-Z].*[A-Z].*[A-Z].*[A-Z].*/",
        message: "Hasło musi mieć co najmniej 4 duże litery."
    )]
    #[Assert\Regex(
        pattern: "/.*\d.*\d.*\d.*/",
        message: "Hasło musi mieć co najmniej 3 cyfry."
    )]
    #[Assert\Regex(
        pattern: "/^(?!.*[@].*)(?!.*[4].*)(?!.*[X].*)(?!.*[i].*)(?!.*[c].*).*$/",
        message: "Hasło nie może zawierać @,4,X,i,c"
    )]
    private string $password;


    #[Assert\NotBlank(message: "Hasło nie może być puste.")]
    #[Assert\Length(
        min: 12,
        minMessage: "Hasło musi mieć co najmniej {{min}} znaków."
    )]
    #[Assert\Regex(
        pattern: "/.*[\W_].*[\W_].*/",
        message: "Hasło musi zawierać co najmniej 2 znaki specjalne"
    )]
    #[Assert\Regex(
        pattern: "/.*[A-Z].*[A-Z].*[A-Z].*[A-Z].*/",
        message: "Hasło musi mieć co najmniej 4 duże litery."
    )]
    #[Assert\Regex(
        pattern: "/.*\d.*\d.*\d.*/",
        message: "Hasło musi mieć co najmniej 3 cyfry."
    )]
    #[Assert\Regex(
        pattern: "/^(?!.*[@].*)(?!.*[4].*)(?!.*[X].*)(?!.*[i].*)(?!.*[c].*).*$/",
        message: "Hasło nie może zawierać @,4,X,i,c"
    )]
    private string $repeatPassword;

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRepeatPassword(): string
    {
        return $this->repeatPassword;
    }

    public function setRepeatPassword(string $repeatPassword): void
    {
        $this->repeatPassword = $repeatPassword;
    }


}