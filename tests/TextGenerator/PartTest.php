<?php

namespace TextGeneratorTest;

require_once __DIR__ . '/TestCase.php';

use TextGenerator\Part;
use TextGenerator\TextGenerator;

class PartTest extends TestCase
{
    public function testSetOptions()
    {
        $options = array(
            Part::OPTION_FILTER_EMPTY_VALUES => false,
            Part::OPTION_REMOVE_DUPLICATES   => false,
            Part::OPTION_STRIP_WHITE_SPACE   => false
        );

        $str = " hi ";
        $part = TextGenerator::factory($str, $options);
        $this->assertInstanceOf('TextGenerator\\Part', $part);

        $this->assertEquals($options, $part->getOptions());

        $options = array(
            Part::OPTION_FILTER_EMPTY_VALUES => true,
            Part::OPTION_REMOVE_DUPLICATES   => true,
            Part::OPTION_STRIP_WHITE_SPACE   => true
        );
        foreach ($options as $name => $value) {
            $part->setOption($name, $value);
            $this->assertEquals($value, $part->getOption($name));
        }

        $this->assertEquals($options, $part->getOption());

        $this->assertEquals('unknown', $part->getOption('unknown', 'unknown'));
    }
}