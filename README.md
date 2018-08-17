Summoning
=========

Yet another HTML code generator.

## Example

```php
$doc = new \Summoning\Node("html");
$doc->lang("en");

$head = $doc->head();
$head->title("Summoning");
$head->link()->rel("stylesheet")->href("https://www.w3schools.com/w3css/4/w3.css");

$body = $doc->body();
$body->p("Hello")->id("message")->class("w3-text-red", "w3-margin")->append(" world!");

$list = $body->ul()->class("w3-ul w3-margin");
$list->li("foo");
$list->li("bar")->class("w3-text-blue");
$list->li("baz");

echo $doc;
```

This example returns following HTML5 code:

```html
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Summoning</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  </head>
  <body>
    <p id="message" class="w3-text-red w3-margin">Hello world!</p>
    <ul class="w3-ul w3-margin">
      <li>foo</li>
      <li class="w3-text-blue">bar</li>
      <li>baz</li>
    </ul>
  </body>
</html>
```

License
=======

GNU General Public License Version 3
