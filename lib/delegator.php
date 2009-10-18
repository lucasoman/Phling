<?php

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
	public function addDelegate($delegate) {
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
	
	public function __get($attr) {
		// loop through each delegate looking for attribute
		foreach ($this->delegator_delegates as $d) {
			if (isset($d->$attr)) {
				return $d->$attr;
			}
		}
		return null;
	}

	public function __set($attr,$val) {
		foreach ($this->delegator_delegates as $d) {
			if (isset($d->$attr)) {
				$d->$attr = $val;
			}
		}
		$this->$attr = $val;
	}

	protected $delegator_delegates = array();
}

?>
