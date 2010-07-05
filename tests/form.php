<?php

$tester->setGroup('Form Delegate');

$delegator = new DelegatorA();
$delegator->addDelegate(new Phling\Delegates\Form());

$post = array(
		'ud1'=>'test',
		'ud2'=>"test'<>",
		'ud3'=>"test'<>",
		);
$delegator->setUserData($post,array('ud1','ud2','ud3'));

$tester->test('set post values',$delegator->ud1,'test');
$tester->test('values untouched',$delegator->ud3,"test'<>");


if (array_search('mysql',get_loaded_extensions()) !== false) {
	$sanitized = $delegator->sanitized('sql');
	$tester->test('sanitized for sql',$sanitized->ud2,"test\\'<>");
	$tester->test('ud1 untouched',$sanitized->ud1,'test');
}

$sanitized = $delegator->sanitized('html');
$tester->test('sanitized for html',$sanitized->ud2,"test'&lt;&gt;");
$tester->test('ud1 untouched',$sanitized->ud1,'test');

?>
