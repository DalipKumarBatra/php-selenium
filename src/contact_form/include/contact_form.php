<?php
error_reporting(E_ALL);
$workingDir = realpath(dirname(__DIR__));
//echo $workingDir; die;
// Load include Library files
include_once "$workingDir/../../testsuite/unit/bootstrap.php";
/**
* Class Name : ContactForm
* 
* All processing related to contact form should be done from this class.
*/
class ContactForm
{
    /*
     * This method will process contact form inputs.
     * validate() member function is used for validate user data
     * SendFormSubmission() member function is used to submit form
     *
     * Expected behaviour:
     *  - Returns true if user input values are valid and processed successfully.
     *  - Returns false if data in form is not valid.
     *
     * @return boolean
     */
    public function processForm()
    {
        // validate() member function return true only if user data is valid.
        if(!$this->validate())
        {
            return false;
        }
        
        // After validation test, submit user input values using submitForm()
        $ret = $this->submitForm();

        return $ret;
    }

    /*
     * This method will validate contact form inputs values.
     * It will get data from member functions
     * 
     * Expected behaviour:
     * - Returns false if firstname field is empty
     * - Returns false if lastname field is empty
     * - Returns false if username field is empty
     * - Returns false if email field is empty
     * - Return collaborated string if data is valid
     *
     * @return boolean/string
     */
    public function validate()
    {
        $firstname = $this->getFirstName();
        if(empty($firstname))
        {
                return false;
        }
        
        $lastname = $this->getLastName();
        if(empty($lastname))
        {
                return false;
        }
        
        $username = $this->getUserName();
        if(empty($username))
        {
                return false;
        }

        $email = $this->getEmail();
        if(empty($email))
        {
                return false;
        }

        $time = Global_Functions::time();
        return $time.'+'.$firstname.'+'.$lastname.'+'.$username.'+'.$email;
    }
	
    /*
     * This method will submit contact form inputs values.
     * 
     * Expected behaviour:
     * - Returns true if data is submitted successfully
     * - Returns false if these ia an error while submiting data
     *
     * @return boolean
     */    
    public function submitForm() 
    {
        return true;
    }

    /*
     * This method will either return null or post value
     *
     * @return null/string
     */
    public function safeDisplay($value_name)
    {
        if(empty($_POST[$value_name]))
        {
            return '';
        }
        return $_POST[$value_name];
    }
	
    /*
     * This method will return execution environment information
     * 
     * @return string
     */
    public function getSelfScript()
    {
        return htmlentities($_SERVER['PHP_SELF']);
    }
	
    /*
     * This method will return execution environment information
     *
     * @param string
     */
    public function RedirectToURL($url)
    {
        header("Location: $url");
        exit;
    }
	
    /*
     * This method will return value of first name field in contact form
     * 
     * @return string
     */
    public function getFirstName()
    {
        return $_POST['firstname'];
    }
    
    /*
     * This method will return value of last name field in contact form
     * 
     * @return string
     */
    public function getLastName()
    {
        return $_POST['lastname'];
    }
    
    /*
     * This method will return value of username field in contact form
     * 
     * @return string
     */
    public function getUsername()
    {
        return $_POST['username'];
    }


    /*
     * This method will return value of email field in contact form
     * 
     * @return string
     */
    public function getEmail()
    {
        return $_POST['email'];
    }
	
}