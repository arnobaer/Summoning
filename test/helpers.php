<?php namespace Summoning;

// Testing helper functions

require_once(__DIR__ . "/../src/Node.php");

// string matching

assert(true === str_startswith('lorem ipsum', 'l'));
assert(true === str_startswith('lorem ipsum', 'lor'));
assert(true === str_startswith('lorem ipsum', 'lorem ipsum'));
assert(false === str_startswith('lorem ipsum', 'ip'));
assert(false === str_startswith('lorem ipsum', 'orem ipsum'));

assert(true === str_startswith('data-valid', 'data-'));
assert(true === str_startswith('onclick', 'on'));
assert(false === str_startswith('class', 'on'));
