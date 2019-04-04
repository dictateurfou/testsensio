<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"},message="this mail is already taken")
 * @UniqueEntity(fields={"username"},message="this username is already taken")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="boolean")
     */
    private $valid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatar;

    const VALIDTYPE = ['image/jpeg', 'image/png'];

    private $uploadedAvatar;

    public function getUploadedAvatar()
    {
        return $this->uploadedAvatar;
    }

    public function setUploadedAvatar($img): self
    {
        $this->uploadedAvatar = $img;
        $this->uploadFile();
        /*set uploadedAvatar at null for fix Serialization of 'Symfony\Component\HttpFoundation\File\UploadedFile' is not allowed*/
        $this->uploadedAvatar = null;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    private function uploadFile()
    {
        $targetDirectory = dirname(__DIR__).'/../public/uploads/avatar';
        $valid = $this->checkValidMimeType($this->uploadedAvatar->getMimeType());
        if (true === $valid) {
            $fileName = $this->avatar;
            $newFileName = md5(uniqid()).'.'.$this->uploadedAvatar->guessExtension();
            try {
                $this->uploadedAvatar->move($targetDirectory, $newFileName);
                if (null !== $fileName) {
                    $this->deleteFile($fileName);
                }
                $this->avatar = $newFileName;
            } catch (FileException $e) {
            }
        }
    }

    private function checkValidMimeType($fileType)
    {
        $i = 0;
        $valid = false;
        while (count(self::VALIDTYPE) > $i) {
            if (self::VALIDTYPE[$i] == $fileType) {
                $valid = true;
                break;
            }
            ++$i;
        }

        return $valid;
    }

    private function deleteFile($name)
    {
        $targetDirectory = dirname(__DIR__).'/../public/uploads/avatar';
        $myFile = $targetDirectory.'/'.$name;
        if (file_exists($myFile)) {
            unlink($myFile);
        }
    }
}
