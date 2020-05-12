<?php

spl_autoload_register(function ($name) {
    $d = (strpos(__FILE__, ".phar") === false ? __DIR__ : "phar://" . __FILE__ . "/src");
    if ($name == "querystring") require_once($d . "/querystring.php");
});

__HALT_COMPILER();
