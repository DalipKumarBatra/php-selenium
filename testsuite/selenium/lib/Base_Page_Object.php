<?php

class Base_Page_Object
{
	/* @var Box_Base_Browser_Test_Case */
	public static $test;

	public static function set_test($test)
	{
		self::$test = $test;
	}
}
