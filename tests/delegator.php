<?php

$tester->setGroup('Delegator');

class DelegateA extends \Phling\Delegate {
	public function setDelegator($d) {
		$this->_delegator = $d;
	}

	public function testMethodA() {
		return 'test method a';
	}

	public $_delegateAttrA = 'delegate attr a';
	private $_delegator;
}

$delegator = new DelegatorA();
$delegator->addDelegate(new DelegateA());

$tester->test('calls delegated method',$delegator->testMethodA(),'test method a');
$tester->test('returns delegated attr',$delegator->_delegateAttrA,'delegate attr a');

$delegator->_delegateAttrA = 'attr value reset';

$tester->test('sets delegated attr',$delegator->_delegateAttrA,'attr value reset');

?>
