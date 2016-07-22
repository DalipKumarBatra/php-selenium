<?php
error_reporting(E_ALL);
$workingDir = realpath(dirname(__DIR__));

// Load include Library files
include_once "$workingDir/../../testsuite/unit/bootstrap.php";
/**
* Class Name : ContactForm
* 
* All processing related to contact form should be done from this class.
*/
class LoginForm
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
    public function processLoginForm()
    {
        // validate() member function return true only if user data is valid.
        if(!$this->validateLoginForm())
        {
            return false;
        }
        
        // After validation test, submit user input values using submitForm()
           
        $user_valid = $this->checkUserDetailsFromDB();
        
        return $user_valid;
    }
    
    public function checkUserDetailsFromDB() 
    {
        $username = $this->getUserName();
        $password = $this->getPassword();
        if($username != '' && $password != '')
        {
            $db_conf = Diesel::DbConf();
            $user_details = $db_conf->getUserData($username, $password);
        }
                
        if(false === $user_details)
            return false;
        else 
            return true;
    }

    /*
     * This method will validate login form inputs values.
     * It will get data from member functions
     * 
     * Expected behaviour:
     * - Returns false if username field is empty
     * - Returns false if password field is empty
     *
     * @return boolean
     */
    public function validateLoginForm()
    {
        $username = $this->getUserName();
        if(empty($username))
        {
            return false;
        }

        $password = $this->getPassword();
        if(empty($password))
        {
            return false;
        }
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
    public function getUserName()
    {
        return $_POST['user'];
    }
    
    /*
     * This method will return value of last name field in contact form
     * 
     * @return string
     */
    public function getPassword()
    {
        return $_POST['password'];
    }
	
}