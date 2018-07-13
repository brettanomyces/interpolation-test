<?php

declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Service\Fee;

use Lendable\Interview\Interpolation\Model\LoanApplication;

/**
 * Calculates fees for loan applications.
 */
class FeeCalculator implements FeeCalculatorInterface
{
    /**
     * @var array
     */
    private $term12 = [
        1000 => 50,
        2000 => 90,
        3000 => 90,
        4000 => 115,
        5000 => 100,
        6000 => 120,
        7000 => 140,
        8000 => 160,
        9000 => 180,
        10000 => 200,
        11000 => 220,
        12000 => 240,
        13000 => 260,
        14000 => 280,
        15000 => 300,
        16000 => 320,
        17000 => 340,
        18000 => 360,
        19000 => 380,
        20000 => 400,
    ];

    /**
     * Calculates the fee for a loan application.
     *
     * @param LoanApplication $application The loan application to
     * calculate for.
     *
     * @return float The calculated fee.
     */
    public function calculate(LoanApplication $application): float
    {
        $lower = $this->getLowerBound($application);

        if ($application->getAmount() == $lower) {
            return $this->term12[$lower];
        }

        $upper = $this->getUpperBound($application);

        if ($application->getAmount() == $upper) {
            return $this->term12[$upper];
        }

        return $this->interpolate($lower, $upper, $application->getAmount());
    }

    private function getLowerBound(LoanApplication $application): int
    {
        return intval(floor($application->getAmount() / 1000) * 1000);
    }

    private function getUpperBound(LoanApplication $application): int
    {
        return intval(ceil($application->getAmount() / 1000) * 1000);
    }

    private function interpolate($lower, $upper, $amount): float
    {
        // m = (y2 - y1) / (x2 - x1)
        $m = ($this->term12[$upper] - $this->term12[$lower]) / ($upper - $lower);
        // c = y1 - (m * x1)
        $c = $this->term12[$lower] - ($m * $lower);

        return $m * $amount + $c;
    }

}
