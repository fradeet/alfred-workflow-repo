<?php

declare(strict_types=1);

namespace Workflow\HypothesisSdk\Dto;

final class AnnotationDocument
{
    /** @param list<string> $title */
    public function __construct(public readonly array $title) {}

    /** @param array<mixed> $data */
    public static function fromArray(array $data): self
    {
        $titles = $data['title'] ?? [];

        if (!is_array($titles) || !array_is_list($titles)) {
            throw new \UnexpectedValueException('Hypothesis annotation document field "title" must be a list.');
        }

        $title = [];

        foreach ($titles as $item) {
            if (!is_string($item)) {
                throw new \UnexpectedValueException('Every Hypothesis annotation document title must be a string.');
            }

            $title[] = $item;
        }

        return new self($title);
    }
}
