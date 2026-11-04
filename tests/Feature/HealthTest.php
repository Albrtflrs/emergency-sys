<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthTest extends TestCase
{
    public function test_it_responds_on_the_health_endpoint(): void
    {
        $this->get('/up')->assertOk();
    }
}