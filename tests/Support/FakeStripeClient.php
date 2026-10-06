<?php

namespace Tests\Support;

use Stripe\HttpClient\ClientInterface;

class FakeStripeClient implements ClientInterface
{
    /**
     * @var array<int, array{0: string, 1: string}>
     */
    public array $requests = [];

    public function __construct(private string $status = 'requires_payment_method')
    {
    }

    public function request($method, $absUrl, $headers, $params, $hasFile, $apiMode = 'v1', $maxNetworkRetries = null)
    {
        $this->requests[] = [$method, $absUrl];

        $status = str_contains($absUrl, '/cancel') ? 'canceled' : $this->status;
        $body = json_encode([
            'id' => 'pi_test_expire',
            'object' => 'payment_intent',
            'amount' => 22000,
            'currency' => 'eur',
            'status' => $status,
            'metadata' => ['booking_id' => '1'],
        ], JSON_THROW_ON_ERROR);

        return [$body, 200, []];
    }
}
