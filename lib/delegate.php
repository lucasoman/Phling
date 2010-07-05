<?php
/**
 * Parent class for delegates.
 *
 * @author Lucas Oman <me@lucasoman.com>
 */

namespace Phling;

class Delegate {
	/**
	 * stores the delegator who owns this delegate to be used
	 * later if necessary
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param delegator
	 * @return null
	 */
	public function setDelegator(\Phling\Delegator $d) {
		$this->_delegator = $d;
	}

	/**
	 * get the delegator that delgates to this delegate
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @return \Phling\Delegator object
	 */
	public function getDelegator() {
		return $this->_delegator;
	}

	/**
	 * PHP magic method for an unknown method.
	 * If a method is called but doesn't exist, we need to
	 * throw an exception.
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string method name
	 * @param array args
	 * @throws DelegatorNoMethodException
	 */
	public function __call($funcName,$args) {
		throw new DelegatorNoMethodException($funcName);
	}

	/**
	 * PHP magic method for unknown static method.
	 * If a static method is called but doesn't exist, we need
	 * to throw an exception.
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param string method name
	 * @param array args
	 * @throws DelegatorNoMethodException
	 */
	static public function __callStatic($funcName,$args) {
		throw new DelegatorNoMethodException($funcName);
	}

	/**
	 * Delegator that delegates to this delegate
	 *
	 * @var Delegator
	 */
	protected $_delegator;
}

?>
