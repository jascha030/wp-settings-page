<?php

namespace Jascha030\WPSettings;

class Setting
{
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

    public function __construct($page, string $title, int $type = HtmlField::TEXT, array $options = null)
    {
        $this->page  = $page;
        $this->title = $title;
        $this->type    = $type;
        $this->options = $options;
    }

    public function renderField()
    {
        switch ($this->type) {
            case HtmlField::RADIO || HtmlField::CHECKBOX:
                echo $this->renderLoopableField();
                break;

            case HtmlField::SELECT:
                echo $this->renderSelectField();
                break;

            case HtmlField::TEXTAREA:
                echo $this->renderTextarea();
                break;

            default:

                break;
        }
    }

    private function renderLoopableField(): string
    {
        $html = "";

        foreach ($this->options as $key => $value) {
            $id   = "{$this->slug}-{$key}";
            $name = "{$this->slug}[{$key}]";

            $checked = checked(1, $name, false);

            $html .= sprintf('<input type="%3$s" id="%1$s" name="%4$s" value="1" %5$s /><label for="%1$s">%2$s</label> <br />', $id, $value, HtmlField::getInputType($this->type), $name, $checked);
        }

        return $html;
    }

    private function renderSelectField(): string
    {
        $optionsHtml = "";

        foreach ($this->options as $key => $value) {
            $selected = selected(get_option($this->slug), $key);

            $optionsHtml .= sprintf("<option value='%s' %s>%s</option>", $key, $selected, $value);
        }

        return sprintf('<select id="%1$s" name="%1$s">%2$s</select>', $this->slug, $optionsHtml);
    }

    private function renderTextArea()
    {
        echo sprintf('<textarea id="%1$s" name="%1$s">%2$s</textarea>', $this->slug, $sanitized ?? null);
    }

    private function renderInputField()
    {
        echo sprintf('<input type="%1$s" id="%2$s" name="%2$s" value="%3$s" />', HtmlField::getInputType($this->type),
            $this->slug, $sanitized ?? "");
    }
}
