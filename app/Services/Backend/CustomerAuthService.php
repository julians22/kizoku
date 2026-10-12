<?php

namespace App\Services\Backend;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CustomerAuthService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.backend.base_url', 'http://karuizawa.test'), '/');
    }

    /**
     * Request OTP for customer login.
     */
    public function requestLoginOtp(string $email): array
    {
        return $this->post('/api/customer/auth/login/otp/request', [
            'email' => $email,
        ]);
    }

    /**
     * Login customer with email, password, and OTP.
     */
    public function login(string $email, string $password, string $otp): array
    {
        return $this->post('/api/customer/auth/login', [
            'email' => $email,
            'password' => $password,
            'otp' => $otp,
        ]);
    }

    /**
     * Issue an API token with email and password only (no OTP), used by the
     * Kizoku login flow so checkout calls can authenticate as the customer.
     */
    public function issueToken(string $email, string $password): array
    {
        return $this->post('/api/customer/auth/token', [
            'email' => $email,
            'password' => $password,
        ]);
    }

    /**
     * Request OTP for customer registration.
     */
    public function requestRegisterOtp(string $email): array
    {
        return $this->post('/api/customer/auth/register/otp/request', [
            'email' => $email,
        ]);
    }

    /**
     * Register a new customer with OTP.
     */
    public function register(array $payload): array
    {
        return $this->post('/api/customer/auth/register', $payload);
    }

    /**
     * Request OTP for reset password.
     */
    public function requestResetPasswordOtp(string $email): array
    {
        return $this->post('/api/customer/auth/reset-password/otp/request', [
            'email' => $email,
        ]);
    }

    /**
     * Reset password with OTP.
     */
    public function resetPassword(string $email, string $otp, string $password, string $passwordConfirmation): array
    {
        return $this->post('/api/customer/auth/reset-password', [
            'email' => $email,
            'otp' => $otp,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ]);
    }

    /**
     * Request OTP for set password (for customers without password).
     */
    public function requestSetPasswordOtp(string $email, ?string $fullName = null): array
    {
        return $this->post('/api/customer/auth/set-password/otp/request', [
            'email' => $email,
            'full_name' => $fullName,
        ]);
    }

    /**
     * Set password with OTP.
     */
    public function setPassword(string $email, string $otp, string $password, string $passwordConfirmation, ?string $fullName = null): array
    {
        return $this->post('/api/customer/auth/set-password', [
            'email' => $email,
            'otp' => $otp,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
            'full_name' => $fullName,
        ]);
    }

    /**
     * Get current authenticated customer details from backend.
     */
    public function me(string $token): array
    {
        $response = Http::acceptJson()
            ->withToken($token)
            ->timeout(10)
            ->get("{$this->baseUrl}/api/customer/auth/me");

        return $this->handleResponse($response);
    }

    /**
     * Logout customer from backend.
     */
    public function logout(string $token): array
    {
        $response = Http::acceptJson()
            ->withToken($token)
            ->timeout(10)
            ->post("{$this->baseUrl}/api/customer/auth/logout");

        return $this->handleResponse($response);
    }

    /**
     * Perform POST request to backend API.
     */
    private function post(string $endpoint, array $data): array
    {
        $url = "{$this->baseUrl}{$endpoint}";

        $response = Http::acceptJson()
            ->timeout(10)
            ->post($url, $data);

        return $this->handleResponse($response);
    }

    /**
     * Handle API response and extract message / errors.
     */
    private function handleResponse(Response $response): array
    {
        $data = $response->json() ?? [];

        if ($response->successful()) {
            return $data;
        }

        $message = $data['message'] ?? null;

        if (!$message && isset($data['errors']) && is_array($data['errors'])) {
            $firstError = reset($data['errors']);
            $message = is_array($firstError) ? reset($firstError) : (string) $firstError;
        }

        if (!$message) {
            $message = 'Terjadi kesalahan pada server backend (HTTP ' . $response->status() . ').';
        }

        throw new RuntimeException($message, $response->status());
    }
}
