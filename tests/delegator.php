<?php

require_once('lib/delegator.php');
require_once('lib/delegate.php');
require_once('lib/delegatornomethodexception.php');

$tester->setGroup('Delegator');

class DelegateA extends Delegate {
	public function setDelegator($d) {
		$this->_delegator = $d;
	}

	public function testMethodA() {
		return 'test method a';
	}

	static public function testStaticMethodA() {
		return 'test static method a';
	}

	private $_delegator;
}

class DelegatorA extends Delegator {
}

$delegator = new DelegatorA();
$delegator->addDelegate(new DelegateA());

$tester->test('calls delegated method',$delegator->testMethodA(),'test method a');

?>
