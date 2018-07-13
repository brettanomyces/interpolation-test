<?php

namespace Lendable\Interview\Interpolation\Service\Fee;

use Lendable\Interview\Interpolation\Model\LoanApplication;
use PHPUnit\Framework\TestCase;

class FeeCalculatorTest extends TestCase
{
    private $calc;

    protected function setUp(): void
    {
        $this->calc = new FeeCalculator();
    }

    public function testCalculate_whenTerm12AndMinimumAmount_thenReturn50()
    {
        $this->assertEquals(50.0, $this->calc->calculate(new LoanApplication(12, 1000.0)));
    }

    public function testCalculate_whenTerm12AndMaximumAmount_thenReturn400()
    {
        $this->assertEquals(400.0, $this->calc->calculate(new LoanApplication(12, 20000.0)));
    }
}
