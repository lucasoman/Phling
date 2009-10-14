<?php

abstract class delegate {
	abstract public function setDelegator($d);

	public function __call($funcName,$args) {
		throw new DelegatorNoMethodException($funcName);
	}

	static public function __callStatic($funcName,$args) {
		throw new DelegatorNoMethodException($funcName);
	}
}

?>
