<?php
/**
 * Parent class for delegators.
 *
 * @author Lucas Oman <me@lucasoman.com>
 */

namespace Phling;

class Delegator {
	/**
	 * adds a new delegate
	 * delegates are FIFO when attempting to resolve method/attr names
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param delegate
	 * @return null
	 */
	public function addDelegate(\Phling\Delegate $delegate) {
		$delegate->setDelegator($this);
		$this->delegator_delegates[] = $delegate;
	}

	/**
	 * does this delegator have a certain delegate?
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string delegate name
	 * @return bool have the delegate?
	 */
	public function hasDelegate($name) {
		foreach ($this->delegator_delegates as $d) {
			if (get_class($d) == $name) {
				return true;
			}
		}
		return false;
	}

	/**
	 * PHP magic method for unknown method.
	 * Searches each delegate for a matching method, executing
	 * the first one it finds.
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string method name
	 * @param array args
	 * @return return value or null if no method found
	 */
	public function __call($funcName,$args) {
		// loop through each delegate looking for method
		foreach ($this->delegator_delegates as $d) {
			try {
				$return = call_user_func_array(array($d,$funcName),$args);
				return $return;
			} catch (DelegatorNoMethodException $e) {
				continue;
			}
		}
	}
	
	/**
	 * PHP magic method for unknown attribute.
	 * Searches each delegate for the attribute, returning the
	 * value of the first match.
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string attribute name
	 * @return mixed attribute value or null if not found
	 */
	public function __get($attr) {
		// loop through each delegate looking for attribute
		foreach ($this->delegator_delegates as $d) {
			if (isset($d->$attr)) {
				return $d->$attr;
			}
		}
		return null;
	}

	/**
	 * PHP magic method for setting unknown attribute.
	 * Searches delegates for matching attribute. If none
	 * found, sets value in this object.
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string attribute name
	 * @param mixed attribute value
	 * @return null
	 */
	public function __set($attr,$val) {
		foreach ($this->delegator_delegates as $d) {
			if (isset($d->$attr)) {
				$d->$attr = $val;
			}
		}
		$this->$attr = $val;
	}

	/**
	 * Delegates of this object
	 *
	 * @var array
	 */
	protected $delegator_delegates = array();
}

?>
