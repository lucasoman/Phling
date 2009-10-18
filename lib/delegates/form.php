<?php

namespace Phling\Delegates;

class Form extends Phling\Delegate {
	/**
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param bool sanitize input?
	 * @return null
	 */
	public function __construct($sanitize) {
		$this->$_sanitizeHtml = (bool)$sanitize;
	}

	/**
	 * set the user input
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param array user input (typically POST or GET data)
	 * @return null
	 */
	public function setUserData($post) {
		$this->_post = $post;
		foreach ($this->_post as $i=>$v) {
			if ($this->_sanitizeHtml) {
				$v = $this->sanitizeHtml($v);
			}
			$this->_delegator->$i = $v;
		}
	}

	/**
	 * sets the delegator that commands this delegate
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param delegator
	 * @return null
	 */
	public function setDelegator($d) {
		$this->_delegator = $d;
	}

	/**
	 * cleanses user input of html
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string to sanitize
	 * @return string sanitized
	 */
	private function sanitizeHtml($v) {
		$v = htmlspecialchars($v);
		return $v;
	}

	private $_post;
	private $_delegator;
}

?>
