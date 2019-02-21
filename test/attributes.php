<?php namespace Summoning;

// Testing helper functions

require_once(__DIR__ . "/../src/Node.php");

// attributes

assert(Node::create('input')->accept('image/*') == '<input accept="image/*" />');

// boolean attributes

assert(Node::create('script')->async() == '<script async></script>');
assert(Node::create('button')->autofocus() == '<button autofocus></button>');
assert(Node::create('audio')->autoplay(), '<audio autoplay></audio>');
assert(Node::create('input')->checked(), '<input checked></input>');
assert(Node::create('audio')->controls(), '<audio controls></audio>');
assert(Node::create('track')->default(), '<track default></track>');
assert(Node::create('script')->defer(), '<script defer></script>');
assert(Node::create('button')->disabled(), '<button disabled></button>');
assert(Node::create('a')->download(), '<a download></a>');
assert(Node::create('div')->hidden(), '<div hidden></div>');
assert(Node::create('img')->ismap(), '<img ismap></img>');
