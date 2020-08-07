<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @package App\Entity
 * @ORM\Entity()
 */
class User implements UserInterface, \Serializable
{
    const ROLE_ANONYMOUS_USER = 'ROLE_ANONYMOUS_USER';
    const ROLE_REGISTERED_USER = 'ROLE_REGISTERED_USER';

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $password;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @var string|null
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    protected $email;

    /**
     * @var array
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @var string|null
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    protected $authToken;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $authTokenCreatedDateTime;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return User
     */
    public function setPassword(?string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return User
     */
    public function setName(?string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return User
     */
    public function setPhone(?string $phone): User
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return User
     */
    public function setEmail(?string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }

    /**
     * @param string|null $authToken
     * @return User
     */
    public function setAuthToken(?string $authToken): User
    {
        $this->authToken = $authToken;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getAuthTokenCreatedDateTime(): ?\DateTime
    {
        return $this->authTokenCreatedDateTime;
    }

    /**
     * @param \DateTime|null $authTokenCreatedDateTime
     * @return User
     */
    public function setAuthTokenCreatedDateTime(?\DateTime $authTokenCreatedDateTime): User
    {
        $this->authTokenCreatedDateTime = $authTokenCreatedDateTime;
        return $this;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // We're using bcrypt in security.yaml to encode the password, so
        // the salt value is built-in and and you don't have to generate one
        // See https://en.wikipedia.org/wiki/Bcrypt

        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // if you had a plainPassword property, you'd nullify it here
        // $this->plainPassword = null;
    }

    /**
     * @return string|void
     */
    public function serialize()
    {
        return serialize([$this->id, $this->email, $this->password]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        [$this->id, $this->email, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }
}