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
     * @var array
     */
    private $term24 = [
        1000 => 70,
        2000 => 100,
        3000 => 120,
        4000 => 160,
        5000 => 200,
        6000 => 240,
        7000 => 280,
        8000 => 320,
        9000 => 360,
        10000 => 400,
        11000 => 440,
        12000 => 480,
        13000 => 520,
        14000 => 560,
        15000 => 600,
        16000 => 640,
        17000 => 680,
        18000 => 720,
        19000 => 760,
        20000 => 800,
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

        if ($application->getAmount() == $lower->getAmount()) {
            return $lower->getFee();
        }

        $upper = $this->getUpperBound($application);

        $interpolated = $this->interpolate($lower, $upper, $application);

        return $this->roundUpToNearest5($interpolated);
    }

    private function getLowerBound(LoanApplication $application): FeeBoundary
    {
        $boundary = intval(floor($application->getAmount() / 1000) * 1000);
        $fee = $application->getTerm() == 12 ? $this->term12[$boundary] : $this->term24[$boundary];
        return new FeeBoundary($boundary, $fee);
    }

    private function getUpperBound(LoanApplication $application): FeeBoundary
    {
        $boundary = intval(ceil($application->getAmount() / 1000) * 1000);
        $fee = $application->getTerm() == 12 ? $this->term12[$boundary] : $this->term24[$boundary];
        return new FeeBoundary($boundary, $fee);
    }

    private function interpolate(FeeBoundary $lower, FeeBoundary $upper, LoanApplication $application): float
    {
        return $this->performInterpolation(
            $lower->getAmount(),
            $lower->getFee(),
            $upper->getAmount(),
            $upper->getFee(),
            $application->getAmount()
        );
    }

    private function performInterpolation(float $x1, float $y1, float $x2, float $y2, float $amount): float
    {
        $m = ($y2 - $y1) / ($x2 - $x1);
        $c = $y1 - ($m * $x1);
        return $m * $amount + $c;
    }

    private function roundUpToNearest5(float $amount): float
    {
        $intAmount = intval(ceil($amount));
        $remainder = $intAmount % 5;

        if ($remainder == 0) {
            return $intAmount;
        }

        return $intAmount + (5 - $remainder);
    }
}
