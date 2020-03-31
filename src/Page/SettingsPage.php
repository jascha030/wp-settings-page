<?php

namespace Jascha030\WPSettings\Page;

use Jascha030\WPSettings\Setting;

class SettingsPage
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string|null
     */
    private $capability;

    /**
     * @var array
     */
    private $settings = [];

    /**
     * @var string|null
     */
    private $section;

    /**
     * @var string
     */
    private $sectionSlug;

    public function __construct(
        string $title,
        string $prefix = null,
        string $section = null,
        array $settings = null,
        string $capability = "manage_options",
        bool $init = true
    ) {
        $this->title      = $title;
        $this->prefix     = (! $prefix) ? "" : "{$prefix}_";
        $this->section    = $section;
        $this->capability = $capability;

        $this->sectionSlug = (! $section) ? "default" : $this->prefix . sanitize_title($section);
        $this->slug        = $this->prefix . sanitize_title($title);

        if ($settings) {
            array_walk($settings, [$this, 'sanitizeAndAddSetting']);
        }

        if ($init) {
            $this->hook();
        }
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getSectionSlug(): string
    {
        return $this->sectionSlug;
    }

    /**
     * Registers page, hooked to admin_menu
     */
    public function registerPage()
    {
        add_menu_page($this->title, $this->title, $this->capability, $this->slug, [$this, 'render'], "dashicons-store");
    }

    /**
     * Registers page settings and respective fields, hooked to admin_init
     *
     * @throws Exception
     */
    public function registerSettings()
    {
        if ($this->section) {
            add_settings_section($this->sectionSlug, $this->section, null, $this->slug);
        }

        foreach ($this->settings as $setting) {
            if ($setting instanceof Setting) {
                $setting->register();
            } else {
                $className = self::class;
                throw new Exception("Setting is not of class \"$className\"");
            }
        }
    }

    /**
     * Renders page html, called in registerPage method
     */
    public function render()
    {
        echo "<div class='wrap'>";
        echo "<h1>{$this->title}</h1>";
        echo "<form method='post' action='options.php'>";

        settings_fields($this->sectionSlug);

        do_settings_sections($this->slug);

        submit_button();

        echo "</form>";
        echo "</div>";
    }

    /**
     * Add a setting field
     *
     * @param $title
     * @param $type
     * @param array|null $options
     */
    public function addSetting($title, $type, array $options = null)
    {
        $this->settings[] = new Setting($this, $title, $type, $options);
    }

    /**
     * Hooks plugin functions
     */
    private function hook()
    {
        add_action('admin_menu', [$this, 'registerPage']);

        add_action('admin_init', [$this, 'registerSettings']);
    }

    /**
     * Checks provided settings validity and add it
     *
     * @param array $args
     *
     * @throws Exception
     */
    private function sanitizeAndAddSetting(array $args)
    {
        $settingArray = [];

        foreach (["title" => "string", "type" => "integer"] as $key => $type) {
            if (! array_key_exists($key, $args)) {
                throw new Exception("Missing argument \"{$key}\" for provided setting.");
            }

            if (gettype($key) !== $type) {
                throw new Exception("Invalid data provided for " . WordpressSetting::class . ", \"{$key}\" must of type: \"{$type}\".");
            }

            $settingArray[$key] = $args[$key];
        }

        $settingArray["options"] = (array_key_exists("options",
                $args) && is_array($args["options"])) ? $args["options"] : null;

        $this->addSetting(...$settingArray);
    }
}