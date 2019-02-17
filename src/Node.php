<?php

/* Summoning - Yet another HTML5 code generator.
 * Copyright (C) 2013-2019  Bernhard Arnold <bernhard.arnold@burgried.at>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

// Version 0.4.0

namespace Summoning;

/**
 * Returns true if string starts with $needle.
 */
function str_startswith($haystack, $needle) {
	return 0 == strlen($needle)
		|| false !== strrpos($haystack, $needle, -strlen($haystack));
}

/**
 * Rules for elements and attributes.
 */
class Rules {
	/** HTML doctype */
	const DocType = 'html';

	/** Prefix for ambiguous attribute calls */
	const AttributeEscape = '_';

	/** Prefix for template calls */
	const TemplatePrefix = 'tpl_';

	/** Defines the HTML5 root element */
	const RootElement = 'html';

	/** List containing valid elements */
	const Elements = array(
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

	/** List containing void elements (<tag />) */
	const VoidElements = array(
		'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
		'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr',
	);

	/** Map containing valid attributes with element context */
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
		'crossorigin' => array('img', 'script', 'video'),
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
		'integrity' => array('link', 'script'), // non HTML5 spec
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
		// 'on*' // 'on<event>' handled separately
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
	/** List containing valid global attributes (to be used with any element) */
	const GlobalAttributes = array(
		'accesskey',
		// 'aria-*', // WAI ARIA, handled separately
		'class',
		'contenteditable',
		// 'data-*', // handled separately
		'dir',
		'draggable',
		'dropzone',
		'hidden',
		'id',
		'lang',
		'role', // WAI ARIA
		'spellcheck',
		'style',
		'tabindex',
		'title',
		'translate',
	);
}

/**
 * HTML5 element node class.
 */
class Node extends Rules {
	protected $_tag;
	protected $_parent;
	protected $_attrs;
	protected $_children;
	protected static $_templates = array();

	/** Constructor requiring tag name and optional list of child elements
	 * (nodes and/or strings) */
	public function __construct($tag, $children=array()) {
		if (!$this->_is_valid_tag($tag)) {
			throw new \Exception("Invalid element name <{$tag}>");
		}
		$this->_tag = $tag;
		$this->_parent = null;
		$this->_attrs = array();
		$this->_children = $children;
	}

	/** Dynamic method handler */
	public function __call($method, $args) {
		$context = $method;
		// Test for valid template
		if ($this->_is_valid_template($context)) {
			$closure = self::$_templates[$context];
			array_unshift($args, $this); // prepend context
			$node = call_user_func_array($closure, $args);
			if ($node instanceof Node) {
				if (null === $node->parent()) {
					$this->append($node);
				}
				return $node;
			}
			return $this;
		}
		// Test for valid element
		if ($this->_is_valid_tag($context)) {
			$node = new Node($context, $args);
			$this->append($node);
			return $node;
		}
		// Un-escape clashing attribute names ('_title' -> 'title')
		if (str_startswith($context, self::AttributeEscape)) {
			$context = substr($context, strlen(self::AttributeEscape));
		}
		// Accept attributes with escaped hyphens ('http_equiv' -> 'http-equiv')
		$context = str_replace('_', '-', $context);
		// Test for valid attribute
		if ($this->_is_valid_attr($context)) {
			$this->_attrs[$context] = join('', $args);
			return $this;
		}
		// On errors
		$message = "Invalid attribute '{$context}' for element <{$this->_tag}>";
		throw new \Exception($message);
	}

	/** Append any number of nodes or strings, sets nodes parent to this. */
	public function append(...$nodes) {
		foreach ($nodes as $node) {
			if ($node instanceof Node) {
				$node->_parent = $this;
			}
		}
		$this->_children = array_merge($this->_children, $nodes);
		return $this;
	}

	/** Prepends any number of nodes or strings, sets nodes parent to this. */
	public function prepend(...$nodes) {
		foreach ($nodes as $node) {
			if ($node instanceof Node) {
				$node->_parent = $this;
			}
		}
		$this->_children = array_merge($nodes, $this->_children);
		return $this;
	}

	/** Returns node's parent node or null if no parent assigned */
	public function parent() {
		return $this->_parent;
	}

	/** Create a new independed node (no parent) */
	public static function create($tag) {
		return new Node($tag); // create a node without parent
	}

	/** Register template function */
	public function register($name, $callback) {
		$key = join('', array(self::TemplatePrefix, "$name"));
		self::$_templates[$key] = $callback;
	}

	/** Render string representation */
	public function toHtml() {
		return $this->_render_tag();
	}

	/** Render string representation */
	public function __toString() {
		return $this->_render_tag();
	}

	/** Check if name is a template (using template prefix) */
	protected function _is_valid_template($name) {
		return str_startswith($name, self::TemplatePrefix);
	}

	/** Check if tag is a valid element */
	protected function _is_valid_tag($tag) {
		return in_array($tag, self::Elements);
	}

	/** Check if node the rote node. */
	protected function _is_root_tag() {
		return (self::RootElement == $this->_tag);
	}

	/** Check if attribute is valid */
	protected function _is_valid_attr($attr) {
		return $this->_is_valid_global_attr($attr)
			|| $this->_is_valid_attr_tag($attr);
	}

	/** Check if attribute is a global attribute */
	protected function _is_valid_global_attr($attr) {
		return in_array($attr, self::GlobalAttributes)
			// workaround to accept 'aria-*', 'data-*' and 'on*' attributes
			|| str_startswith($attr, 'aria-')
			|| str_startswith($attr, 'data-')
			|| str_startswith($attr, 'on');
	}

	/** Check if attribute is valid with tag */
	protected function _is_valid_attr_tag($attr) {
		return array_key_exists($attr, self::Attributes)
			&& in_array($this->_tag, self::Attributes[$attr]);
	}

	/** Render HTML doctype. */
	protected function _render_doctype() {
		return join('', array('<!DOCTYPE ', self::DocType, '>', PHP_EOL));
	}

	/** Render tag and its children */
	protected function _render_tag() {
		$tag = $this->_tag;
		$attrs = $this->_render_attrs();
		$children = $this->_render_children();
		$result = array();
		if ($this->_is_root_tag()) {
			$result[] = $this->_render_doctype();
		}
		if (in_array($tag, self::VoidElements)) {
			$result[] = "<{$tag}{$attrs} />";
		} else {
			$result[] = "<{$tag}{$attrs}>$children</{$tag}>";
		}
		return join('', $result);
	}

	/** Render list of attributes */
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
