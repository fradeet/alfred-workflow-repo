#!/usr/bin/env php
<?php

declare(strict_types=1);

use Workflow\AlfredAdapters\Type\AlfredSF;
use Workflow\AlfredAdapters\Type\AlfredSFCache;
use Workflow\AlfredAdapters\Type\AlfredSFItem;
use Workflow\AlfredAdapters\Type\AlfredSFItemText;
use Workflow\HypothesisSdk\Connectors\HypothesisConnector;
use Workflow\HypothesisSdk\Dto\Annotation;
use Workflow\HypothesisSdk\Dto\AnnotationDocument;
use Workflow\HypothesisSdk\Dto\AnnotationTarget;
use Workflow\RecentAnnotations;

use function Workflow\AlfredAdapters\Support\alfredEnvironmentVariable;
use function Workflow\AlfredAdapters\Support\run;

require dirname(__DIR__, 2).'/vendor/autoload.php';

run(
    static function (array $arguments): AlfredSF {
        if ([] !== $arguments) {
            throw new InvalidArgumentException('RecentAnnotations does not accept positional arguments.');
        }

        $apiKey = alfredEnvironmentVariable('HYPO_API_KEY');

        if (null === $apiKey || '' === trim($apiKey)) {
            throw new RuntimeException('HYPO_API_KEY is not configured.');
        }

        $annotations = (new RecentAnnotations(new HypothesisConnector($apiKey)))();
        $items = array_map(recentAnnotationItem(...), $annotations);

        if ([] === $items) {
            $items[] = new AlfredSFItem(
                title: 'No Recent Annotations',
                subtitle: 'Create an annotation in Hypothesis and try again',
                valid: false,
            );
        }

        return new AlfredSF(
            items: $items,
            cache: new AlfredSFCache(300),
            skipknowledge: true,
        );
    },
);

function recentAnnotationItem(Annotation $annotation): AlfredSFItem
{
    $quote = recentAnnotationQuote($annotation->target);
    $documentTitle = recentAnnotationDocumentTitle($annotation->document);
    $sourceUrl = $annotation->uri;
    $title = recentAnnotationFirstNonEmpty(
        $quote,
        $annotation->text,
        $documentTitle,
    ) ?? 'Untitled Annotation';
    $title = recentAnnotationSingleLine($title);
    $text = recentAnnotationSingleLine($annotation->text);
    $quote = null === $quote ? null : recentAnnotationSingleLine($quote);
    $documentTitle = null === $documentTitle ? null : recentAnnotationSingleLine($documentTitle);

    try {
        $created = new DateTimeImmutable($annotation->created);
    } catch (Exception $exception) {
        throw new RuntimeException('Hypothesis returned an invalid annotation creation time.', previous: $exception);
    }

    $details = [];

    if (null !== $documentTitle && $title !== $documentTitle) {
        $details[] = $documentTitle;
    }

    if ('' !== $text && $title !== $text) {
        $details[] = $text;
    }

    $details[] = $created->format('Y-m-d H:i');

    return new AlfredSFItem(
        title: $title,
        arg: $sourceUrl,
        match: implode(' ', array_filter([
            $quote,
            $text,
            $documentTitle,
            implode(' ', $annotation->tags),
            $annotation->uri,
        ], static fn (?string $value): bool => null !== $value && '' !== $value)),
        quicklookurl: $sourceUrl,
        subtitle: implode(' · ', $details),
        text: new AlfredSFItemText(
            copy: $title,
            largetype: '' === $text ? $title : $text,
        ),
        uid: $annotation->id,
    );
}

/** @param list<AnnotationTarget> $target */
function recentAnnotationQuote(array $target): ?string
{
    foreach ($target as $item) {
        foreach ($item->selector as $selector) {
            if ('TextQuoteSelector' !== $selector->type) {
                continue;
            }

            if (null === $selector->exact) {
                throw new UnexpectedValueException('A Hypothesis TextQuoteSelector exact value must be a string.');
            }

            if ('' !== trim($selector->exact)) {
                return $selector->exact;
            }
        }
    }

    return null;
}

function recentAnnotationDocumentTitle(AnnotationDocument $document): ?string
{
    foreach ($document->title as $title) {
        if ('' !== trim($title)) {
            return $title;
        }
    }

    return null;
}

function recentAnnotationFirstNonEmpty(?string ...$values): ?string
{
    foreach ($values as $value) {
        if (null !== $value && '' !== trim($value)) {
            return $value;
        }
    }

    return null;
}

function recentAnnotationSingleLine(string $value): string
{
    return trim((string) preg_replace('/\s+/u', ' ', $value));
}
