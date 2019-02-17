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
$body->p("Hello")->id("message")->class("w3-text-red", " w3-margin")->append(" world!");

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
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css" />
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

Child elements without a parent can be also created using method `create()`.

```php
$div = $html->create("div");  // independent <div> node
```

As method `create()` is static it can be also called directly without requiring
an object.

```php
$div = \Summoning\Node::create("div");  // independent <div> node
```

## Appending and prepending

Appending or prepending overwrites a node's parent attribute. Using
`$parent->append($node)` will assign `$parent` as parent of `$node` regardless
if `$node` got a parent assigned before. Same applies for prepending nodes by
using method `prepend()`.

## Name collisions

Attributes of the same name as tags can be distinguished by prepending an
underline character.

```php
$head->title("Summoning")->_title("This is the page's title.");
```

In the above example calling `title()` creates a tag, while `_title()` appends
an attribute of the same name.

```html
<title title="This is the page's title.">Summoning</title>
```

Following attributes collide with HTML5 tag names and need to be escaped.

| attribute    | example             |     | tag      | example               |     
| ------------ | ------------------- | --- | -------- | --------------------- |
| \_cite       | `cite="url"`        |     | cite     | `<cite>text</cite>`   |
| \_data       | `data="url"`        |     | data     | `<data>foo</data>`    |
| \_form       | `form="id"`         |     | form     | `<form>...</form>`    |
| \_label      | `label="text"`      |     | label    | `<label>text</label>` |
| \_span       | `span="2"`          |     | span     | `<span>text</span>`   |
| \_style      | `style="color:red"` |     | style    | `<style>.p{}</style>` |
| \_title      | `title="text"`      |     | title    | `<title>text</title>` |

Note that any valid attribute can be prefixed with an underline character
regardless if it is shadowed by a tag name or not.

## Attributes with hyphens

Attribute names containing a hyphen can be escaped using an underline character.

```php
$meta->http_equiv("refresh")->content("30");
```

```html
<meta http-equiv="refresh" content="30">
```

## Text content

String like object can be passed like other nodes by calling a tag method or
methods `append()` and `prepend()`.

```php
$node = $body->create("strong")->append("expects");
$body->p()->append($node)->append(" the Spanish Inquisition!")->prepend("NO-body ");
```

```html
<p>NO-body <strong>expects</strong> the Spanish Inquisition!</p>
```

Tag methods as well as methods `append()` and `prepend()` accept multiple
arguments that are joined (without spaces).

```php
$context = "Spanish Inquisition";
$body->p("Who expects the", " ", $context)->append(" before ", 8, " o'clock?");
```

```html
<p>Who expects the Spanish Inquisition before 8 o'clock?</p>
```

It is also possible to append predefined HTML codes as strings.

```php
$head->append('<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css" />');
```

Multiple strings and other elements can be passed to any tag method or by
calling method `append()`.

## Doctype declaration

A `<!DOCTYPE html>` declaration is automatically prepended when rendering a
root node of type `<html>`.

## Templates

Reusable templates can be registered using PHP closures. The first attribute
`$parent` is mandatory and provides a reference to the node executing the
template closure.

```php
$body->register("link", function($parent, $url, $title) {
  $a = $parent->create("a");
  $a->href($url)->_title($title)->append($title);
  return $a;
});
$body->register("list", function($parent, $items) {
  $ul = $parent->create("ul");
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
    <li><a href="https://www.w3schools.com/html/default.asp" title="HTML5 Tutorial">HTML5 Tutorial</a></li>
    <li><a href="https://www.w3schools.com/css/default.asp" title="CSS Tutorial">CSS Tutorial</a></li>
    <li><a href="https://www.w3schools.com/php/default.asp" title="PHP 5 Tutorial">PHP 5 Tutorial</a></li>
  </ul>
</body>
```

### Notes

 - If the closure does **not** return a `Node` type object the call returns a reference to its parent node.
 - If the closure does return a `Node` type object **without** a parent it will assign the calling node as parent.


```php
$body->register("spam", function($parent) {
  $parent->div();
});
$body->tpl_spam(); // '<div></div>'
```

## Installation

### Using composer

Append the repository and requirement to your project `composer.json`

```json
{
    "repositories": [
        {
            "url": "https://github.com/arnobaer/summoning.git",
            "type": "git"
        }
    ],
    "require": {
        "arnobaer/summoning": "~0.4"
    }
}
```

Install using ```composer``` (providing PSR-4 autoloading).

```bash
composer update
```

### Using git repo

```php
include 'path/to/Summoning/src/Node.php';
```

License
=======

Summoning is licensed under the GNU General Public License Version 3.
