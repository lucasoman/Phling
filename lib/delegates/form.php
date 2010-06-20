<?php

namespace Phling\Delegates;

class Form extends \Phling\Delegate {
	/**
	 * Store user input in delegator. Only allowed keys are set to prevent
	 * important values from being overwritten and to prevent security holes.
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param array user input (typically POST or GET data)
	 * @param array keys that are allowed to be set
	 * @return null
	 */
	public function setUserData(array $post,array $allowed) {
		$this->_post = $post;
		foreach ($this->_post as $i=>$v) {
			if (in_array($i,$allowed)) {
				$this->_delegator->$i = $v;
			}
		}
	}

	/**
	 * sets the delegator that commands this delegate
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param delegator
	 * @return null
	 */
	public function setDelegator(\Phling\Delegator $d) {
		$this->_delegator = $d;
	}

	/**
	 * sanitize values
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string which type of sanitization
	 * @param array names to sanitize
	 * @return null
	 */
	public function sanitize($type,$which) {
		if (($method = $this->getSanitizeMethod($type)) === false) {
			throw new Exception('Unknown sanitization type.');
		}
		foreach ($which as $v) {
			$this->_delegator->$v = $this->$method($this->_delegator->$v);
		}
	}

	/**
	 * sanitize values
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string which type of sanitization
	 * @param array names to exclude from sanitization
	 * @return null
	 */
	public function sanitizeExcept($type,$which) {
		if (($method = $this->getSanitizeMethod($type)) === false) {
			throw new Exception('Unknown sanitization type.');
		}
		foreach ($this->_post as $v=>$value) {
			if (!array_search($v,$which)) {
				$this->_delegator->$v = $this->$method($this->_delegator->$v);
			}
		}
	}

	/**
	 * get which sanitization method should be used
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string type of sanitization
	 * @return string method name
	 */
	private function getSanitizeMethod($type) {
		$type = strtolower($type);
		switch ($type) {
			case 'sql':
				return 'sanitizeSqlString';
			case 'html':
				return 'sanitizeHtmlString';
			default:
				return false;
		}
	}

	/**
	 * escapes value for SQL query
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string to sanitize
	 * @return string sanitized
	 */
	private function sanitizeSqlString($v) {
		$v = mysql_real_escape_string($v);
		return $v;
	}

	/**
	 * cleanses user input of html
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string to sanitize
	 * @return string sanitized
	 */
	private function sanitizeHtmlString($v) {
		$v = htmlspecialchars($v);
		return $v;
	}

	private $_post;
	private $_delegator;
	private $_sanitizeHtml;
}

?>
