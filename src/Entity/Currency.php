<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $valuteID;

    #[ORM\Column(type: 'string', length: 255)]
    private $numCode;

    #[ORM\Column(type: 'string', length: 255)]
    private $charCode;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'float')]
    private $value;

    #[ORM\Column(type: 'integer')]
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValuteID(): ?string
    {
        return $this->valuteID;
    }

    public function setValuteID(string $valuteID): self
    {
        $this->valuteID = $valuteID;

        return $this;
    }

    public function getNumCode(): ?string
    {
        return $this->numCode;
    }

    public function setNumCode(string $numCode): self
    {
        $this->numCode = $numCode;

        return $this;
    }

    public function getCharCode(): ?string
    {
        return $this->charCode;
    }

    public function setCharCode(string $charCode): self
    {
        $this->charCode = $charCode;

        return $this;
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

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDate(): ?int
    {
        return $this->date;
    }

    public function setDate(int $date): self
    {
        $this->date = $date;

        return $this;
    }
}
