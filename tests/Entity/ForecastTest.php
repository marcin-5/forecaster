<?php

namespace App\Tests\Entity;

use App\Entity\Forecast;
use PHPUnit\Framework\TestCase;

class ForecastTest extends TestCase
{
    public function dataTemperatureFahrenheit(): array
    {
        return [
            [0, 32],
            [0.7, 33.3],
            [-1, 30.2],
            [-10.94, 12.3],
            [-17.8, 0],
        ];
    }

    /**
     * @dataProvider dataTemperatureFahrenheit
     */
    public function testTemperatureFahrenheit($celsius, $expectedFahrenheit): void
    {
        $forecast = new Forecast();
        $forecast->setTemperatureCelsius($celsius);
        $fahrenheit = $forecast->getTemperatureFahrenheit();

        $this->assertEquals($expectedFahrenheit, $fahrenheit, "Wrong Celsius to Fahrenheit conversion");
    }
}
