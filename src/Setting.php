<?php

namespace Jascha030\WPSettings;

use Jascha030\WPSettings\Page\SettingsPage;

class Setting
{
    /**
     * @var SettingsPage
     */
    private $page;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var int
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    public function __construct(SettingsPage $page, string $title, int $type = HtmlField::TEXT, array $options = null)
    {
        $this->page    = $page;
        $this->title   = $title;
        $this->slug    = $page->getPrefix() . sanitize_title($title);
        $this->type    = $type;
        $this->options = $options;
    }

    public function register()
    {
        add_settings_field($this->slug, $this->title, [$this, 'renderField'], $this->page->getSlug(),
            $this->page->getSectionSlug());

        register_setting($this->page->getSectionSlug(), $this->slug, ['type' => HtmlField::getInputType($this->type)]);
    }

    public function renderField()
    {
        switch ($this->type) {
            case HtmlField::CHECKBOX:
            case HtmlField::RADIO:
                echo $this->renderLoopableField();
                break;

            case HtmlField::SELECT:
                echo $this->renderSelectField();
                break;

            case HtmlField::TEXTAREA:
                echo $this->renderTextarea();
                break;

            default:
                echo $this->renderInputField();
                break;
        }
    }

    private function renderLoopableField(): string
    {
        $html = "<br />";

        foreach ($this->options as $key => $value) {
            $id   = "{$this->slug}-{$key}";
            $name = "{$this->slug}[{$key}]";

            $checked = checked(1, $name, false);

            $html .= sprintf('<input type="%3$s" id="%1$s" name="%4$s" value="1" %5$s /><label for="%1$s">%2$s</label> <br /> <br />',
                $id, $value, HtmlField::getInputType($this->type), $name, $checked);
        }

        return $html;
    }

    private function renderSelectField(): string
    {
        $optionsHtml = "";

        foreach ($this->options as $key => $value) {
            $optionsHtml .= sprintf("<option value='%s' %s >%s</option>", $key,
                selected($this->getOption(), $key, false), $value);
        }

        return sprintf('<select id="%1$s" name="%1$s">%2$s</select>', $this->slug, $optionsHtml);
    }

    private function renderTextArea()
    {
        $sanitized = $this->getOption(true);

        return sprintf('<textarea id="%1$s" name="%1$s">%2$s</textarea>', $this->slug, $sanitized ?? null);
    }

    private function renderInputField()
    {
        $sanitized = $this->getOption(true);

        return sprintf('<input type="%1$s" id="%2$s" name="%2$s" value="%3$s" />', HtmlField::getInputType($this->type),
            $this->slug, $sanitized ?? "");
    }

    /**
     * @param bool $sanitized
     *
     * @return mixed
     */
    private function getOption($sanitized = false)
    {
        $option = get_option($this->slug);

        return ($sanitized) ? esc_attr($option) : $option;
    }
}
