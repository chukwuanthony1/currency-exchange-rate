<?php

namespace App\Entity;

use App\Repository\CurrencyRatesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRatesRepository::class)]
class CurrencyRates
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $base_currency = null;

    #[ORM\Column(length: 255)]
    private ?string $target_currency = null;

    #[ORM\Column(length: 255)]
    private ?float $target_currency_rate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBaseCurrency(): ?string
    {
        return $this->base_currency;
    }

    public function setBaseCurrency(string $base_currency): self
    {
        $this->base_currency = $base_currency;

        return $this;
    }

    public function getTargetCurrency(): ?string
    {
        return $this->target_currency;
    }

    public function setTargetCurrency(string $target_currency): self
    {
        $this->target_currency = $target_currency;

        return $this;
    }

    public function getTargetCurrencyRate(): ?float
    {
        return $this->target_currency_rate;
    }

    public function setTargetCurrencyRate(string $target_currency_rate): self
    {
        $this->target_currency_rate = $target_currency_rate;

        return $this;
    }
}
