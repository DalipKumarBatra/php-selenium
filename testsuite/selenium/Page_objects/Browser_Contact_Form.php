<?php

/**
* This page object class include all functions of Contact Form page
*/
class Browser_Contact_Form extends Base_Page_Object
{
	public static function open_contact_page()
	{
		$test = self::$test;
		$test->url('/index.php');
		$test->wait_for_title('Contact Form');
	}

	/**
	 * @param string $user_data
	 */
	public static function fill_form($user_data)
	{
		$test = self::$test;

		$test->clear_and_fill_new_value('#firstname', $user_data['firstname']);
        $test->clear_and_fill_new_value('#lastname', $user_data['lastname']);
		$test->clear_and_fill_new_value('#username', $user_data['username']);
		$test->clear_and_fill_new_value('#email', $user_data['email']);
	}
	
	public static function click_submit_button()
	{
		$test = self::$test;
		$button_element = $test->byId('submit');
		$test->moveto($button_element);
		$button_element->click();
	}

	public static function verify_thanks_page()
	{
		$test = self::$test;

		$page_title = $test->title();
		$test->assertContains($page_title, 'Thank You');
	}
        
    public static function verify_contact_page()
	{
		$test = self::$test;

		$test->wait_for_title('Contact Form');
                $page_title = $test->title();
		$test->assertContains($page_title, 'Contact Form', 'We are on wrong page');
	}
	
	public static function verify_empty_firstname_validation_messages()
	{
		$test = self::$test;

		$test->wait_for_element('#firstname_label span.red');

		$name_error_messages = $test->byCssSelector('#firstname_label span.red');
		$test->assertNotNull($name_error_messages);
	}
	
	public static function verify_empty_lastname_validation_messages()
	{
		$test = self::$test;

		$test->wait_for_element('#lastname_label span.red');
		
		$name_error_messages = $test->byCssSelector('#lastname_label span.red');
		$test->assertNotNull($name_error_messages);
	}
        
    public static function verify_empty_username_validation_messages()
	{
		$test = self::$test;

		$test->wait_for_element('#username_label span.red');
		
		$name_error_messages = $test->byCssSelector('#username_label span.red');
		$test->assertNotNull($name_error_messages);
	}
        
    public static function verify_empty_email_validation_messages()
	{
		$test = self::$test;

		$test->wait_for_element('#email_label span.red');
		
		$name_error_messages = $test->byCssSelector('#email_label span.red');
		$test->assertNotNull($name_error_messages, 'Error message should exists on contact page');
	}
}
