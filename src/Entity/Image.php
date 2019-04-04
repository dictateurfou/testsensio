<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="imageList",cascade={"persist","remove"})
     */
    private $trick;

    const VALIDTYPE = ['image/jpeg', 'image/png'];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setFile($file)
    {
        $this->file = $file;
        $this->uploadFile();

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    private function uploadFile()
    {
        dump('test');
        $targetDirectory = dirname(__DIR__).'/../public/uploads/trick';
        $valid = $this->checkValidMimeType($this->file->getMimeType());
        if (true === $valid) {
            $fileName = $this->name;
            $newFileName = md5(uniqid()).'.'.$this->file->guessExtension();
            try {
                $this->file->move($targetDirectory, $newFileName);
                if (null !== $fileName) {
                    $this->deleteFile($fileName);
                }
                $this->name = $newFileName;
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
        $targetDirectory = dirname(__DIR__).'/../public/uploads/trick';
        $myFile = $targetDirectory.'/'.$name;
        if (file_exists($myFile)) {
            unlink($myFile);
        }
    }
}
