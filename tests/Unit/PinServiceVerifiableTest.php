<?php

namespace Tumainimosha\Verifiable\Tests\Unit;

use Tumainimosha\Verifiable\Services\PinService;
use Tumainimosha\Verifiable\Tests\VerifiableTestCase;

class PinServiceVerifiableTest extends VerifiableTestCase
{
    /** @test */
    public function it_generates_random_pin()
    {
        $service = $this->getPinService();

        $pin1 = $service->generatePin();
        $pin2 = $service->generatePin();

        $this->assertNotEquals($pin1, $pin2);
    }

    /** @test */
    public function it_returns_token_string_of_specified_length()
    {
        $service = $this->getPinService();

        $pinDefault = $service->generatePin();
        $this->assertEquals(strlen($pinDefault), 4);

        // length 5
        $pin5 = $service->generatePin(5);
        $this->assertEquals(strlen($pin5), 5);

        // length 8
        $pin8 = $service->generatePin(8);
        $this->assertEquals(strlen($pin8), 8);
    }

    /**
     * @return PinService
     */
    protected function getPinService(): PinService
    {
        /** @var PinService $service */
        $service = $this->app->make(PinService::class);

        return $service;
    }
}
