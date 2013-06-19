<?php

define('PROJECT_PATH',  dirname(__FILE__) . '/../');
define('LIBRARY_PATH',  dirname(__FILE__) . '/../library');
define('FIXTURES_PATH', realpath(__DIR__ . '/_fixtures'));

set_include_path(realpath(PROJECT_PATH . '/library')
				 . PATH_SEPARATOR . get_include_path());

require LIBRARY_PATH . '/TextGenerator/Part.php';
require LIBRARY_PATH . '/TextGenerator/XorPart.php';
require LIBRARY_PATH . '/TextGenerator/OrPart.php';
require LIBRARY_PATH . '/TextGenerator/TextGenerator.php';
