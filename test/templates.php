<?php

// Testing templates

require_once(__DIR__ . "/../src/Node.php");

$html = new \Summoning\Node('html');

$html->register("link", function($parent, $url, $title) {
  $a = $parent->create("a");
  $a->href($url)->_title($title)->append($title);
  return $a;
});

$html->register("list", function($parent, $items) {
  $ul = $parent->create("ul");
  $ul->class("w3-ul");
  foreach ($items as $title => $url)
    $ul->li()->tpl_link($url, $title);
  return $ul;
});

$html->body()->tpl_list(array(
  "HTML5 Tutorial" => "https://www.w3schools.com/html/default.asp",
  "CSS Tutorial" => "https://www.w3schools.com/css/default.asp",
  "PHP 5 Tutorial" => "https://www.w3schools.com/php/default.asp"
));

$reference = <<<EOS
<!DOCTYPE html>
<html><body><ul class="w3-ul"><li><a href="https://www.w3schools.com/html/default.asp" title="HTML5 Tutorial">HTML5 Tutorial</a></li><li><a href="https://www.w3schools.com/css/default.asp" title="CSS Tutorial">CSS Tutorial</a></li><li><a href="https://www.w3schools.com/php/default.asp" title="PHP 5 Tutorial">PHP 5 Tutorial</a></li></ul></body></html>
EOS;

assert($reference == $html->toHtml());
