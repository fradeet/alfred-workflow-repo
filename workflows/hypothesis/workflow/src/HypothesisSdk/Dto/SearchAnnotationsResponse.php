<?php

declare(strict_types=1);

namespace Workflow\HypothesisSdk\Dto;

final class SearchAnnotationsResponse
{
    /** @param list<Annotation> $rows */
    public function __construct(
        public readonly int $total,
        public readonly array $rows,
    ) {}

    /** @param array<mixed> $data */
    public static function fromArray(array $data): self
    {
        $total = $data['total'] ?? null;
        $rows = $data['rows'] ?? null;

        if (!is_int($total) || $total < 0) {
            throw new \UnexpectedValueException('Hypothesis search field "total" must be a non-negative integer.');
        }

        if (!is_array($rows) || !array_is_list($rows)) {
            throw new \UnexpectedValueException('Hypothesis search field "rows" must be a list.');
        }

        $annotations = [];

        foreach ($rows as $row) {
            if (!is_array($row) || array_is_list($row)) {
                throw new \UnexpectedValueException('Every Hypothesis search row must be an object.');
            }

            $annotations[] = Annotation::fromArray($row);
        }

        return new self($total, $annotations);
    }
}
