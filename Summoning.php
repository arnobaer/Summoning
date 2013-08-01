<?php define ('SUMMONING', true);

/*
 *  Summoning - HTML code generator
 *  Copyright (C) 2012-2013  Bernhard Arnold <bernhard.arnold@cern.ch>
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
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

class Summoning {
	static public function a($href, $content) {
		$a = new SummoningHtmlElement('a', $content ? $content : '');
		$a->setAttribute('href', $href ? $href : '#');
		return $a;
	}
	static public function div($content) {
		return new SummoningHtmlElement('div', $content ? $content : '');
	}
	static public function em($content) {
		return new SummoningHtmlElement('em', $content ? $content : '');
	}
	static public function h($level, $content) {
		$minlevel = 1;
		$maxlevel = 6;
		$level = intval($level);
		$level = $level < $minlevel ? $minlevel : ($level > $maxlevel ? $maxlevel : $level);
		return new SummoningHtmlElement("h{$level}", $content ? $content : '');
	}
	static public function p($content) {
		return new SummoningHtmlElement('p', $content ? $content : '');
	}
	static public function span($content) {
		return new SummoningHtmlElement('span', $content ? $content : '');
	}
	static public function strong($content) {
		return new SummoningHtmlElement('strong', $content ? $content : '');
	}
	static public function inputText($name, $value = '', $maxlength = false) {
		return new SummoningHtmlInputText($name, $value, $maxlength);
	}
	static public function inputSubmit($name, $value = '') {
		return new SummoningHtmlInputSubmit($name, $value);
	}
	static public function select($name, $items = array()) {
		return new SummoningHtmlSelect($name, $items);
	}
	static public function selectDate($basename) {
		return new SummoningHtmlSelectDate($basename);
	}
	static public function selectTime($basename) {
		return new SummoningHtmlSelectTime($basename);
	}
}

class SummoningHtmlElement {
	protected $tag;
	protected $content;
	protected $attributes;
	public function __construct($tag, $content = false) {
		$this->tag = $tag;
		$this->content = $content;
		$this->attributes = array();
	}
	public function setAttribute($key, $value) {
		$this->attributes["{$key}"] = "{$value}";
	}
	public function removeAttribute($key) {
		unset($this->attributes["{$key}"]);
	}
	public function get() {
		$attributes = $this->getAttributes();
		return "<{$this->tag}{$attributes}" . ($this->content === false ? ">" : ">{$this->content}</{$this->tag}>");
	}
	protected function getAttributes() {
		$attributes = array();
		foreach ($this->attributes as $key => $value) {
			$attributes[] = "{$key}=\"{$value}\"";
		}
		$attributes = implode(' ', $attributes);
		return $attributes ? " {$attributes}" : '';
	}
}

class SummoningInputElement extends SummoningHtmlElement {
	public function __construct($type, $name, $value = '') {
		parent::__construct('input');
		$this->setAttribute('type', $type);
		$this->setAttribute('name', $name);
		$this->setAttribute('value', $value);
	}
	// Provided for convenience.
	public function setValue($value) {
		$this->setAttribute('value', $value);
	}
}

class SummoningHtmlInputText extends SummoningInputElement {
	public function __construct($name, $value = '', $maxlength = false) {
		parent::__construct('text', $name, $value);
		if ($maxlength) $this->setAttribute('maxlength', $maxlength);
	}
}

class SummoningHtmlInputSubmit extends SummoningInputElement {
	public function __construct($name, $value = '') {
		parent::__construct('submit', $name, $value);
	}
}

class SummoningHtmlSelectOption extends SummoningHtmlElement {
	public function __construct($key, $value, $active = false) {
		parent::__construct('option', $key);
		$this->setAttribute('value', $value);
		if ("$active" === "$value") $this->setAttribute('selected', 'selected');
	}
}

class SummoningHtmlSelect extends SummoningHtmlElement {
	protected $items;
	public function __construct($name, $items = array()) {
		parent::__construct('select', '');
		$this->setAttribute('name', $name);
		$this->items = $items;
	}
	protected function getOptions($active) {
		$content = array();
		foreach ($this->items as $key => $value) {
			$option = new SummoningHtmlSelectOption($key, $value, $active);
			$content[] = $option->get();
		}
		return "\n".implode("\n", $content)."\n";
	}
	public function get($active = false) {
		$this->content = $this->getOptions($active);
		return parent::get();
	}
}

class SummoningHtmlSelectDate extends SummoningHtmlSelect {
	protected $day, $months, $year;
	public function __construct($basename) {
		$this->day = new SummoningHtmlSelect("{$basename}_day", SummoningToolbox::days());
		$this->month = new SummoningHtmlSelect("{$basename}_month", SummoningToolbox::months());
		$this->year = new SummoningHtmlInputText("{$basename}_year", false, 4);
	}
	public function get($datetime = '0000-00-00') {
		// tag, monat, jahr,  HH:MM
		$date = explode('-', $datetime);
		$day = intval($date[2]);
		$month = intval($date[1]);
		$year = intval($date[0]);
		$get = '';
		$get .= $this->day->get("$day");
		$get .= $this->month->get("$month");
		$this->year->setValue($year);
		$get .= $this->year->get();
		return $get;
	}
}

class SummoningHtmlSelectTime extends SummoningHtmlSelect {
	protected $hour, $minute;
	public function __construct($basename) {
		$this->hour = new SummoningHtmlSelect("{$basename}_hour", SummoningToolbox::hours());
		$this->minute = new SummoningHtmlSelect("{$basename}_minute", SummoningToolbox::minutes(true));
	}
	public function get($datetime = '0000-00-00 00:00') {
		// tag, monat, jahr,  HH:MM
		$datetime = explode(' ', $datetime);
		$date = explode('-', $datetime[0]);
		$time = explode(':', $datetime[1]);
		$day = intval($date[2]);
		$month = intval($date[1]);
		$year = intval($date[0]);
		$hour = intval($time[0]);
		$minute = intval($time[1]);
		$get = '';
		$get .= $this->hour->get("$hour");
		$get .= $this->minute->get("$minute");
		return $get;
	}
}

class SummoningToolbox {
	static public function minutes($quarters = false) {
		$minutes = array();
		for ($i = 0; $i < 60; $i += ($quarters ? 15 : 1)) $minutes[sprintf("%02d", $i)] = $i;
		return $minutes;
	}
	static public function hours() {
		$hours = array();
		for ($i = 0; $i < 24; ++$i) $hours[sprintf("%02d", $i)] = $i;
		return $hours;
	}
	static public function days() {
		$days = array();
		for ($i = 1; $i <= 31; ++$i) $days["{$i}"] = $i;
		return $days;
	}
	static public function months($lang = 'de', $short = false) {
		switch ($lang) {
			case 'de':
			case 'de-DE':
			case 'de-AT':
				return array(
					'Jänner' => 1, 'Februar' => 2, 'März' => 3, 'April' => 4, 'Mai' => 5, 'Juni' => 6,
					'Juli' => 7, 'August' => 8, 'September' => 9, 'Oktober' => 10, 'November' => 11, 'Dezember' => 12, );
			case 'en':
			case 'en-US':
			default:
				return array(
					'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4, 'May' => 5, 'June' => 6,
					'July' => 7, 'August' => 8, 'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12, );
		} // switch
	}
	static public function get($key) {
		return isset($_GET[$key]) ? filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING) : false;
	}
	static public function post($key) {
		return isset($_POST[$key]) ? filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING) : false;
	}
	static public function cookie($key) {
		return isset($_COOKIE[$key]) ? filter_input(INPUT_COOKIE, $key, FILTER_SANITIZE_STRING) : false;
	}
	static public function server($key) {
		return isset($_SERVER[$key]) ? filter_input(INPUT_SERVER, $key, FILTER_SANITIZE_STRING) : false;
	}
}

