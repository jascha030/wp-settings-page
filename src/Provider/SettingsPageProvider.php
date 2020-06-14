<?php

namespace Jascha030\WP\Settings\Provider;

use Jascha030\WP\Subscriptions\Provider\ActionProvider;

/**
 * Class SettingsPageProvider
 *
 * @package Jascha030\WPSettings\Page
 */
class SettingsPageProvider implements ActionProvider
{
    protected static $actions = [
        'admin_menu' => 'registerPage',
        'admin_init' => 'registerSettings'
    ];

    public function __construct(
        string $title,
        string $prefix = null,
        string $section = null,
        array $settings = null,
        string $slug = null,
        string $capability = "manage_options",
        bool $init = false
    ) {
        parent::__construct($title, $prefix, $section, $settings, $slug, $capability, $init);
    }
}
