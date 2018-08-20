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

## Text content

String like object can be passed like other nodes using a node's constructor or ```append()```method.

```php
$node = (new \Summoning\Node("strong"))->append("expects");
$body->p("NO-body ")->append($node)->append(" the Spanish Inquisition!");
```

```html
<p>NO-body <strong>expects</strong> the Spanish Inquisition!</p>
```

## Doctype declaration

A ```<!DOCTYPE html>``` declaration is automatically prepended when rendering a root node of type ```<html>```.

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
