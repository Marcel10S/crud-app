<?php

namespace App\Entity;

use App\Repository\ExportsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExportsRepository::class)
 */
class Exports
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $export_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $assigned_user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $export_place;

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

    public function getExportDate(): ?\DateTimeInterface
    {
        return $this->export_date;
    }

    public function setExportDate(?\DateTimeInterface $export_date): self
    {
        $this->export_date = $export_date;

        return $this;
    }

    public function getAssignedUser(): ?string
    {
        return $this->assigned_user;
    }

    public function setAssignedUser(string $assigned_user): self
    {
        $this->assigned_user = $assigned_user;

        return $this;
    }

    public function getExportPlace(): ?string
    {
        return $this->export_place;
    }

    public function setExportPlace(string $export_place): self
    {
        $this->export_place = $export_place;

        return $this;
    }
}
