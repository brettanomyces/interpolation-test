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

    public function testCalculate(): void
    {
        $this->assertEquals(1.5, $this->calc->calculate(new LoanApplication(1, 1.5)));
    }
}
