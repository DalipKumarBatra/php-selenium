<?php
trait HelperFunctions
{
	protected static $timeout_wait_until = 10000;
    protected static $failed_script_retry = 1;
    protected $html_dump_path = 'D:\xampp\htdocs\tddDemo\testsuite\selenium\htmldump';
    protected static $failure_screenshot_dir = 'D:\xampp\htdocs\tddDemo\testsuite\selenium\screenshot';
	/*
     * To replace existing value of form field with provided value
     *
     * @param $selector
     * @param $updated_value
     */
    public function clear_and_fill_new_value($selector, $updated_value)
    {
        try
        {
            $this->execute(['script' => "document.querySelector('".$selector."').value = '$updated_value';", 'args' => []]);
        }
        catch (PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e)
        {}
    }
    
    /**
    * @param $selector
    * @return null|string
    */
    public function get_inner_text($selector)
    {
        $value = null;
        try
        {
            $value = $this->execute(['script' => "return document.querySelector('$selector').innerHTML;", 'args' => []]);
        }
        catch (PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e)
        {}
        return $value;
    }

    /*
     * @param string $title
     */
    public function wait_for_title($title)
    {
        $self = $this;
        $this->waitUntil(function() use($self, $title)
        {
            return ($self->title() == $title)? true : null;
        }, self::$timeout_wait_until);
    }

    /*
     * @param string $selector
     */
    public function wait_for_element($selector)
    {
        $self = $this;
        $this->waitUntil(function() use($self, $selector)
        {
            $result = null;
            try
            {
                    $result = $self->byCssSelector($selector);
            }
            catch (PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e)
            {}
            return $result;
        }, self::$timeout_wait_until);
    }

    /*
     * @param string $title_part
     */
    public function assert_title_contains($title_part)
    {
        $this->assertTrue(strpos($this->title(), $title_part) !== false);
    }
    
    /*
     * @return string
     */
    protected function screenshot_path()
    {
        $path = self::$failure_screenshot_dir . "/" . get_called_class() . "/" . $this->getName();
        if (!file_exists($path)) {
             mkdir($path, 0777, true);
        }
        return $path;
    }
    
    public function screenshot()
    {
        $path = $this->screenshot_path();
        $screenshot_listener = new PHPUnit_Extensions_Selenium2TestCase_ScreenshotListener($path);
        $screenshot_listener->addError($this, new Exception(''), 1);
    }
	
	public function dump_html()
	{
		$source = $this->source();
		file_put_contents($this->html_dump_path . "/source.html", $source);
	}

    /*
     * @param $directory
     * @param bool $empty_directories_only
     */
    protected function delete_items_from_path($directory, $empty_directories_only = true)
    {
        $dir_iterator = new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($iterator as $path) {
            if ($path->isDir()) {
                $this->delete_dir_if_empty($path->getPathname());
            }
            else if (!$empty_directories_only) {
                var_dump($path->getPathname());
                unlink($path->getPathname());
            }
        }
        $this->delete_dir_if_empty($directory);
    }

    /*
     * @param $dir
     */
    function delete_dir_if_empty($dir)
    {
        if (count(scandir($dir)) == 2) {
            rmdir($dir);
        }
    }
    
    protected function clean_up_screenshots()
    {
        $this->delete_items_from_path($this->screenshot_path(), false);
        $this->delete_items_from_path($this->root_screenshot_path(), true);
    }
}