<?php

/*  Summoning - yet another HTML5 code generator
 *  Copyright (C) 2013, 2018  Bernhard Arnold <bernhard.arnold@burgried.at>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Summoning;

if (!defined('SUMMONING_DEBUG')) {
  define('SUMMONING_DEBUG', true);
}

class Node {
  protected $_tag;
  protected $_attrs;
  protected $_parent = false;
  protected $_children;
  public const doctype = 'html';
  public const valid_tags = array(
    'a', 'abbr', 'address', 'area', 'article', 'aside', 'audio',
    'b', 'base', 'bdi', 'bdo', 'blockquote', 'body', 'br', 'button',
    'canvas', 'caption', 'cite', 'code', 'col', 'colgroup',
    'data', 'datalist', 'dd', 'del', 'details', 'dfn', 'dialog', 'div', 'dl', 'dt',
    'em', 'embded', 'fieldset', 'figcaption', 'figure', 'footer', 'form',
    'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'header', 'hr', 'html',
    'i', 'iframe', 'img', 'input', 'ins',
    'kbd',
    'label', 'legend', 'li', 'link',
    'main', 'map', 'mark', 'meta', 'meter',
    'nav', 'noscript',
    'object', 'ol', 'optgroup', 'option', 'output',
    'p', 'param', 'picture', 'pre', 'progress',
    'q',
    'rp', 'rt', 'ruby',
    's', 'samp', 'script', 'section', 'select', 'small', 'source', 'span', 'strong', 'style', 'sub', 'summary', 'sup', 'svg',
    'table', 'tbody', 'td', 'template', 'textarea', 'tfoot', 'th', 'thead', 'time', 'title', 'tr', 'track',
    'u', 'ul',
    'var', 'video',
    'wbr',
  );
  public const valid_attrs = array(
    'accept' => array('input'),
    'accept-charset' => array('form'),
    'accesskey' => array(),
    'action' => array('form'),
    'alt' => array('area', 'img', 'input'),
    'async' => array('script'),
    'autocomplete' => array('form', 'input'),
    'autofocus' => array('button', 'input', 'select', 'textarea'),
    'autoplay' => array('audio', 'video'),
    'charset' => array('meta', 'script'),
    'checked' => array('input'),
    'cite' => array('blockquote', 'del', 'ins', 'q'),
    'class' => array(),
    'cols' => array('textarea'),
    'colspan' => array('td', 'th'),
    'content' => array('meta'),
    'contenteditable' => array(),
    'controls' => array('audio', 'video'),
    'coords' => array('area'),
    'data' => array('object'),
    'data-*' => array(), // TODO: implement variable attribute keys
    'datetime' => array('del', 'ins', 'time'),
    'default' => array('track'),
    'defer' => array('script'),
    'dir' => array(),
    'dirname' => array('input', 'textarea'),
    'disabled' => array('download'),
    'href' => array('a', 'area', 'base', 'link'),
    'http-equiv' => array('meta'),
    'id' => array(),
    // TODO: add missing HTML5 attributes
    'label' => array('track', 'option', 'optgroup'),
    'lang' => array(),
    'rel' => array('a', 'area', 'link'),
    'src' => array('audio', 'embed', 'iframe', 'img', 'input', 'script', 'source', 'track', 'video'),
    'style' => array(),
    'tabindex' => array(),
    'title' => array(),
    'wrap' => array('textarea'),
  );
  public function __construct($tag, $children=array()) {
    if (SUMMONING_DEBUG && !$this->_is_valid_tag($tag)) {
      throw new \Exception("node error: invalid tag name <{$tag}>");
    }
    $this->_tag = $tag;
    $this->_children = $children;
    $this->_attrs = array();
  }
  public function __call($method, $args) {
    if ($this->_is_valid_tag($method)) {
      $node = new Node($method, $args);
      $this->append($node);
      return $node;
    }
    if ($this->_is_valid_attr($method)) {
      $this->_attrs[$method] = join(' ', $args);
      return $this;
    }
    if (SUMMONING_DEBUG) {
      $message = "node error: invalid callback <method={$method}, args=[" . join(', ', $args) ."]>";
      throw new \Exception($message);
    }
  }
  public function append($node) {
    if ($node instanceof Node) {
      $node->_parent = $this;
    }
    $this->_children[] = $node;
    return $this;
  }
  public function toHtml() {
    return $this->_render_tag();
  }
  public function __toString() {
    return $this->toHtml();
  }
  protected function _is_valid_tag($tag) {
    return in_array($tag, Node::valid_tags);
  }
  protected function _is_valid_attr($attr) {
    return array_key_exists($attr, Node::valid_attrs)
      && $this->_is_valid_attr_tag($attr);
  }
  protected function _is_valid_attr_tag($attr) {
    return count(Node::valid_attrs[$attr])
      ? in_array($this->_tag, Node::valid_attrs[$attr]) : true;
  }
  protected function _render_tag() {
    $tag = $this->_tag;
    $attrs = $this->_render_attrs();
    $children = $this->_render_children();
    $data = array();
    if (false === $this->_parent) {
      $data[] = join('', array('<!DOCTYPE ', Node::doctype, '>', PHP_EOL));
    }
    $data[] = "<{$tag}{$attrs}>$children</{$tag}>";
    return join('', $data);
  }
  protected function _render_attrs() {
    $pairs = array();
    $attrs = $this->_attrs;
    if (count($attrs)) {
      $pairs[] = ''; // to prepend a whitespace
    }
    foreach ($attrs as $key => $value) {
      $pairs[] = "{$key}=\"{$value}\"";
    }
    return join(' ', $pairs);
  }
  protected function _render_children() {
    return join('', $this->_children);
  }
}
