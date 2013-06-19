<?php

namespace TextGeneratorTest;

require_once __DIR__ . '/TestCase.php';

use TextGenerator\TextGenerator;

class TextGeneratorTest extends TestCase
{
    public function testFactory()
    {
        $str = " {hi} ";
        $this->assertInstanceOf('TextGenerator\\XorPart', TextGenerator::factory($str));

        $str = " [hi] ";
        $this->assertInstanceOf('TextGenerator\\OrPart', TextGenerator::factory($str));

        $str = " hi ";
        $this->assertInstanceOf('TextGenerator\\Part', TextGenerator::factory($str));
    }
}