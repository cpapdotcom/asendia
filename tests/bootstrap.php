<?php

require_once __DIR__.'/../autoload.php';
require_once __DIR__.'/util.php';
require_once __DIR__.'/fixtures.php';
require_once __DIR__.'/DummyAsendiaWsdlClient.php';

function it($m,$p){echo"\033[3",$p?'2m✔︎':'1m✘'.register_shutdown_function(function(){die(1);}),"\033[0m It $m\n";}

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);

assert_options(ASSERT_CALLBACK, function ($file, $line, $code, $reason = null) {
    printf("\033[31m✘\033[0m %s in %s:%s\n", $reason?:'unexpected result', $file, $line);
});
