<?php
/*
 * Test Suite - Login Form
 * 
 * This class contains test scripts which will verify contact form page.
 */
$workingDir = realpath(dirname(__DIR__));
require_once "$workingDir/lib/Browser_Base_Test_Case.php";

class Login_Test_Suite extends Browser_Base_Test_Case
{
    /*
     * Test to verify validation message on submitting empty form
     */
    public function test_to_verify_validation_message_on_submitting_form_without_filling()
    {
        // Create user from db
		$user = $this->phactory->create('user_ph');
		
        Browser_Login_Form::open_login_page();
        Browser_Login_Form::fill_username($user);
        Browser_Login_Form::fill_password($user);
        Browser_Login_Form::click_submit_button();
        Browser_Contact_Form::verify_contact_page();
    }

    /*
     * Test to verify password manadatory validation message on submitting form without password
     */
    public function test_to_verify_password_manadatory_validation_message_on_submitting_form_without_password()
    {
        $user = $this->phactory->create('user_ph');
        Browser_Login_Form::open_login_page();
        Browser_Login_Form::fill_username($user);
        Browser_Login_Form::click_submit_button();
        Browser_Login_Form::verify_empty_password_validation_message();
    }
}
