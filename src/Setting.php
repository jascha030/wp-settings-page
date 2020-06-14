<?php

namespace Jascha030\WP\Settings;

/**
 * Class Setting
 *
 * @package Jascha030\WPSettings
 *
 * @todo: Add check on MIME
 */
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
        add_settings_field(
            $this->slug,
            $this->title,
            [$this, 'renderField'],
            $this->page->getSlug(),
            $this->page->getSectionSlug()
        );

        if ($this->type === HtmlField::FILE) {
            $callback = [$this, 'uploadFile'];
        }
        $args = ['type' => HtmlField::getInputType($this->type), 'sanitize_callback' => $callback ?? null];

        register_setting($this->page->getSectionSlug(), $this->slug, $args);
    }

    public function renderField(): void
    {
        switch ($this->type) {
            case HtmlField::CHECKBOX:
                echo $this->renderCheckbox();
                break;

            case HtmlField::RADIO:
                echo $this->renderRadio();
                break;

            case HtmlField::SELECT:
                echo $this->renderSelectField();
                break;

            case HtmlField::TEXTAREA:
                echo $this->renderTextarea();
                break;

            case HtmlField::FILE:
                echo $this->renderFileField();
                break;

            default:
                echo $this->renderInputField();
                break;
        }
    }

    /**
     * @param $option
     *
     * @return mixed
     */
    public function uploadFile($option)
    {
        if (! function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        if (! empty($_FILES[$this->slug]["tmp_name"])) {
            $uploadedFile = $_FILES[$this->slug];
            $moveFile     = wp_handle_upload($uploadedFile, ['test_form' => false]);

            if ($moveFile && ! isset($moveFile['error'])) {
                return $moveFile['file'];
            } else {
                echo $moveFile['error'];
            }
        }

        return $this->getSetting();
    }

    private function renderCheckbox(): string
    {
        $checked = checked("1", $this->getSetting(), false);

        return sprintf(
            '<input type="%3$s" id="%1$s" name="%1$s" value="1" %4$s /><label for="%1$s">%2$s</label> <br /> <br />',
            $this->slug,
            $this->title,
            HtmlField::getInputType($this->type),
            $checked
        );
    }

    private function renderRadio(): string
    {
        $html = "<br />";

        foreach ($this->options as $key => $value) {
            $id      = "{$this->slug}-{$key}";
            $checked = checked($key, $this->getSetting(), false);

            $html .= sprintf(
                '<input type="%3$s" id="%1$s" name="%4$s" value="%6$s" %5$s /><label for="%1$s">%2$s</label> <br /> <br />',
                $id,
                $value,
                HtmlField::getInputType($this->type),
                $this->slug,
                $checked,
                $key
            );
        }

        return $html;
    }

    private function renderSelectField(): string
    {
        $optionsHtml = "";

        foreach ($this->options as $key => $value) {
            $optionsHtml .= sprintf(
                "<option value='%s' %s >%s</option>",
                $key,
                selected($this->getSetting(), $key, false),
                $value
            );
        }

        return sprintf('<select id="%1$s" name="%1$s">%2$s</select>', $this->slug, $optionsHtml);
    }

    private function renderTextArea()
    {
        $sanitized = $this->getSetting(true);

        return sprintf('<textarea id="%1$s" name="%1$s">%2$s</textarea>', $this->slug, $sanitized ?? null);
    }

    private function renderInputField()
    {
        $sanitized = $this->getSetting(true);

        return sprintf(
            '<input type="%1$s" id="%2$s" name="%2$s" value="%3$s" />',
            HtmlField::getInputType($this->type),
            $this->slug,
            $sanitized ?? ""
        );
    }

    private function renderFileField()
    {
        $accept = (isset($this->options['accept'])) ? "accept='{$this->options['accept']}'" : '';

        return sprintf(
            '<input type="%1$s" id="%2$s" name="%2$s" %3$s value="%4$s" /> <br /> <p>%5$s</p>',
            HtmlField::getInputType($this->type),
            $this->slug,
            $accept,
            $this->getSetting(),
            $this->getSetting(true)
        );
    }

    /**
     * @param bool $sanitized
     *
     * @return mixed
     */
    private function getSetting($sanitized = false)
    {
        $option = get_option($this->slug);

        return ($sanitized) ? esc_attr($option) : $option;
    }
}
