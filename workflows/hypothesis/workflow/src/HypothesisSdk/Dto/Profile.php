<?php

declare(strict_types=1);

namespace Workflow\HypothesisSdk\Dto;

final class Profile
{
    public function __construct(public readonly string $userid) {}

    /** @param array<mixed> $data */
    public static function fromArray(array $data): self
    {
        $userid = $data['userid'] ?? null;

        if (!is_string($userid) || '' === trim($userid)) {
            throw new \UnexpectedValueException('The Hypothesis profile does not contain an authenticated userid.');
        }

        return new self($userid);
    }
}
