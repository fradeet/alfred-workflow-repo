<?php

declare(strict_types=1);

namespace Workflow\HypothesisSdk\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Workflow\HypothesisSdk\Dto\Profile;

final class GetProfileRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/profile';
    }

    public function createDtoFromResponse(Response $response): Profile
    {
        $data = $response->json();

        if (array_is_list($data)) {
            throw new \UnexpectedValueException('The Hypothesis profile response must be an object.');
        }

        return Profile::fromArray($data);
    }
}
