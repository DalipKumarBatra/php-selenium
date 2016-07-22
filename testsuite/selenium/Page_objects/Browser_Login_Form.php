<?php

/**
* This page object class include all functions of Login Form page
*/
class Browser_Login_Form extends Base_Page_Object
{
	public static function open_login_page()
	{
		$test = self::$test;
		$test->url('/login.php');
		$test->wait_for_title('Login Page');
	}
        
    public static function fill_username($user_data)
	{
            $test = self::$test;
		
            $test->clear_and_fill_new_value('#username', $user_data->name);
	}

	public static function fill_password($user_data)
	{
            $test = self::$test;
	
            $test->clear_and_fill_new_value('#password', $user_data->pass);
	}
	
	public static function click_submit_button()
	{
		$test = self::$test;
		$button_element = $test->byId('submit');
		$test->moveto($button_element);
		$button_element->click();
	}
        
        public static function verify_empty_password_validation_message()
	{
		$test = self::$test;

		$test->wait_for_element('#login_pass .red');

		$password_messages = $test->byCssSelector('#login_pass .red');
		$test->assertNotNull($password_messages);
	}
}