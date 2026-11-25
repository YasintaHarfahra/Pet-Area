<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    public function test_forgot_password_response_does_not_disclose_unknown_emails(): void
    {
        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => 'unknown@example.test'])
            ->andReturn(Password::INVALID_USER);

        $response = $this->from(route('forgot.password'))
            ->post(route('forgot.password.send'), ['email' => 'unknown@example.test']);

        $response->assertRedirect(route('forgot.password'))
            ->assertSessionHas('status', 'Jika email tersebut terdaftar, tautan reset kata sandi akan dikirim.');
    }

    public function test_reset_page_contains_the_broker_token_and_email(): void
    {
        $this->withoutVite()
            ->get(route('password.reset', [
                'token' => 'reset-token',
                'email' => 'doctor@example.test',
            ]))
            ->assertOk()
            ->assertSee('name="email" value="doctor@example.test"', false)
            ->assertSee('name="token" value="reset-token"', false);
    }

    public function test_password_reset_requires_a_token_and_uses_the_broker(): void
    {
        Password::shouldReceive('reset')->never();

        $response = $this->from('/reset-password/some-token?email=doctor@example.test')
            ->post(route('password.update'), [
                'email' => 'doctor@example.test',
                'password' => 'new-test-password',
                'password_confirmation' => 'new-test-password',
            ]);

        $response->assertRedirect('/reset-password/some-token?email=doctor@example.test')
            ->assertSessionHasErrors('token');

        $this->get('/reset-password-direct?email=doctor@example.test')->assertNotFound();
    }

    public function test_reset_password_is_delegated_to_the_broker_with_the_token(): void
    {
        Password::shouldReceive('reset')
            ->once()
            ->withArgs(function (array $credentials, $callback): bool {
                return $credentials['email'] === 'doctor@example.test'
                    && $credentials['token'] === 'valid-token'
                    && is_callable($callback);
            })
            ->andReturn(Password::PASSWORD_RESET);

        $this->post(route('password.update'), [
            'email' => 'doctor@example.test',
            'token' => 'valid-token',
            'password' => 'new-test-password',
            'password_confirmation' => 'new-test-password',
        ])->assertRedirect(route('login'))
            ->assertSessionHas('success');
    }
}
