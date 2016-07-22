<?php


$workingDir = realpath(dirname(__DIR__));
require_once "$workingDir/bootstrap.php";

/**
 * Class Browser_Base_Test_Case
 * All browser-based tests should inherit from this
 */
Class Browser_Base_Test_Case extends PHPUnit_Extensions_Selenium2TestCase
{
    /** protected @var */
    protected $pdo;
    protected $phactory;
    // protected static $timeout_wait_until = 10000;
    // protected static $failed_script_retry = 1;
    // protected $html_dump_path = 'D:\xampp\htdocs\TDD\test\selenium\htmldump';
    // protected static $failure_screenshot_dir = 'D:\xampp\htdocs\TDD\test\selenium\screenshot';
	
	use HelperFunctions;
	
    protected function setUp()
    {
        //$this->setBrowser('firefox');
        //$this->setPort(4444);
        $this->setBrowser('chrome');
        $this->setPort(9515);
        $this->setBrowserUrl('http://localhost/TDD/src/contact_form/');
        $this->set_up_page_objects();
        $this->set_up_phactory_objects();
    }

    /*
     * Each page object needs to know the test class it is acting on
     */
    protected function set_up_page_objects()
    {
            $this->configure_page_objects_with_test($this);
    }
    
    /*
     * Setup phactory DB objects on the run
     */
    protected function set_up_phactory_objects()
    {
        $this->pdo = new \PDO('mysql:host=127.0.0.1; dbname=testdb', 'root', '');

        $this->phactory = new Phactory($this->pdo);
        $this->phactory->setConnection($this->pdo);
        $this->phactory->define('user_ph', array('name' => 'Test User $n', 'pass' => 'test1234'));
    }

    /*
     * Informs the page objects about the test class they will act on
     * @param Base_Browser_Test_Case|null $test_value
     */
    protected function configure_page_objects_with_test($test_value)
    {
            $workingDir = realpath(dirname(__DIR__));
            foreach(new DirectoryIterator("$workingDir/Page_objects/") as $file)
            {
                $matches = [];
                if (preg_match('/^(Browser.*)\.php/', $file->getFilename(), $matches))
                {
                        $class_name = $matches[1];
                        $class_name::set_test($test_value);
                }
            }
    }

    
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        //$this->phactory->reset();
		//$this->phactory->getConnection()->exec("DROP TABLE `users_ph`");

        //$this->pdo->exec("DROP TABLE `users`");
    }
    
    /*
     * @return string
     */
    protected function root_screenshot_path()
    {
        return self::$failure_screenshot_dir;
    }
    
    /*
     * Override runBare function of PHPUnit_Framework_TestCase class to retry failed test scripts and capture screen shot of failure
     */
    public function runBare()
    {
        $streamer = str_repeat('=', 70);

        for($script_retry = 0; $script_retry < self::$failed_script_retry; $script_retry++)
        {
            $test_name = $this->getName();

            try
            {
                echo "$streamer\nRunning testcase $test_name on attempt: " . ($script_retry + 1) . "\n$streamer\n";
                parent::runBare();
                $this->clean_up_screenshots();
                return;
            }

            catch (Exception $e)
            {
                echo "$streamer\nTestcase $test_name failed on attempt: " . ($script_retry + 1) . "\n$streamer\n";
                echo $e->getMessage() . "\n$streamer\n" . $e->getTraceAsString() . "\n$streamer\n";
            }
        }

        if($e)
        {
            $this->screenshot();
            throw $e;
        }
    }

}