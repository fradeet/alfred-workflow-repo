<?php

declare(strict_types=1);

namespace Workflow\HypothesisSdk\Dto;

final class AnnotationTarget
{
    /** @param list<AnnotationSelector> $selector */
    public function __construct(public readonly array $selector) {}

    /** @param array<mixed> $data */
    public static function fromArray(array $data): self
    {
        $selectors = $data['selector'] ?? [];

        if (!is_array($selectors) || !array_is_list($selectors)) {
            throw new \UnexpectedValueException('Hypothesis annotation target field "selector" must be a list.');
        }

        $selector = [];

        foreach ($selectors as $item) {
            if (!is_array($item) || (array_is_list($item) && [] !== $item)) {
                throw new \UnexpectedValueException('Every Hypothesis annotation selector must be an object.');
            }

            $selector[] = AnnotationSelector::fromArray($item);
        }

        return new self($selector);
    }
}
