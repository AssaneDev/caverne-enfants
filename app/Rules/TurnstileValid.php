<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TurnstileValid implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Skip validation if Turnstile is not configured
        if (empty(config('turnstile.secret_key')) || empty(config('turnstile.site_key'))) {
            Log::warning('Turnstile validation skipped: keys not configured');
            return;
        }

        // Check if value is provided
        if (empty($value)) {
            $fail('Veuillez compléter la vérification de sécurité.');
            return;
        }

        try {
            // Verify with Cloudflare
            $response = Http::asForm()->post(config('turnstile.verify_url'), [
                'secret' => config('turnstile.secret_key'),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            if (!$response->successful()) {
                Log::error('Turnstile API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                $fail('La vérification de sécurité a échoué. Veuillez réessayer.');
                return;
            }

            $result = $response->json();

            if (!isset($result['success']) || !$result['success']) {
                Log::warning('Turnstile validation failed', [
                    'error_codes' => $result['error-codes'] ?? [],
                    'ip' => request()->ip()
                ]);
                $fail('La vérification de sécurité a échoué. Veuillez réessayer.');
                return;
            }

            // Optional: Check hostname
            if (isset($result['hostname']) && !empty(config('app.url'))) {
                $expectedHost = parse_url(config('app.url'), PHP_URL_HOST);
                if ($result['hostname'] !== $expectedHost) {
                    Log::warning('Turnstile hostname mismatch', [
                        'expected' => $expectedHost,
                        'received' => $result['hostname']
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('Turnstile validation exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $fail('Une erreur est survenue lors de la vérification de sécurité.');
        }
    }
}
