<?php

namespace TextGenerator;

class OrPart extends XorPart
{
    /**
     * Word delimiter
     *
     * @var string
     */
    private $delimiter = ' ';

    /**
     * Последовательность, в которой будут следовать фразы шаблона при генерации
     * @var array
     */
    private $currentTemplateKeySequence;

    /**
     * Массив последовательностей слов, из которых будут формироваться фразы
     * @var array
     */
    private $sequenceArray = array();

    private $similarTemplateCount = 0;

    public function __construct($template, array $options = array())
    {
        $template        = preg_replace_callback('#^\+([^\+]+)\+#', function ($match) use (&$delimiter) {
            $delimiter = $match[1];
            return '';
        }, $template);

        if (isset($delimiter)) {
            $this->delimiter = $delimiter;
        }

        parent::__construct($template, $options);

        $firstSequence                    = range(0, count($this->template) - 1);
        $this->sequenceArray[0]           = $firstSequence;
        $this->currentTemplateKeySequence = $firstSequence;
    }

    /**
     * Получает последовательность из другой последовательности, например, из 012 получается 021, далее 102 и т.д
     * @param array $currentSequence - последовательность, на основе которой будет строится следующая
     *
     * @return mixed
     */
    public function getNextSequence($currentSequence)
    {
        $sequenceLength = count($currentSequence);

        //Ищем максимальный k-индекс, для которого a[k] < a[k - 1]
        $k = null;
        for ($i = 0; $i < $sequenceLength; $i++) {
            if (isset($currentSequence[$i + 1]) && $currentSequence[$i] < $currentSequence[$i + 1]) {
                $k = $i;
            }
        }
        //Если k невозможно определить, то это конец последовательности, начинаем сначала
        if (is_null($k)) {
            //На колу мочало, начинай с начала!
            return reset($this->sequenceArray);
        }
        //Ищем максимальный l-индекс, для которого a[k] < a[l]
        $l = null;
        for ($i = 0; $i < $sequenceLength; $i++) {
            if ($currentSequence[$k] < $currentSequence[$i]) {
                $l = $i;
            }
        }
        //Если k невозможно определить (что весьма странно, k определили же), то начинаем сначала
        if (is_null($l)) {
            //На колу мочало, начинай с начала!
            return reset($this->sequenceArray);
        }
        $nextSequence     = $currentSequence;
        //Меняем местами a[k] и a[l]
        $nextSequence[$k] = $currentSequence[$l];
        $nextSequence[$l] = $currentSequence[$k];

        $k2 = $k + 1;
        //Разворачиваем массив начиная с k2 = k + 1
        if ($k2 < ($sequenceLength - 1)) {
            for ($i = 0, $count = floor(($sequenceLength - $k2) / 2); $i < $count; $i++) {
                $key1                = $k2 + $i;
                $key2                = $sequenceLength - 1 - $i;
                $val1                = $nextSequence[$key1];
                $nextSequence[$key1] = $nextSequence[$key2];
                $nextSequence[$key2] = $val1;
            }
        }

        return $nextSequence;
    }

    /**
     * Returns count of variants
     *
     * @return int
     */
    public function getCount()
    {
        $repeats = $this->getReplacementCount();
        return $this->factorial(count(reset($this->sequenceArray))) * $repeats;
    }

    /**
     * Factorial
     *
     * @param $x
     * @return int
     */
    private  function factorial($x)
    {
        if ($x === 0) {
            return 1;
        } else {
            return $x*$this->factorial($x-1);
        }
    }

    /**
     * Смещает текущую последрвательность ключей массива шаблона на следующую
     */
    public function next()
    {
        //print_r($this->sequenceArray);
        $key = implode('', $this->currentTemplateKeySequence);
        if (!isset($this->sequenceArray[$key]) || !($nextSequence = $this->sequenceArray[$key])) {
            $nextSequence              = $this->getNextSequence($this->currentTemplateKeySequence);
            $this->sequenceArray[$key] = $nextSequence;
        }
        $this->currentTemplateKeySequence = $nextSequence;
    }

    public function isCurrentTemplateIsLast()
    {
        return 0;
    }

    /**
     * Get template (random)
     *
     * @return string
     */
    protected function getRandomTemplate()
    {
        $randomSequence = range(0, count($this->template) - 1);
        shuffle($randomSequence);
        $templateKeySequence = $this->getNextSequence($randomSequence);

        $templateArray = $this->template;
        for ($i = 0, $count = count($templateKeySequence); $i < $count; $i++) {
            $templateKey             = $templateKeySequence[$i];
            $templateKeySequence[$i] = $templateArray[$templateKey];
        }

        return implode($this->delimiter, $templateKeySequence);

    }

    public function getCurrentTemplate()
    {
        $templateKeySequence = $this->currentTemplateKeySequence;

        $templateArray = $this->template;
        for ($i = 0, $count = count($templateKeySequence); $i < $count; $i++) {
            $templateKey             = $templateKeySequence[$i];
            $templateKeySequence[$i] = $templateArray[$templateKey];
        }

        return implode($this->delimiter, $templateKeySequence);
    }
}