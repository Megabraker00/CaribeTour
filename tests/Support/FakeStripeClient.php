<?php

namespace Tests\Support;

use Stripe\HttpClient\ClientInterface;

class FakeStripeClient implements ClientInterface
{
    /**
     * @var array<int, array{0: string, 1: string}>
     */
    public array $requests = [];

    public int $amountRefunded = 0;

    public int $refundStatusCode = 200;

    public function __construct(private string $status = 'requires_payment_method')
    {
    }

    public function request($method, $absUrl, $headers, $params, $hasFile, $apiMode = 'v1', $maxNetworkRetries = null)
    {
        $this->requests[] = [$method, $absUrl];

        if (str_contains($absUrl, '/refunds')) {
            return $this->refundResponse($method);
        }

        $status = str_contains($absUrl, '/cancel') ? 'canceled' : $this->status;
        $body = json_encode([
            'id' => 'pi_test_expire',
            'object' => 'payment_intent',
            'amount' => 22000,
            'amount_refunded' => $this->amountRefunded,
            'currency' => 'eur',
            'status' => $status,
            'metadata' => ['booking_id' => '1'],
        ], JSON_THROW_ON_ERROR);

        return [$body, 200, []];
    }

    /**
     * @return array{0: string, 1: int, 2: array<string, string>}
     */
    private function refundResponse(string $method): array
    {
        if (strtolower($method) === 'get') {
            $body = json_encode([
                'object' => 'list',
                'data' => [$this->refundPayload()],
                'has_more' => false,
                'url' => '/v1/refunds',
            ], JSON_THROW_ON_ERROR);

            return [$body, 200, []];
        }

        if ($this->refundStatusCode !== 200) {
            $body = json_encode([
                'error' => [
                    'message' => 'Refund refused',
                    'type' => 'invalid_request_error',
                    'code' => 'charge_disputed',
                ],
            ], JSON_THROW_ON_ERROR);

            return [$body, $this->refundStatusCode, []];
        }

        return [json_encode($this->refundPayload(), JSON_THROW_ON_ERROR), 200, []];
    }

    /**
     * @return array<string, int|string>
     */
    private function refundPayload(): array
    {
        return [
            'id' => 're_test_refund',
            'object' => 'refund',
            'amount' => 22000,
            'currency' => 'eur',
            'payment_intent' => 'pi_test_expire',
            'status' => 'succeeded',
        ];
    }
}
