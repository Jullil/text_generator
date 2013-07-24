<?php

namespace TextGeneratorTest;

require_once __DIR__ . '/TestCase.php';

use TextGenerator\OrPart;
use TextGenerator\TextGenerator;

class OrPartTest extends TestCase
{
    /**
     * @group testGenerateAllPossibleVariants
     */
    public function testGenerateAllPossibleVariants()
    {
        $str = "1|2|[3|4]";
        $part = new OrPart($str);

        $this->assertEquals('1 2 3 4', $part->generate());
        $this->assertEquals('1 2 4 3', $part->generate());
        $this->assertEquals('1 3 4 2', $part->generate());
        $this->assertEquals('1 4 3 2', $part->generate());
    }

    /**
     * @group a
     */
    public function testGetNextSequence()
    {
        $str = "1|2|[3|4]";
        $part = new OrPart($str);

        $firstSequence = range(0, 10);

        $array = array();
        $t = microtime(true);
        for ($i = 0; $i < 2000000; $i++) {
            $sequence = $part->getNextSequence($firstSequence);
            $key = implode('', $sequence);
            if (!isset($array[$key])) {
                $array[$key] = $sequence;
                $firstSequence = $sequence;
            } else {
                echo "YO!\n";
                break;
            }
            //print_r($part->getNextSequence($firstSequence));
            //echo "\n";
        }
        echo count($array) . "\n";
        print_r(microtime(true) - $t);
        die;
    }
    
    public function testGetRandomTemplate()
    {
        $str = "1|2|3|4|5|6";
        $part = new OrPart($str);
        $this->assertNotEquals($part->generate(true), $part->generate(true));

        $part = new OrPart($str);
        $this->assertEquals('1 2 3 4 5 6', $part->generate());
        $this->assertEquals('1 2 3 4 6 5', $part->generate());
    }

    public function testGetCount()
    {
        $str = "1|2";
        $part = new OrPart($str);
        $this->assertEquals(2, $part->getCount());

        $str = "1|2|3";
        $part = new OrPart($str);
        $this->assertEquals(6, $part->getCount());

        $str = "1|2|3|4";
        $part = new OrPart($str);
        $this->assertEquals(24, $part->getCount());

        $str = "+ and +1|2|3|4|5";
        $part = new OrPart($str);
        $this->assertEquals(120, $part->getCount());
        $part->next();
        $result = array();
        $i = 0;
        while ($item = $part->getCurrentTemplate()) {
            $result[$item] = true;
            $part->next();
            if ($i++ >= 240) {
                break;
            }
        }

        $this->assertEquals(120, count($result));

        $str = "+ and +1|2|3|4|{5|6}";
        $part = new OrPart($str);

        $part->getCount(true);
        $this->assertEquals(240, $part->getCount());
        $part->next();
        $result = array();
        $i = 0;
        while ($item = $part->getCurrentTemplate()) {
            $result[$item] = true;
            $part->next();
            if ($i++ >= 720) {
                break;
            }
        }
        // wrong :(
        $this->assertEquals(120, count($result));
    }
}