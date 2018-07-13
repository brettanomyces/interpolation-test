<?php

declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Service\Fee;


class FeeBoundary
{
    /**
     * @var int
     */
    private $amount;

    /**
     * @var float
     */
    private $fee;

    public function __construct(int $amount, float $fee)
    {
        $this->amount = $amount;
        $this->fee = $fee;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getFee(): float
    {
        return $this->fee;
    }

}