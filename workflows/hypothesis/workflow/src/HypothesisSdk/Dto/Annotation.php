<?php

declare(strict_types=1);

namespace Workflow\HypothesisSdk\Dto;

final class Annotation
{
    /**
     * @param list<string>           $tags
     * @param list<AnnotationTarget> $target
     */
    public function __construct(
        public readonly string $id,
        public readonly string $created,
        public readonly string $text,
        public readonly string $uri,
        public readonly array $tags,
        public readonly array $target,
        public readonly AnnotationDocument $document,
        public readonly AnnotationLinks $links,
    ) {}

    /** @param array<mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: self::requiredString($data, 'id'),
            created: self::requiredString($data, 'created'),
            text: self::optionalString($data, 'text') ?? '',
            uri: self::requiredString($data, 'uri'),
            tags: self::stringList($data['tags'] ?? [], 'tags'),
            target: self::targets($data['target'] ?? []),
            document: AnnotationDocument::fromArray(self::object($data['document'] ?? [], 'document')),
            links: AnnotationLinks::fromArray(self::object($data['links'] ?? [], 'links')),
        );
    }

    /** @param array<mixed> $data */
    private static function requiredString(array $data, string $key): string
    {
        $value = self::optionalString($data, $key);

        if (null === $value || '' === trim($value)) {
            throw new \UnexpectedValueException(sprintf('Hypothesis annotation field "%s" must be a non-empty string.', $key));
        }

        return $value;
    }

    /** @param array<mixed> $data */
    private static function optionalString(array $data, string $key): ?string
    {
        $value = $data[$key] ?? null;

        if (null !== $value && !is_string($value)) {
            throw new \UnexpectedValueException(sprintf('Hypothesis annotation field "%s" must be a string.', $key));
        }

        return $value;
    }

    /** @return list<string> */
    private static function stringList(mixed $value, string $field): array
    {
        if (!is_array($value) || !array_is_list($value)) {
            throw new \UnexpectedValueException(sprintf('Hypothesis annotation field "%s" must be a list.', $field));
        }

        $strings = [];

        foreach ($value as $item) {
            if (!is_string($item)) {
                throw new \UnexpectedValueException(sprintf('Every Hypothesis annotation "%s" item must be a string.', $field));
            }

            $strings[] = $item;
        }

        return $strings;
    }

    /** @return list<AnnotationTarget> */
    private static function targets(mixed $value): array
    {
        if (!is_array($value) || !array_is_list($value)) {
            throw new \UnexpectedValueException('Hypothesis annotation field "target" must be a list.');
        }

        $targets = [];

        foreach ($value as $item) {
            if (!is_array($item) || (array_is_list($item) && [] !== $item)) {
                throw new \UnexpectedValueException('Every Hypothesis annotation target must be an object.');
            }

            $targets[] = AnnotationTarget::fromArray($item);
        }

        return $targets;
    }

    /** @return array<mixed> */
    private static function object(mixed $value, string $field): array
    {
        if (!is_array($value) || (array_is_list($value) && [] !== $value)) {
            throw new \UnexpectedValueException(sprintf('Hypothesis annotation field "%s" must be an object.', $field));
        }

        return $value;
    }
}
