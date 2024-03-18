<?php

putenv( 'TESTS_PATH=' . __DIR__ );
putenv( 'LIBRARY_PATH=' . dirname( __DIR__ ) );

define( 'PRIMCAT_PATH', dirname( dirname( __DIR__ ) ) . '/' );

require_once __DIR__ . '/../../autoload.php';
WP_Mock::bootstrap();

// Include test case abstract.
include_once 'Unit/AbstractUnitTestcase.php';
