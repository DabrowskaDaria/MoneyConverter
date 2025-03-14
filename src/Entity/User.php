<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name:'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string')]
    private string $surname;

    #[ORM\Column(type: 'string')]
    private string $email;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(
        type: 'string',
        nullable: true
    )]
    private string $token;

    #[ORM\Column(type: 'boolean')]
    private bool $active;

    #[ORM\Column(type: 'json')]
    private array $roles=[];

    /**
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $password
     * @param string $token
     * @param bool $active
     */
    public function __construct(string $name, string $surname, string $email, string $password, string $token, bool $active)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->password = $password;
        $this->token = $token;
        $this->active = $active;
    }


    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }


    public function getRoles(): array
    {
        $roles= $this ->roles;
        $roles[] = "ROLE_USER";
        return array_unique($roles);
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
}