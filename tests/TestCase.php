<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Faker\Factory as Faker;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;

    protected $faker;

    /**
     * Set up the test
     */
    public function setUp() : void
    {
        parent::setUp();
        $this->faker = Faker::create();
    }
    /**
     * Reset the migrations
     */
    public function tearDown() : void
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }
}
