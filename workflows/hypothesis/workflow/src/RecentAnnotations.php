<?php

declare(strict_types=1);

namespace Workflow;

use Workflow\HypothesisSdk\Connectors\HypothesisConnector;
use Workflow\HypothesisSdk\Dto\Annotation;
use Workflow\HypothesisSdk\Dto\Profile;
use Workflow\HypothesisSdk\Dto\SearchAnnotationsResponse;
use Workflow\HypothesisSdk\Requests\GetProfileRequest;
use Workflow\HypothesisSdk\Requests\SearchAnnotationsRequest;

final class RecentAnnotations
{
    public function __construct(private readonly HypothesisConnector $connector) {}

    /** @return list<Annotation> */
    public function __invoke(): array
    {
        try {
            $profile = $this->connector->send(new GetProfileRequest())->dtoOrFail();

            if (!$profile instanceof Profile) {
                throw new \UnexpectedValueException('Hypothesis returned an invalid profile.');
            }

            $search = $this->connector->send(new SearchAnnotationsRequest($profile->userid, 100))->dtoOrFail();

            if (!$search instanceof SearchAnnotationsResponse) {
                throw new \UnexpectedValueException('Hypothesis returned invalid search results.');
            }
        } catch (\Throwable $exception) {
            throw new \RuntimeException('Unable to request recent Hypothesis annotations.', previous: $exception);
        }

        return $search->rows;
    }
}
