<?php
// set up AT and require the autoloader
error_reporting(E_ALL);
$workingDir = realpath(dirname(__DIR__));

// Include the Composer autoloader

//include "$workingDir/selenium/lib/vendor/autoload.php";

include "$workingDir/selenium/lib/phactory/Inflector.php";
include "$workingDir/selenium/lib/phactory/Sql/DbUtil/AbstractDbUtil.php";
include "$workingDir/selenium/lib/phactory/Sql/DbUtil/PgsqlUtil.php";
include "$workingDir/selenium/lib/phactory/Sql/DbUtil/MysqlUtil.php";
include "$workingDir/selenium/lib/phactory/Sql/DbUtil/SqliteUtil.php";
include "$workingDir/selenium/lib/phactory/Sql/Association.php";
include "$workingDir/selenium/lib/phactory/Sql/Blueprint.php";
include "$workingDir/selenium/lib/phactory/Sql/DbUtilFactory.php";
include "$workingDir/selenium/lib/phactory/Sql/Inflector.php";
include "$workingDir/selenium/lib/phactory/Sql/Logger.php";
include "$workingDir/selenium/lib/phactory/Sql/Phactory.php";
include "$workingDir/selenium/lib/phactory/Sql/Row.php";
include "$workingDir/selenium/lib/phactory/Sql/Sequence.php";
include "$workingDir/selenium/lib/phactory/Sql/Table.php";




/********************Automation library files***********/

include "$workingDir/selenium/lib/HelperFunctions.php";
include "$workingDir/selenium/lib/Base_Page_Object.php";
include "$workingDir/selenium/lib/vendor/phpunit-selenium/PHPUnit/Extensions/Selenium2TestCase/ScreenshotListener.php";

// Load all the page object classes
foreach (glob("$workingDir/selenium/Page_objects/Browser_*") as $file_name)
{
	include $file_name;
}
