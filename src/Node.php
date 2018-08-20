<?php

namespace Summoning;

if (!defined('SUMMONING_DEBUG')) {
  define('SUMMONING_DEBUG', true);
}

class Node {
  protected $_tag;
  protected $_attrs;
  protected $_parent = false;
  protected $_children;
  const DocType = 'html';
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
    'disabled' => array('download'),
    'href' => array('a', 'area', 'base', 'link'),
    'http-equiv' => array('meta'),
    'label' => array('track', 'option', 'optgroup'),
    'rel' => array('a', 'area', 'link'),
    'src' => array('audio', 'embed', 'iframe', 'img', 'input', 'script', 'source', 'track', 'video'),
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
    // Rename clashing attribute names ('_title' -> 'title')
    if (substr($method, 0, 1) == '_') {
      $method = substr($method, 1);
    }
    if ($this->_is_valid_attr($method)) {
      $this->_attrs[$method] = join(' ', $args);
      return $this;
    }
    if (SUMMONING_DEBUG) {
      $message = "node error: invalid callback <method=" . $method . ", args=[" . join(', ', $args) ."]>";
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
    return in_array($tag, Node::Tags);
  }
  protected function _is_valid_attr($attr) {
    return $this->_is_valid_global_attr($attr)
      || $this->_is_valid_attr_tag($attr));
  }
  protected function _is_valid_global_attr($attr) {
    return in_array($attr, Node::GlobalAttributes)
      || substr($attr, 0, 5) == 'data-'; // workaround to accept 'data-...' attributes
  }
  protected function _is_valid_attr_tag($attr) {
    return array_key_exists($attr, Node::Attributes)
      && in_array($this->_tag, Node::Attributes[$attr]);
  }
  protected function _render_tag() {
    $tag = $this->_tag;
    $attrs = $this->_render_attrs();
    $children = $this->_render_children();
    $result = array();
    if (false === $this->_parent and $this->_tag == 'html') {
      $result[] = join('', array('<!DocType ', Node::DocType, '>', PHP_EOL));
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
