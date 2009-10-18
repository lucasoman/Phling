<?php

namespace Phling;

abstract class delegate {
	/**
	 * stores the delegator who owns this delegate to be used
	 * later if necessary
	 *
	 * @author Lucas Oman <me@lucasoman.com>
	 * @param delegator
	 * @return null
	 */
	abstract public function setDelegator($d);

	public function __call($funcName,$args) {
		/*
			 If method doesn't exist, throw exception to calling code
			 so that it knows it doesn't exist.
			 */
		throw new DelegatorNoMethodException($funcName);
	}

	static public function __callStatic($funcName,$args) {
		/*
			 If method doesn't exist, throw exception to calling code
			 so that it knows it doesn't exist.
			 */
		throw new DelegatorNoMethodException($funcName);
	}
}

?>
