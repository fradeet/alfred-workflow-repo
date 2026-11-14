<?php

declare(strict_types=1);

namespace Workflow\HypothesisSdk\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Workflow\HypothesisSdk\Dto\SearchAnnotationsResponse;

final class SearchAnnotationsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly string $userid,
        private readonly int $limit = 20,
    ) {
        if ('' === trim($userid)) {
            throw new \InvalidArgumentException('The Hypothesis userid must not be empty.');
        }

        if ($limit < 1 || $limit > 200) {
            throw new \InvalidArgumentException('The Hypothesis search limit must be between 1 and 200.');
        }
    }

    public function resolveEndpoint(): string
    {
        return '/search';
    }

    public function createDtoFromResponse(Response $response): SearchAnnotationsResponse
    {
        $data = $response->json();

        if (array_is_list($data)) {
            throw new \UnexpectedValueException('The Hypothesis search response must be an object.');
        }

        return SearchAnnotationsResponse::fromArray($data);
    }

    /** @return array<string, int|string> */
    protected function defaultQuery(): array
    {
        return [
            'user' => $this->userid,
            'limit' => $this->limit,
            'sort' => 'created',
            'order' => 'desc',
        ];
    }
}
