<?php
/**
 * Ensures the error reporting level includes E_STRICT on all tests
 */
class PHPUnit_TestListener_Error_Reporting_Strict implements PHPUnit_Framework_TestListener
{
    private $levels = [];
    private $reader = null;

    public function __construct()
    {
        //$this->reader = Diesel::Annotations_Reader();
    }
    /**
     * An error occurred.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // TODO: Implement addError() method.
    }

	/**
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /**
     * A failure occurred.
     *
     * @param PHPUnit_Framework_Test                 $test
     * @param PHPUnit_Framework_AssertionFailedError $e
     * @param float                                  $time
     */
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        // TODO: Implement addFailure() method.
    }

    /**
     * Incomplete test.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // TODO: Implement addIncompleteTest() method.
    }

    /**
     * Skipped test.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     * @since  Method available since Release 3.0.0
     */
    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // TODO: Implement addSkippedTest() method.
    }

    /**
     * A test suite started.
     *
     * @param PHPUnit_Framework_TestSuite $suite
     * @since  Method available since Release 2.2.0
     */
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        // TODO: Implement startTestSuite() method.
        /*$test_class =$suite->getName();
        if (class_exists($test_class)) {
            $for_class = $this->reader->read_from_class($test_class);
            foreach ($for_class->declared_annotations() as $annotation) {
                if ($annotation->name() === 'not_strict') {
                    $this->push_reporting_level($this->not_strict_level());

                    return;
                }
            }
        }
        $this->push_reporting_level($this->strict_level());
        if (count($this->_suites) === 0)
        {
         PHP_Timer::start();
        }*/

        $this->_suites[] = $suite->getName();
        //$this->objectLogger->addData($this->handle,"TestSuite ".$suite->getName()." started.\n");

        printf("TestSuite '%s' started.\n", $suite->getName());
    }

    /**
     * A test suite ended.
     *
     * @param PHPUnit_Framework_TestSuite $suite
     * @since  Method available since Release 2.2.0
     */
    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        $this->pop_reporting_level();
    }

    /**
     * @return int
     */
    private function strict_level()
    {
        return E_ALL;
    }

    /**
     * @return int
     */
    private function not_strict_level()
    {
        return E_ALL & ~E_NOTICE & ~E_STRICT;
    }

    /**
     * A test started.
     *
     * @param PHPUnit_Framework_Test $test
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        /*$test_class = get_class($test);
        $annotations_for_class = $this->reader->read_from_class($test_class);

        // If the test has data providers, the testname is the method name plus extra information
        $test_name = $test->getName();
        $test_name_split = explode(' ', $test_name);
        $method_name = $test_name_split[0];

        if (method_exists($test_class, $method_name)) {
            $method_annotations = $annotations_for_class->method_annotations($method_name);
            foreach ($method_annotations as $annotation) {
                if ($annotation->name() === 'not_strict') {
                    $this->push_reporting_level($this->not_strict_level());

                    return;
                }
            }
        }
        $this->push_reporting_level($this->strict_level());*/
    }

    private function push_reporting_level($level)
    {
        $old_level = error_reporting($level);
        array_push($this->levels, $old_level);

    }

    private function pop_reporting_level()
    {
        if ($this->levels) {
            $level = array_pop($this->levels);
            error_reporting($level);
        }
    }

    /**
     * A test ended.
     *
     * @param PHPUnit_Framework_Test $test
     * @param float                  $time
     */
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        $this->pop_reporting_level();
    }
}
