<?php

/* Summoning - Yet another HTML5 code generator.
 * Copyright (C) 2013,2018  Bernhard Arnold <bernhard.arnold@burgried.at>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Summoning;

class Node {
  protected $_tag;
  protected $_attrs;
  protected $_parent = false;
  protected $_children;
  const DocType = 'html'; // HTML 5
  const Tags = array(
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
  const Attributes = array(
    'accept' => array('input'),
    'accept-charset' => array('form'),
    'action' => array('form'),
    'alt' => array('area', 'img', 'input'),
    'async' => array('script'),
    'autocomplete' => array('form', 'input'),
    'autofocus' => array('button', 'input', 'select', 'textarea'),
    'autoplay' => array('audio', 'video'),
    'charset' => array('meta', 'script'),
    'checked' => array('input'),
    'cite' => array('blockquote', 'del', 'ins', 'q'),
    'cols' => array('textarea'),
    'colspan' => array('td', 'th'),
    'content' => array('meta'),
    'controls' => array('audio', 'video'),
    'coords' => array('area'),
    'data' => array('object'),
    'datetime' => array('del', 'ins', 'time'),
    'default' => array('track'),
    'defer' => array('script'),
    'dirname' => array('input', 'textarea'),
    'disabled' => array('button', 'fieldset', 'input', 'optgroup', 'option', 'select', 'textarea'),
    'download' => array('a', 'area'),
    'enctype' => array('form'),
    'for' => array('label', 'output'),
    'form' => array('button', 'fieldset', 'input', 'label', 'meter', 'object', 'output', 'select', 'textarea'),
    'formaction' => array('button', 'input'),
    'headers' => array('td', 'th'),
    'height' => array('canvas', 'embed', 'iframe', 'img', 'input', 'object', 'video'),
    'high' => array('meter'),
    'href' => array('a', 'area', 'base', 'link'),
    'hreflang' => array('a', 'area', 'link'),
    'http-equiv' => array('meta'),
    'ismap' => array('img'),
    'kind' => array('track'),
    'label' => array('track', 'option', 'optgroup'),
    'list' => array('input'),
    'loop' => array('audio', 'video'),
    'low' => array('meter'),
    'max' => array('input', 'meter', 'progress'),
    'maxlength' => array('input', 'textarea'),
    'media' => array('a', 'area', 'link', 'source', 'style'),
    'method' => array('form'),
    'min' => array('input', 'meter'),
    'multiple' => array('input', 'select'),
    'muted' => array('video', 'audio'),
    'name' => array('button', 'fieldset', 'form', 'iframe', 'input', 'map', 'meta', 'object', 'output', 'param', 'select', 'textarea'),
    'novalidate' => array('form'),
    // 'on*' // omitting 'on<event>' attributes
    'open' => array('details'),
    'optimum' => array('meter'),
    'pattern' => array('input'),
    'placeholder' => array('input', 'textarea'),
    'poster' => array('video'),
    'preload' => array('audio', 'video'),
    'readonly' => array('input', 'textarea'),
    'rel' => array('a', 'area', 'link'),
    'required' => array('input', 'select', 'textarea'),
    'reversed' => array('ol'),
    'rows' => array('textarea'),
    'rowspan' => array('td', 'th'),
    'sandbox' => array('iframe'),
    'scope' => array('th'),
    'selected' => array('option'),
    'shape' => array('area'),
    'size' => array('input', 'select'),
    'sizes' => array('img', 'link', 'source'),
    'span' => array('col', 'colgroup'),
    'src' => array('audio', 'embed', 'iframe', 'img', 'input', 'script', 'source', 'track', 'video'),
    'srcdoc' => array('iframe'),
    'srclang' => array('track'),
    'srcset' => array('img', 'source'),
    'start' => array('ol'),
    'step' => array('input'),
    'target' => array('a', 'area', 'base', 'form'),
    'type' => array('button', 'embed', 'input', 'link', 'menu', 'object', 'script', 'source', 'style'),
    'usemap' => array('img', 'object'),
    'value' => array('button', 'input', 'li', 'option', 'meter', 'progress', 'param'),
    'width' => array('canvas', 'embed', 'iframe', 'img', 'input', 'object', 'video'),
    'wrap' => array('textarea'),
  );
  const GlobalAttributes = array(
    'accesskey',
    'class',
    'contenteditable',
    // 'data-*', // handled separately
    'dir',
    'draggable',
    'dropzone',
    'hidden',
    'id',
    'lang',
    'spellcheck',
    'style',
    'tabindex'
    'title',
    'translate',
  );
  public function __construct($tag, $children=array()) {
    if (!$this->_is_valid_tag($tag)) {
      throw new \Exception("error: invalid tag name <{$tag}>");
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
    // Rename clashing attribute names ('_title' -> 'title')
    if (substr($method, 0, 1) == '_') {
      $method = substr($method, 1);
    }
    if ($this->_is_valid_attr($method)) {
      $this->_attrs[$method] = join(' ', $args);
      return $this;
    }
    $message = "node error: invalid callback <method=" . $method . ", args=[" . join(', ', $args) ."]>";
    throw new \Exception($message);
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
    return in_array($tag, Node::Tags);
  }
  protected function _is_root_tag($tag) {
    return 'html' is $this->_tag; // document root tag
  }
  protected function _is_valid_attr($attr) {
    return $this->_is_valid_global_attr($attr)
      || $this->_is_valid_attr_tag($attr));
  }
  protected function _is_valid_global_attr($attr) {
    return in_array($attr, Node::GlobalAttributes)
      || substr($attr, 0, 5) == 'data-' // workaround to accept 'data-*' attributes
      || substr($attr, 0, 2) == 'on'; // workaround for 'on<event>' attributes
  }
  protected function _is_valid_attr_tag($attr) {
    return array_key_exists($attr, Node::Attributes)
      && in_array($this->_tag, Node::Attributes[$attr]);
  }
  protected function _render_doctype() {
    return join('', array('<!DOCTYPE ', Node::DocType, '>', PHP_EOL));
  }
  protected function _render_tag() {
    $tag = $this->_tag;
    $attrs = $this->_render_attrs();
    $children = $this->_render_children();
    $result = array();
    if ($this->_is_root_tag()) {
      $result[] = $this->_render_doctype();
    }
    if (count($this->_children)) {
      $result[] = "<{$tag}{$attrs}>$children</{$tag}>";
    } else {
      $result[] = "<{$tag}{$attrs}>";
    }
    return join('', $result);
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
