<?php

require_once('../Tester/tester.php');

require_once('lib/delegator.php');
require_once('lib/delegate.php');
require_once('lib/delegatornomethodexception.php');
require_once('lib/delegates/form.php');

class DelegatorA extends \Phling\Delegator {
}


$tester = Tester::singleton();
$tester->runTests(array(
			array('tests/delegator.php',Tester::TESTRUN),
			array('tests/form.php',Tester::TESTRUN),
			));

$tester->setShowTests();
$tester->setShowTotals();
$tester->setShowFailing();
$tester->setShowPassing(false);
$tester->setShowContents();
print($tester->getResults());

?>
