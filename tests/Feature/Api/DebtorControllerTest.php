<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DebtorControllerTest extends TestCase
{
    /** test */
    public function get_top_should_return_top_debtors_by_month()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
