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

    public function testCalculate_whenTerm24AndMinimumAmount_thenReturn70()
    {
        $this->assertEquals(70.0, $this->calc->calculate(new LoanApplication(24, 1000.0)));
    }

    public function testCalculate_whenTerm12AndMaximumAmount_thenReturn400()
    {
        $this->assertEquals(400.0, $this->calc->calculate(new LoanApplication(12, 20000.0)));
    }

    public function testCalculate_whenTerm24AndMaximumAmount_thenReturn800()
    {
        $this->assertEquals(800.0, $this->calc->calculate(new LoanApplication(24, 20000.0)));
    }

    public function testCalculate_whenTerm12AmountMultipleOf1000_thenReturnCorrectFee()
    {
        $this->assertEquals(200.0, $this->calc->calculate(new LoanApplication(12, 10000.0)));
    }

    public function testCalculate_whenTerm24AmountMultipleOf1000_thenReturnCorrectFee()
    {
        $this->assertEquals(400.0, $this->calc->calculate(new LoanApplication(12, 10000.0)));
    }

    public function testCalculate_whenAmountNotMultipleOf1000_thenReturnCalculatedFee()
    {
        /*
         * Between 1000 and 2000:
         *
         * y = mx + c
         * 50 = m * 1000 + c | 90 = m * 2000 + c
         * (50 - c) / 1000 = m | (90 - c) / 2000 = m
         * (50 - c) / 1000 = (90 - c) / 2000
         * 100 - 2c = 90 - c
         * 100 = 90 + c
         * 10 = c
         *
         * 50 = m * 1000 + 10
         * 40 = m * 1000
         * 0.04 = m
         *
         * y = 0.04 * x + 10
         * y = 0.04 * 1500 + 10
         * y = 70
         */
        $this->assertEquals(70, $this->calc->calculate(new LoanApplication(12, 1500.0)));
    }

    public function testCalculate_whenCalculatedFeeNotMultipleOf5_thenRoundUpToNearestMultipleOf5()
    {
        $this->assertEquals(60.0, $this->calc->calculate(new LoanApplication(12, 1234.0)));
    }
}
