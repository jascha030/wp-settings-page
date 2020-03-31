<?php

namespace Jascha030\WPSettings;

class HtmlField
{
    const TEXT = 0;
    const TEXTAREA = 1;
    const RADIO = 2;
    const CHECKBOX = 3;
    const SELECT = 4;

    const HTML_INPUT_TYPES = [
        self::TEXT     => "text",
        self::TEXTAREA => "textarea",
        self::RADIO    => "radio",
        self::CHECKBOX => "checkbox",
        self::SELECT   => "select"
    ];

    const REGULAR_INPUT_TYPES = [
        self::TEXT,
    ];

    const LOOP_FIELD = [
        self::RADIO,
        self::CHECKBOX
    ];

    /**
     * @param int $fieldType
     *
     * @return bool
     */
    public static function validateFieldType(int $fieldType): bool
    {
        return (array_key_exists($fieldType, self::HTML_INPUT_TYPES));
    }

    /**
     * @param int $fieldType
     *
     * @return bool
     */
    public static function isRegularInput(int $fieldType): bool
    {
        return (in_array($fieldType, self::REGULAR_INPUT_TYPES));
    }

    public static function isLoopable(int $fieldType): bool
    {
        return (in_array($fieldType, self::LOOP_FIELD));
    }

    public static function getInputType(int $fieldType): string
    {
        return self::HTML_INPUT_TYPES[$fieldType];
    }
}
