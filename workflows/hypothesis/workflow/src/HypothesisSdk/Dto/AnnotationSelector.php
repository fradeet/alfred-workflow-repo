<?php

declare(strict_types=1);

namespace Workflow\HypothesisSdk\Dto;

final class AnnotationSelector
{
    public function __construct(
        public readonly string $type,
        public readonly ?string $exact = null,
    ) {}

    /** @param array<mixed> $data */
    public static function fromArray(array $data): self
    {
        $type = $data['type'] ?? null;
        $exact = $data['exact'] ?? null;

        if (!is_string($type) || '' === trim($type)) {
            throw new \UnexpectedValueException('Hypothesis annotation selector field "type" must be a non-empty string.');
        }

        if (null !== $exact && !is_string($exact)) {
            throw new \UnexpectedValueException('Hypothesis annotation selector field "exact" must be a string.');
        }

        return new self(type: $type, exact: $exact);
    }
}
