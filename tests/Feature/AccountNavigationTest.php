<?php

namespace Tests\Feature;

use App\Livewire\AuthComponent;
use Tests\TestCase;

class AccountNavigationTest extends TestCase
{
    public function test_guests_are_redirected_from_the_account_page_to_login(): void
    {
        $this->get(route('account'))
            ->assertRedirect(route('login'));
    }

    public function test_login_route_renders_the_standalone_login_page(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Customer Account')
            ->assertSee('Sign in or create a Kizoku customer account')
            ->assertSee('MASUK');
    }

    public function test_register_route_renders_the_standalone_registration_flow(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('MINTA KODE OTP REGISTRASI');
    }

    public function test_order_history_is_the_default_account_tab(): void
    {
        $component = new AuthComponent;

        $this->assertSame('orders', $component->accountTab);
    }
}
