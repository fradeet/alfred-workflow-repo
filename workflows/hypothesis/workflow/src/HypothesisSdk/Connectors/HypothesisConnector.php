<?php

declare(strict_types=1);

namespace Workflow\HypothesisSdk\Connectors;

use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;

final class HypothesisConnector extends Connector
{
    public function __construct(private readonly string $apiKey)
    {
        if ('' === trim($apiKey)) {
            throw new \InvalidArgumentException('The Hypothesis API key must not be empty.');
        }
    }

    public function resolveBaseUrl(): string
    {
        return 'https://hypothes.is/api';
    }

    #[\Override]
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/vnd.hypothesis.v1+json',
            'Content-Type' => 'application/json',
            'User-Agent' => 'alfred-hypothesis-workflow/1.0',
        ];
    }

    #[\Override]
    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->apiKey);
    }
}
