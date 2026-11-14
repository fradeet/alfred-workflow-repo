<?php

declare(strict_types=1);

namespace Workflow\HypothesisSdk\Dto;

final class AnnotationLinks
{
    public function __construct(
        public readonly ?string $html = null,
        public readonly ?string $incontext = null,
    ) {}

    /** @param array<mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            html: self::optionalString($data, 'html'),
            incontext: self::optionalString($data, 'incontext'),
        );
    }

    /** @param array<mixed> $data */
    private static function optionalString(array $data, string $key): ?string
    {
        $value = $data[$key] ?? null;

        if (null !== $value && !is_string($value)) {
            throw new \UnexpectedValueException(sprintf('Hypothesis annotation link field "%s" must be a string.', $key));
        }

        return $value;
    }
}
