<?php

$tester->setGroup('Delegator');

class DelegateA extends \Phling\Delegate {
	public function setDelegator(\Phling\Delegator $d) {
		$this->_delegator = $d;
	}

	public function testMethodA() {
		return 'test method a';
	}

	public function delegatorMethod() {
		return 'delegated method';
	}

	public $delegateAttrA = 'delegate attr a';
	public $delegatorAttr = 'delegated attribute';
}

$delegator = new DelegatorA();
$delegator->addDelegate(new DelegateA());

$tester->test('calls delegated method',$delegator->testMethodA(),'test method a');
$tester->test('returns delegated attr',$delegator->delegateAttrA,'delegate attr a');

$delegator->delegateAttrA = 'attr value reset';

$tester->test('sets delegated attr',$delegator->delegateAttrA,'attr value reset');
$tester->test('delegator methods take precedence',$delegator->delegatorMethod(),'delegator method');
$tester->test('delegator attributes take precedence (get)',$delegator->delegatorAttr,'delegator attribute');
$delegator->delegatorAttr = 'delegator attribute modified';
$tester->test('delegator attributes take precedence (set)',$delegator->delegatorAttr,'delegator attribute modified');

?>
