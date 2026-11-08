<?php

namespace FriendsOfRedaxo\Sa11y;

use function array_filter;
use function array_map;
use function explode;
use function in_array;
use function is_array;
use function trim;

/**
 * Prüfungen von Sa11y 5.1 (Schlüssel aus default-options.js), gruppiert für das Formular „Prüfungen abschalten“.
 * Bei einem Sa11y-Update mit neuen Prüfungen hier ergänzen; unbekannte Schlüssel ignoriert Sa11y.
 */
final class Checks
{
    /** @var array<string, list<string>> Gruppe (i18n-Schlüssel) => Prüfungen */
    public const GROUPS = [
        'for_sa11y_checks_group_headings' => [
            'HEADING_SKIPPED_LEVEL', 'HEADING_EMPTY_WITH_IMAGE', 'HEADING_EMPTY', 'HEADING_FIRST',
            'HEADING_LONG', 'HEADING_MISSING_ONE', 'HEADING_UNPRONOUNCEABLE',
        ],
        'for_sa11y_checks_group_images' => [
            'MISSING_ALT_LINK', 'MISSING_ALT_LINK_HAS_TEXT', 'MISSING_ALT', 'IMAGE_DECORATIVE_CAROUSEL',
            'LINK_IMAGE_NO_ALT_TEXT', 'LINK_IMAGE_TEXT', 'IMAGE_FIGURE_DECORATIVE', 'IMAGE_DECORATIVE',
            'LINK_ALT_FILE_EXT', 'ALT_FILE_EXT', 'LINK_PLACEHOLDER_ALT', 'ALT_PLACEHOLDER', 'LINK_SUS_ALT',
            'SUS_ALT', 'LINK_IMAGE_LONG_ALT', 'IMAGE_ALT_TOO_LONG', 'LINK_IMAGE_ALT', 'LINK_IMAGE_ALT_AND_TEXT',
            'IMAGE_FIGURE_DUPLICATE_ALT', 'IMAGE_PASS', 'ALT_UNPRONOUNCEABLE', 'LINK_ALT_UNPRONOUNCEABLE',
            'ALT_MAYBE_BAD', 'LINK_ALT_MAYBE_BAD', 'ALT_MAYBE_BAD_WARNING', 'LINK_ALT_MAYBE_BAD_WARNING',
        ],
        'for_sa11y_checks_group_links' => [
            'DUPLICATE_TITLE', 'LINK_EMPTY_LABELLEDBY', 'LINK_EMPTY_NO_LABEL', 'LINK_STOPWORD',
            'LINK_STOPWORD_ARIA', 'LINK_SYMBOLS', 'LINK_CLICK_HERE', 'LINK_DOI', 'LINK_URL', 'LINK_LABEL',
            'LINK_EMPTY', 'LINK_IDENTICAL_NAME', 'LINK_NEW_TAB', 'LINK_FILE_EXT', 'LINK_UNPRONOUNCEABLE',
        ],
        'for_sa11y_checks_group_forms' => [
            'LABELS_MISSING_IMAGE_INPUT', 'LABELS_INPUT_RESET', 'LABELS_MISSING_LABEL', 'LABELS_NO_FOR_ATTRIBUTE',
            'LABELS_PLACEHOLDER', 'LABELS_ARIA_LABEL_INPUT', 'ARIA_INPUT_FIELD_NAME',
        ],
        'for_sa11y_checks_group_embeds' => [
            'EMBED_AUDIO', 'EMBED_VIDEO', 'EMBED_DATA_VIZ', 'EMBED_UNFOCUSABLE', 'EMBED_MISSING_TITLE', 'EMBED_GENERAL',
        ],
        'for_sa11y_checks_group_quality' => [
            'QA_BAD_LINK', 'QA_STRONG_ITALICS', 'QA_IN_PAGE_LINK', 'QA_DOCUMENT', 'QA_PDF', 'QA_BLOCKQUOTE',
            'TABLES_MISSING_HEADINGS', 'TABLES_SEMANTIC_HEADING', 'TABLES_EMPTY_HEADING', 'TABLES_INVALID_HEADERS_REF',
            'QA_FAKE_HEADING', 'QA_FAKE_LIST', 'QA_UPPERCASE', 'QA_UNDERLINE', 'QA_SUBSCRIPT',
            'QA_NESTED_COMPONENTS', 'QA_JUSTIFY', 'QA_SMALL_TEXT',
        ],
        'for_sa11y_checks_group_meta' => [
            'META_LANG', 'META_LANG_VALID', 'META_LANG_SUGGEST', 'META_SCALABLE', 'META_MAX', 'META_REFRESH',
            'PAGE_LANG_CONFIDENCE', 'LANG_OF_PARTS', 'LANG_MISMATCH', 'LANG_OF_PARTS_ALT',
        ],
        'for_sa11y_checks_group_developer' => [
            'DUPLICATE_ID', 'META_TITLE', 'UNCONTAINED_LI', 'TABINDEX_ATTR', 'HIDDEN_FOCUSABLE', 'LABEL_IN_NAME',
            'BTN_EMPTY', 'BTN_EMPTY_LABELLEDBY', 'BTN_ROLE_IN_NAME', 'LINK_MAYBE_BUTTON', 'BTN_UNPRONOUNCEABLE',
        ],
        'for_sa11y_checks_group_contrast' => [
            'CONTRAST_WARNING', 'CONTRAST_INPUT', 'CONTRAST_ERROR', 'CONTRAST_PLACEHOLDER',
            'CONTRAST_PLACEHOLDER_UNSUPPORTED', 'CONTRAST_ERROR_GRAPHIC', 'CONTRAST_WARNING_GRAPHIC', 'CONTRAST_UNSUPPORTED',
        ],
    ];

    /**
     * Gespeicherter Wert der Mehrfachauswahl (rex_config_form speichert „|A|B|“) → bekannte Prüfungs-Schlüssel.
     *
     * @return list<string>
     */
    public static function parse(mixed $value): array
    {
        $items = is_array($value) ? $value : explode('|', (string) $value);
        $known = array_merge(...array_values(self::GROUPS));

        return array_values(array_filter(
            array_map(static fn ($item): string => trim((string) $item), $items),
            static fn (string $item): bool => '' !== $item && in_array($item, $known, true),
        ));
    }
}
