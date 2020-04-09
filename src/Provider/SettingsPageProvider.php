<?php

namespace Jascha030\WPSettings\Provider;

use Jascha030\WPOL\Subscription\Provider\ActionProvider;
use Jascha030\WPOL\Subscription\Provider\Provider;

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
        string $capability = "manage_options"
    ) {
        parent::__construct($title, $prefix, $section, $settings, $capability, false);
    }
}
