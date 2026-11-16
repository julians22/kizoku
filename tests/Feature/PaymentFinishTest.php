<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class PaymentFinishTest extends TestCase
{
    public function test_guests_are_redirected_from_the_finish_page_to_login(): void
    {
        $this->get(route('checkout.finish'))
            ->assertRedirect(route('login'));
    }

    public function test_finish_page_shows_not_found_state_without_an_order_id(): void
    {
        $this->actingAs(new User(['name' => 'Test Customer', 'email' => 'customer@example.com']));

        $this->get(route('checkout.finish'))
            ->assertOk()
            ->assertSee('Transaksi tidak ditemukan')
            ->assertSee('Lihat Riwayat Pemesanan');
    }

    public function test_finish_page_shows_transaction_details_with_zero_fee_fallback(): void
    {
        // No server key: the component falls back to the redirect query parameters
        // instead of calling the Midtrans Get Status API.
        config(['services.midtrans.server_key' => null]);

        $this->actingAs(new User(['name' => 'Test Customer', 'email' => 'customer@example.com']));

        $this->get(route('checkout.finish', [
            'order_id' => 'KZK-20260101000000-ABCDEF',
            'status_code' => '200',
            'transaction_status' => 'settlement',
        ]))
            ->assertOk()
            ->assertSee('KZK-20260101000000-ABCDEF')
            ->assertSee('Status Pesanan')
            ->assertSee('Biaya Layanan')
            ->assertSee('Rp.0')
            ->assertSee('Lihat Riwayat Pemesanan');
    }

    public function test_finish_page_marks_pending_transactions_as_pending(): void
    {
        config(['services.midtrans.server_key' => null]);

        $this->actingAs(new User(['name' => 'Test Customer', 'email' => 'customer@example.com']));

        $this->get(route('checkout.finish', [
            'order_id' => 'KZK-20260101000000-ABCDEF',
            'status_code' => '201',
            'transaction_status' => 'pending',
        ]))
            ->assertOk()
            ->assertSee('Menunggu pembayaran Anda.');
    }
}
