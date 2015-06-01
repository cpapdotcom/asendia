<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/util.php';
require_once __DIR__.'/fixtures.php';
require_once __DIR__.'/DummyAsendiaWsdlClient.php';

function it($m,$p){echo"\033[3",$p?'2m✔︎':'1m✘'.register_shutdown_function(function(){die(1);}),"\033[0m It $m\n";}
