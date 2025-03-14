<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    #[Assert\NotBlank(message: "Imię nie może bć puste.")]
    private string $name;

    #[Assert\NotBlank(message: "Nazwisko nie może być puste.")]
    private string $surname;

    #[Assert\NotBlank(message: "E-mail nie może być pusty.")]
    #[Assert\Email(message: "Podaj poprawny e-mail.")]
    private string $email;

    #[Assert\NotBlank(message: "Hasło nie może być puste.")]
    #[Assert\Length(
        min: 12,
        minMessage: "Hasło musi mieć co najmniej {{12}} znaków."
    )]
    #[Assert\Regex(
        pattern: "/.*[\W_].*[\W_].*/",
        message: "Hasło musi zawierać co najmniej 2 znaki specjalne."
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }


}