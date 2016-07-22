<?php

class Browser_Thanks_Page extends Base_Page_Object
{
	public static function verify_thanks_page()
	{
		$test = self::$test;

		$page_title = $test->title();
		$test->assertContains($page_title, 'Thank You');
	}
}
