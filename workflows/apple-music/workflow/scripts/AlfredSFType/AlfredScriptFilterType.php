<?php

/**
 * Base value object for Alfred Script Filter JSON structures.
 *
 * Null properties are omitted from the JSON output; false, zero, and empty
 * values are retained because they can have meaning in Alfred's JSON format.
 */
class AlfredSFBase implements JsonSerializable
{
    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return array_filter(get_object_vars($this), fn($v) => $v !== null);
    }
}

/**
 * Top-level response returned by an Alfred Script Filter.
 *
 * A response must contain an items array, which may be empty. Session
 * variables are passed back to later runs of the same Script Filter session.
 *
 * @author fradeet
 * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/ Alfred Script Filter JSON format
 * @property list<AlfredSFItem> $items Result rows displayed by Alfred.
 * @property array<string, mixed>|null $variables Session variables available to downstream objects
 *                                                and subsequent runs.
 * @property string|null $rerun Automatic rerun interval. Alfred expects a JSON number from 0.1 to 5.0 seconds.
 * @property AlfredSFCache|null $cache Result-cache configuration, available in Alfred 5.5 and later.
 * @property bool|null $skipknowledge Preserve the supplied order instead of applying Alfred's learned result ordering.
 * @version 1.0.1
 */
class AlfredSF extends AlfredSFBase
{
    public function __construct(
        public array $items,
        public ?array $variables = null,
        public ?string $rerun = null,
        public ?AlfredSFCache $cache = null,
        public ?bool $skipknowledge = null,
    ) {}
}

/**
 * A single result row in an Alfred Script Filter response.
 *
 * Only the title is required by Alfred. Supplying a stable UID lets Alfred
 * learn the result's ranking; omit it when the returned order must be kept.
 *
 * @property string $title Primary text displayed in the result row; required and non-empty.
 * @property array<array-key, mixed>|string|AlfredSFItemAction|null $action Universal Action content;
 *                                                                       overrides arg for that action.
 * @property string|list<string>|null $arg Value passed to the connected workflow action when selected.
 * @property string|null $autocomplete Text inserted into Alfred's search field when autocompleted.
 * @property AlfredSFItemIcon|null $icon Result icon; relative paths resolve from the workflow root.
 * @property string|null $match Text used instead of title when Alfred Filters Results is enabled.
 * @property array<string, array<string, mixed>>|null $mods Modifier-key overrides for cmd, alt, ctrl,
 *                                                               shift, fn, or combinations.
 * @property string|null $quicklookurl Quick Look URL or path; Alfred falls back to arg when omitted.
 * @property string|null $subtitle Secondary text displayed below the title.
 * @property AlfredSFItemType|null $type Controls whether Alfred treats the result as an item or file.
 * @property AlfredSFItemText|null $text Text used for Copy (Command-C) and Large Type (Command-L).
 * @property string|null $uid Stable identifier used by Alfred to learn result ordering.
 * @property array<string, mixed>|null $variables Variables emitted when selected; item values override
 *                                                session values.
 * @property bool|null $valid Whether Return can action the item; defaults to true in Alfred.
 */
class AlfredSFItem extends AlfredSFBase
{
    public function __construct(
        public string $title,
        public null|array|string|AlfredSFItemAction $action = null,
        public null|string|array $arg = null,
        public ?string $autocomplete = null,
        public ?AlfredSFItemIcon $icon = null,
        public ?string $match = null,
        public ?array $mods = null,
        public ?string $quicklookurl = null,
        public ?string $subtitle = null,
        public ?AlfredSFItemType $type = null,
        public ?AlfredSFItemText $text = null,
        public ?string $uid = null,
        public ?array $variables = null,
        public ?bool $valid = null,
    ) {}
}

/**
 * Typed Universal Action content for a result.
 *
 * Use auto to let Alfred infer the content type, or text, url, and file to
 * explicitly identify it.
 *
 * @property string|list<string>|null $text Text content.
 * @property string|null $url URL content.
 * @property string|null $file File-path content.
 * @property string|null $auto Content whose type Alfred should infer.
 */
class AlfredSFItemAction extends AlfredSFBase
{
    public function __construct(
        public null|string|array $text = null,
        public ?string $url = null,
        public ?string $file = null,
        public ?string $auto = null,
    ) {}
}

/**
 * Configuration for Alfred's automatic Script Filter result cache.
 *
 * @property int $seconds Cache lifetime from 5 to 86400 seconds (24 hours).
 * @property bool|null $loosereload Show cached data first and refresh stale results in the background.
 */
class AlfredSFCache extends AlfredSFBase
{
    public function __construct(
        public int $seconds,
        public ?bool $loosereload = null,
    ) {}
}

/**
 * Text used when the user copies a result or displays it in Large Type.
 *
 * @property string $copy Text copied with Command-C.
 * @property string $largetype Text displayed with Command-L.
 */
class AlfredSFItemText extends AlfredSFBase
{
    public function __construct(
        public string $copy,
        public string $largetype,
    ) {}
}

/** How Alfred should interpret and validate a result item. */
enum AlfredSFItemType: string
{
    /** A normal, non-file result. */
    case Default = "default";

    /** A file result; Alfred verifies that the path exists before displaying it. */
    case File = "file";

    /** A file result for which Alfred skips the path-existence check. */
    case FileSkipcheck = "file:skipcheck";
}

/**
 * Icon displayed alongside a Script Filter result.
 *
 * @property string $path Image path, file path, or UTI depending on type.
 * @property string|null $type Use fileicon for a path's icon or filetype for a UTI; omit for an image path.
 */
class AlfredSFItemIcon extends AlfredSFBase
{
    public function __construct(
        public string $path,
        public ?string $type = null,
    ) {}
}

define(
    "RETURN_ERROR_ALFRED",
    new AlfredSF(
        items: [
            new AlfredSFItem(
                title: "Unable to Load Results",
                subtitle: "Open the debugger and try again",
                valid: false,
            ),
        ],
    ),
);
