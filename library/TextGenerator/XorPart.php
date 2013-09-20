<?php

namespace TextGenerator;

class XorPart extends Part
{
    /**
     * Массив шаблонов для генерации
     * @var array
     */
    protected $template;

    /**
     * Количество вариантов шаблонов для генерации
     * @var int
     */
    protected $templateCount = 0;

    public function __construct($template, array $options = array())
    {
        $template = $this->parseTemplate($template);

        $this->template         = explode('|', $template['template']);
        $this->replacementArray = $template['replacement_array'];
        $this->templateCount    = count($this->template);
        $this->lastTemplateKey  = $this->templateCount - 1;
    }

    /**
     * Смещает текущий ключ массива
     */
    public function goNext()
    {
        $this->currentTemplateKey++;
        if (!isset($this->template[$this->currentTemplateKey])) {
            $this->currentTemplateKey = 0;
        }
    }

    /**
     * Returns current template value
     * @return string
     */
    public function getCurrentTemplate()
    {
        $templateArray = $this->template;
        $templateKey   = $this->currentTemplateKey;

        return $templateArray[$templateKey];
    }

    protected function getRandomTemplate()
    {
        $templateArray = $this->template;
        $templateKey   = mt_rand(0, $this->templateCount - 1);
        return $templateArray[$templateKey];
    }

    public function getCount()
    {
        return count($this->template) * $this->getReplacementCount();
    }
}