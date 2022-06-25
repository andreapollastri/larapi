<?php

namespace Andr3a\Larapi\Tests\Unit;

use Andr3a\Larapi\Larapi;
use Andr3a\Larapi\Tests\TestCase;

class LarapiTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function larapi_instantiate_correctly()
    {
        $service = $this->app->make(Larapi::class);

        $this->assertInstanceOf('Andr3a\Larapi\Larapi', $service);
    }
}
