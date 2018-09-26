Summoning
=========

Yet another HTML5 code generator.

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

This example returns the following valid HTML5 code:

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
## Creating nodes

A root node can be created like any PHP instance.

```php
$html = new \Summoning\Node("html");
```
Child elements withpout a parent can be also created using `create($tag)`.

```php
$div = $html->create("div");  // independend <div> node
```

## Name collisions

Attributes of the same name as tags can be distinguished by prepending an underline character.

```php
$head->title("Summoning")->_title("This is the page's title.");
```

In the above example calling ```title()```creates a tag, while ```_title()``` appends an 
attribute of the same name.

```html
<title title="This is the page's title.">Summoning</title>
```

Following attributes collide with HTML5 tag names and need to be escaped.

 * cite
 * data
 * form
 * label
 * span
 * style
 * title

## Attributes with hyphens

Attribute names containing a hyphen can be escaped using an underline character.

```php
$meta->http_equiv("refresh")->content("30");
```

```html
<meta http-equiv="refresh" content="30">
```

## Text content

String like object can be passed like other nodes using a node's constructor or ```append()```method.

```php
$node = $body->create("strong")->append("expects");
$body->p("NO-body ")->append($node)->append(" the Spanish Inquisition!");
```

```html
<p>NO-body <strong>expects</strong> the Spanish Inquisition!</p>
```

## Doctype declaration

A ```<!DOCTYPE html>``` declaration is automatically prepended when rendering a root node of type ```<html>```.

## Templates

Reusable templates can be registered using PHP closures.

```php
$body->register("link", function($url, $title) {
  $a = new \Summoning\Node("a");
  $a->href($url)->_title($title)->append($title);
  return $a;
});
$body->register("list", function($items) {
  $ul = new \Summoning\Node("ul");
  $ul->class("w3-ul");
  foreach ($items as $title => $url)
    $ul->li()->tpl_link($url, $title);
  return $ul;
});
$body->tpl_list(array(
  "HTML5 Tutorial" => "https://www.w3schools.com/html/default.asp",
  "CSS Tutorial" => "https://www.w3schools.com/css/default.asp",
  "PHP 5 Tutorial" => "https://www.w3schools.com/php/default.asp"
));
```

```html
<body>
  <ul class="w3-ul">
    <li><a href="https://www.w3schools.com/html/" title="HTML5 Tutorial">HTML5 Tutorial</a></li>
    <li><a href="https://www.w3schools.com/css/" title="CSS Tutorial">CSS Tutorial</a></li>
    <li><a href="https://www.w3schools.com/php/" title="PHP 5 Tutorial">PHP 5 Tutorial</a></li>
  </ul>
</body>
```

## Installation

### Using composer

Append the repositiory and requirement to your project ```composer.json```

```json
{
    "repositories": [
        {
            "url": "https://github.com/arnobaer/summoning.git",
            "type": "git"
        }
    ],
    "require": {
        "arnobaer/summoning": "~0.1"
    }
}
```

Install using ```composer``` (providing PSR-4 autoloading).

```bash
composer update
```

License
=======

GNU General Public License Version 3
