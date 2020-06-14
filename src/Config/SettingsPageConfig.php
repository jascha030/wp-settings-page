<?php

namespace Jascha030\WP\Settings\Config;

use Jascha030\WP\Plugin\Core\Config\PluginComponent;
use Jascha030\WP\Settings\Provider\SettingsPageProvider;
use Jascha030\WP\Subscriptions\Exception\InvalidArgumentException;
use Jascha030\WP\Subscriptions\Shared\Container\WordpressSubscriptionContainer;

use function Jascha030\WP\Plugin\Core\pluginDir;

class SettingsPageConfig extends PluginComponent
{
    protected $name = 'wp-settings-page';

    protected $config = 'settings-pages.php';

    protected $pages = [];

    /**
     * @throws \Jascha030\WP\Subscriptions\Exception\InvalidArgumentException
     *
     * @todo: move partially to PluginComponent
     */
    public function config(): void
    {
        $this->pages = pluginDir() . '/bootstrap/' . $this->config;

        if (! is_array($this->pages)) {
            throw new InvalidArgumentException('Error: faulty config for' . get_class($this));
        }
    }

    public function run(): void
    {
        foreach ($this->pages as $page) {
            WordpressSubscriptionContainer::getInstance()->register(
                SettingsPageProvider::class,
                new SettingsPageProvider(...$page)
            );
        }
    }

    protected function addSettingsPages(array $pages): void
    {
        $this->pages = $pages;
    }
}