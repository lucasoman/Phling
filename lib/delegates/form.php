<?php
/**
 * Helps a model manage user data securely.
 *
 * @author Lucas Oman <me@lucasoman.com>
 *
 * class DelegatorChild extends \Phling\Delegator { ... }
 * $delegator = new DelegatorChild();
 * $delegator->addDelegate(new \Phling\Delegates\Form());
 * $delegator->setUserData($_POST,array('username','password','rememberMe'));
 * $query = "select * from users where * username='".$delegator->sanitized('sql')->username."'";
 */

namespace Phling\Delegates;

class Form extends \Phling\Delegate {
	public function __construct() {
		$this->_sanitized = array();
	}

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
		$this->_allowed = $allowed;
		foreach ($this->_post as $i=>$v) {
			if (in_array($i,$allowed)) {
				$this->_delegator->$i = $v;
			}
		}
	}

	/**
	 * Get an object containing values sanitized by type.
	 * Type is the second part of the name of any of this class's sanitize_*
	 * methods. The delegator can also define its own sanitize_* methods.
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string type
	 * @return StdClass with sanitized values
	 */
	public function sanitized($type) {
		if (isset($this->_sanitized[$type])) {
			$obj = $this->_sanitized[$type];
		} else {
			if (($method = $this->getSanitizeMethod($type)) === false) {
				throw new Exception('Unknown sanitization type.');
			}
			$obj = new \StdClass();
			foreach ($this->_allowed as $v) {
				$obj->$v = $this->_delegator->$method($this->_delegator->$v);
			}

			// cache this for later use
			$this->_sanitized[$type] = $obj;
		}

		return $obj;
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
		$method = 'sanitize_'.$type;
		if (method_exists($this,$method) || method_exists($this->_delegator,$method)) {
			return $method;
		}
		return false;
	}

	/**
	 * escapes value for SQL query
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string to sanitize
	 * @return string sanitized
	 */
	public function sanitize_sql($v) {
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
	public function sanitize_html($v) {
		$v = htmlspecialchars($v);
		return $v;
	}

	/**
	 * post or get data from delegator
	 *
	 * @var array
	 */
	private $_post;

	/**
	 * allowed user-supplied variable names
	 *
	 * @var array
	 */
	private $_allowed;

	/**
	 * cached stdclass objects sanitized for each type
	 *
	 * @var array
	 */
	private $_sanitized;
}

?>
