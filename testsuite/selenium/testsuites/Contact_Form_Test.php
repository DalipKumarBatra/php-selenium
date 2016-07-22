<?php
/*
 * Test Suite - Contact Form
 * 
 * This class contains test scripts which will verify contact form page.
 */
$workingDir = realpath(dirname(__DIR__));
require_once "$workingDir/lib/Browser_Base_Test_Case.php";

class Contact_Form_Test_Suite extends Browser_Base_Test_Case
{
    /*
     * Test to verify validation message on submitting empty form
     */
    public function test_to_verify_validation_message_on_submitting_form_without_filling()
    {
        Browser_Contact_Form::open_contact_page();
        Browser_Contact_Form::click_submit_button();
        Browser_Contact_Form::verify_empty_firstname_validation_messages();
    }

    /*
     * Test to verify first name validation message
     */
    public function test_to_verify_empty_firstname_validation_messages()
    {
        $user_data = [
                'firstname' => '',
                'lastname' => 'testname',
                'username' => 'testusername',
                'email' => 'test@test.com',
        ];

        Browser_Contact_Form::open_contact_page();
        Browser_Contact_Form::fill_form($user_data);
        Browser_Contact_Form::click_submit_button();
        Browser_Contact_Form::verify_empty_firstname_validation_messages();
    }
    
    /*
     * Test to verify lastname validation message
     */
    public function test_to_verify_empty_lastname_validation_messages()
    {
        $user_data = [
                'firstname' => 'testfirstname',
                'lastname' => '',
                'username' => 'testusername',
                'email' => 'test@test.com',
        ];

        Browser_Contact_Form::open_contact_page();
        Browser_Contact_Form::fill_form($user_data);
        Browser_Contact_Form::click_submit_button();
        Browser_Contact_Form::verify_empty_lastname_validation_messages();
    }
    
    /*
     * Test to verify Username validation message
     */
    public function test_to_verify_empty_username_validation_messages()
    {
        $user_data = [
                'firstname' => 'testfirstname',
                'lastname' => 'testlastname',
                'username' => '',
                'email' => 'test@test.com',
        ];

        Browser_Contact_Form::open_contact_page();
        Browser_Contact_Form::fill_form($user_data);
        Browser_Contact_Form::click_submit_button();
        Browser_Contact_Form::verify_empty_username_validation_messages();
    }

    /*
     * Test to verify email validation message when only email field is empty
     */
    public function test_to_empty_email_validation_messages()
    {
        $user_data = [
                'firstname' => 'testname',
                'lastname' => 'testlastname',
                'username' => 'testusername',
                'email' => '',
        ];

        Browser_Contact_Form::open_contact_page();
        Browser_Contact_Form::fill_form($user_data);
        Browser_Contact_Form::click_submit_button();
        Browser_Contact_Form::verify_empty_email_validation_messages();
    }

    /*
     * Test to verify that contact form is submitting successfully on filling all fields
     */
    public function test_to_verify_contact_form_submitted_successfully()
    {
        $user_data = [
                'firstname' => 'New test',
                'lastname' => 'testlastname',
                'username' => 'testusername',
                'email' => 'test@test.com',
        ];

        Browser_Contact_Form::open_contact_page();
        Browser_Contact_Form::fill_form($user_data);
        Browser_Contact_Form::click_submit_button();

        // Failing test script by checking error message 
        Browser_Contact_Form::verify_empty_email_validation_messages();
        //Browser_Thanks_Page::verify_thanks_page();
    }
}