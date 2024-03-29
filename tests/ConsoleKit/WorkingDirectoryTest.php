<?php
/**
 * This file is part of the Console-Kit library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/console-helpers/console-kit
 */

namespace Tests\ConsoleHelpers\ConsoleKit;


use ConsoleHelpers\ConsoleKit\WorkingDirectory;
use Yoast\PHPUnitPolyfills\Polyfills\ExpectException;

class WorkingDirectoryTest extends WorkingDirectoryAwareTestCase
{

	use ExpectException;

	/**
	 * @dataProvider incorrectSubFolderDataProvider
	 */
	public function testCreationWithIncorrectSubFolder($sub_folder)
	{
		$this->expectException('\ConsoleHelpers\ConsoleKit\Exception\ApplicationException');
		$this->expectExceptionMessage('The $sub_folder is a path or empty.');

		new WorkingDirectory($sub_folder);
	}

	public static function incorrectSubFolderDataProvider()
	{
		return array(
			'empty sub-folder' => array(''),
			'path sub-folder' => array('a/b'),
		);
	}

	public function testWorkingDirectoryCreation()
	{
		$expected_working_directory = $this->getExpectedWorkingDirectory();

		$actual_working_directory = $this->getWorkingDirectory();
		$this->assertEquals($expected_working_directory, $actual_working_directory);
		$this->assertFileExists($expected_working_directory);

		// If directory is created, when it exists, them this would trigger a warning.
		$this->getWorkingDirectory();
	}

	public function testBrokenLinuxEnvironment()
	{
		$this->expectException('\ConsoleHelpers\ConsoleKit\Exception\ApplicationException');
		$this->expectExceptionMessage('The HOME environment variable must be set to run correctly');

		putenv('HOME=');
		$this->getWorkingDirectory();
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testBrokenWindowsEnvironment()
	{
		$this->expectException('\ConsoleHelpers\ConsoleKit\Exception\ApplicationException');
		$this->expectExceptionMessage('The APPDATA environment variable must be set to run correctly');

		putenv('HOME=');
		define('PHP_WINDOWS_VERSION_MAJOR', 5);

		$this->getWorkingDirectory();
	}

	/**
	 * Returns correct working directory.
	 *
	 * @return string
	 */
	protected function getExpectedWorkingDirectory()
	{
		$sub_folder = array_key_exists('working_directory', $_SERVER) ? $_SERVER['working_directory'] : '';

		return getenv('HOME') . '/' . $sub_folder;
	}

}
