<?php

namespace Jascha030\WP\Settings;

class HtmlField
{
    public const TEXT = 0;
    public const TEXTAREA = 1;
    public const RADIO = 2;
    public const CHECKBOX = 3;
    public const SELECT = 4;
    public const FILE = 5;
    public const DATE = 6;
    public const DATETIME = 7;

    public const HTML_INPUT_TYPES = [
        self::TEXT     => "text",
        self::TEXTAREA => "textarea",
        self::RADIO    => "radio",
        self::CHECKBOX => "checkbox",
        self::SELECT   => "select",
        self::FILE     => "file",
        self::DATE     => "date",
        self::DATETIME => "datetime-local"
    ];

    public const REGULAR_INPUT_TYPES = [
        self::TEXT,
        self::DATE,
        self::DATETIME
    ];

    public const LOOP_FIELD = [
        self::RADIO,
    ];

    public const FILE_TYPES = [
        'image'       => self::IMAGE_TYPES,
        'application' => self::APPLICATION_FILE_TYPES,
        'audio'       => self::AUDIO_FILE_TYPES,
        'video'       => self::VIDEO_FILE_TYPES
    ];

    public const IMAGE_TYPES = [
        'png',
        'jpeg',
        'gif',
        'x-icon',
        'vnd.adobe.photoshop'
    ];

    public const APPLICATION_FILE_TYPES = [
        'pdf',
        'msword',
        'vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.oasis.opendocument.text',
        'vnd.ms-excel',
        'vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    public const AUDIO_FILE_TYPES = [
        'mpeg',
        'm4a',
        'ogg',
        'wav'
    ];

    public const VIDEO_FILE_TYPES = [
        'mp4',
        'quicktime',
        'x-ms-wmv',
        'avi',
        'mpeg'
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

    public static function validateMimeType(string $mime): bool
    {
        $sections = explode('/', $mime);

        if (! array_key_exists($sections[0], self::FILE_TYPES)) {
            return false;
        }

        if (! in_array(self::FILE_TYPES[$sections[0]], $sections[1], true)) {
            return false;
        }

        return true;
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
