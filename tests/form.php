<?php

$tester->setGroup('Form Delegate');

$delegator = new DelegatorA();
$delegator->addDelegate(new Phling\Delegates\Form());

$post = array(
		'ud1'=>'test',
		'ud2'=>"test'<>",
		'ud3'=>"test'<>",
		);
$delegator->setUserData($post);

$tester->test('set post values',$delegator->ud1,'test');
$tester->test('values untouched',$delegator->ud2,"test'<>");


if (array_search('mysql',get_loaded_extensions()) !== false) {
	$delegator->sanitize('sql',array('ud2'));
	$tester->test('sanitized for sql',$delegator->ud2,"test\\'<>");
	$tester->test('ud1 untouched',$delegator->ud1,'test');
	$tester->test('ud3 untouched',$delegator->ud3,"test'<>");
}
$delegator->ud2 = "test'<>";

$delegator->sanitizeExcept('html',array('ud1','ud2'));

$tester->test('sanitize for html',$delegator->ud3,"test'&lt;&gt;");
$tester->test('ud1 untouched',$delegator->ud1,'test');
$tester->test('ud2 untouched',$delegator->ud2,"test'<>");

?>
