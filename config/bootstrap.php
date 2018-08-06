<?php

define('PATTERN_EMPTY_OR_ANY', '{,*.}');

$configs = array_map(
    function ($file) {
        return require $file;
    },
    glob(
        __DIR__ . '/autoload/{'.PATTERN_EMPTY_OR_ANY.'global,'.PATTERN_EMPTY_OR_ANY.'local}.php',
        GLOB_BRACE
    )
);

return array_merge_recursive(...$configs);