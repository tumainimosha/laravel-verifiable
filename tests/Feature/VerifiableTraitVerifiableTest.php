<?php

namespace Tumainimosha\Verifiable\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tumainimosha\Verifiable\Tests\Models\Order;
use Tumainimosha\Verifiable\Tests\VerifiableTestCase;

class VerifiableTraitVerifiableTest extends VerifiableTestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_verification_token()
    {
        /** @var Order $order */
        $order = factory(Order::class)->create();

        $token = $order->getVerificationToken();

        $this->assertIsString($token);

        // Code exists in db
        $this->assertDatabaseHas('verification_codes', ['code' => $token]);
    }

    /** @test */
    public function itVerifiesValidTokens()
    {
        /** @var Order $order */
        $order = factory(Order::class)->create();

        $token = $order->getVerificationToken();

        $verStatus = $order->verifyToken($token);

        $this->assertEquals(true, $verStatus['status']);
    }

    /** @test */
    public function itDoesntVerifyInvalidTokens()
    {
        /** @var Order $order */
        $order = factory(Order::class)->create();

        $order->getVerificationToken();

        $invalidToken = 'INVALID';

        $verStatus = $order->verifyToken($invalidToken);

        $this->assertEquals(false, $verStatus['status']);
    }

    /** @test */
    public function itDoesntVerifyATokenAfterExceedingMaximumAttemptsCount()
    {
        $limit = 4;

        $this->app['config']->set('verifiable.max_attempts', $limit);

        /** @var Order $order */
        $order = factory(Order::class)->create();

        $validToken = $order->getVerificationToken();

        $invalidToken = 'INVALID';

        for ($i = 0;$i < $limit; $i++) {
            $verStatus = $order->verifyToken($invalidToken);
            $this->assertEquals(false, $verStatus['status']);
        }

        $verStatus = $order->verifyToken($validToken);

        $this->assertFalse($verStatus['status']);
        $this->assertEquals($verStatus['statusDesc'], 'MaxAttemptsExceeded');
    }

    /** @test */
    public function itDoesNotVerifyExpiredTokens()
    {
        // set ttl to zero to expire tokens immediately
        $this->app['config']->set('verifiable.token_ttl', 0);

        /** @var Order $order */
        $order = factory(Order::class)->create();

        $expiredToken = $order->getVerificationToken();

        $verificationStatus = $order->verifyToken($expiredToken);

        $this->assertFalse($verificationStatus['status']);
        $this->assertEquals($verificationStatus['statusDesc'], 'Expired');
    }

    /** @test */
    public function itDoesNotVerifyModelWithoutAToken()
    {
        $order = factory(Order::class)->create();

        $nonExistentToken = 'DOES_NOT_EXIST';

        $verificationStatus = $order->verifyToken($nonExistentToken);

        $this->assertFalse($verificationStatus['status']);
        $this->assertEquals($verificationStatus['statusDesc'], 'NotFound');
    }
}
