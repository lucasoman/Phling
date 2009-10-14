<?php

class Delegator {
	public function addDelegate($delegate) {
		$delegate->setDelegator($this);
		$this->delegator_delegates[] = $delegate;
	}

	public function __call($funcName,$args) {
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
		foreach ($this->delegator_delegates as $d) {
			if (isset($d->$attr)) {
				return $d->$attr;
			}
		}
	}

	public function __set($attr,$val) {
		foreach ($this->delegator_delegates as $d) {
			if (isset($d->$attr)) {
				$d->$attr = $val;
			}
		}
	}

	protected $delegator_delegates = array();
}

?>
