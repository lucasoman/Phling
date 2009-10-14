<?php

require_once('../Tester/tester.php');

$tester = Tester::singleton();
$tester->runTests(array(
			array('tests/delegator.php',Tester::TESTRUN),
			));

$tester->setShowTests();
$tester->setShowTotals();
$tester->setShowFailing();
$tester->setShowPassing(false);
$tester->setShowContents();
print($tester->getResults());

?>
