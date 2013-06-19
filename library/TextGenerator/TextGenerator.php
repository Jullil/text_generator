<?php

namespace TextGenerator;

class TextGenerator
{
    public static function factory($template, array $options = array())
    {
        $template = trim($template);
        if (mb_strpos($template, '{', null, 'utf8') === 0) {
            $template = trim($template, '{}');
            return new XorPart($template, $options);
        }
        if (mb_strpos($template, '[', null, 'utf8') === 0) {
            $template = trim($template, '[] ');
            return new OrPart($template, $options);
        }
        //print_r($template . "\n");
        return new Part($template, $options);
    }
}