<?php

namespace Jascha030\WP\Settings\Provider;

use Jascha030\WP\Settings\SettingsPage;
use Jascha030\WP\Subscriptions\Provider\ActionProvider;
use Jascha030\WP\Subscriptions\Provider\Provider;

/**
 * Class SettingsPageProvider
 *
 * @package Jascha030\WPSettings\Page
 */
class SettingsPageProvider extends SettingsPage implements ActionProvider
{
    USE Provider;

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
