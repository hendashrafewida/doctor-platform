<?php

namespace Tests\Feature;

use App\Models\Customer;
use Carbon\Carbon;
use Tests\TestCase;

class CustomerSubscriptionAccessorTest extends TestCase
{
    public function test_remaining_days_is_a_whole_number_in_africa_cairo_timezone(): void
    {
        Carbon::setTestNow('2026-09-15 12:00:00');

        $customer = new Customer([
            'subscription_end_date' => '2026-09-30',
        ]);

        $this->assertSame(15, $customer->remaining_days);
    }

    public function test_remaining_days_is_null_when_subscription_has_no_end_date(): void
    {
        $customer = new Customer;

        $this->assertNull($customer->remaining_days);
    }
}
