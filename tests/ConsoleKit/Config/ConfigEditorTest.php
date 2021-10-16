<?php
/**
 * This file is part of the Console-Kit library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/console-helpers/console-kit
 */

namespace Tests\ConsoleHelpers\Config\ConsoleKit;


use ConsoleHelpers\ConsoleKit\Config\ConfigEditor;
use Tests\ConsoleHelpers\ConsoleKit\WorkingDirectoryAwareTestCase;

class ConfigEditorTest extends WorkingDirectoryAwareTestCase
{

	/**
	 * Config file path.
	 *
	 * @var string
	 */
	protected $configPath;

	/**
	 * @before
	 * @return void
	 */
	protected function setupTest()
	{
		$this->configPath = $this->getWorkingDirectory() . '/test_config.json';
	}

	public function testGet()
	{
		$settings = array(
			'setting1' => 'top-value1',
			'group1.setting1' => 'sub-value1',
			'group1.sub-group1.setting1' => 'sub-value2',
		);

		$config_editor = $this->createConfigEditor($settings);

		$this->assertEquals('top-value1', $config_editor->get('setting1'));
		$this->assertEquals('sub-value1', $config_editor->get('group1.setting1'));
		$this->assertEquals(
			array('group1.sub-group1.setting1' => 'sub-value2'),
			$config_editor->get('group1.sub-group1.')
		);
		$this->assertNull($config_editor->get('non-existing-setting'));
		$this->assertEquals('user default', $config_editor->get('non-existing-setting', 'user default'));
	}

	public function testGetAll()
	{
		$settings = array(
			'setting1' => 'top-value1',
			'group1.setting1' => 'sub-value1',
			'group1.sub-group1.setting1' => 'sub-value2',
		);

		$config_editor = $this->createConfigEditor($settings);

		$this->assertEquals($settings, $config_editor->getAll());
	}

	public function testSet()
	{
		$config_editor = $this->createConfigEditor(array());
		$config_editor->set('setting1', 'value1');
		$config_editor->set('top.sub1.sub11', 'one');
		$config_editor->set('top.sub1.sub12', 'two');

		$this->assertEquals('value1', $config_editor->get('setting1'));
		$this->assertEquals('one', $config_editor->get('top.sub1.sub11'));
		$this->assertEquals('two', $config_editor->get('top.sub1.sub12'));
		$this->assertEquals(
			array('top.sub1.sub11' => 'one', 'top.sub1.sub12' => 'two'),
			$config_editor->get('top.sub1.')
		);
	}

	public function testDelete()
	{
		$config_editor = $this->createConfigEditor(array(
			'top.sub1.sub11' => 'one',
			'top.sub1.sub12' => 'two',
		));

		$this->assertEquals('one', $config_editor->get('top.sub1.sub11'));
		$config_editor->set('top.sub1.sub11', null);
		$this->assertEquals('default-value', $config_editor->get('top.sub1.sub11', 'default-value'));
	}

	public function testConfigDefaults()
	{
		$config_editor = new ConfigEditor(
			$this->configPath,
			array('setting_a' => 'value_a', 'setting_b' => 'value_b')
		);

		$this->assertEquals('value_a', $config_editor->get('setting_a'));
		$this->assertEquals('value_b', $config_editor->get('setting_b'));
	}

	public function testConfigStoredDefaultsAreUpgraded()
	{
		$stored_values = array('setting_a' => 'saved_a', 'setting_b' => 'saved_b');
		file_put_contents($this->configPath, json_encode($stored_values));

		$config_editor = new ConfigEditor(
			$this->configPath,
			array('setting_a' => 'default_a', 'setting_b' => 'default_b', 'setting_c' => 'default_c')
		);

		// Confirm, that upgraded settings are immediately available.
		$this->assertEquals('saved_a', $config_editor->get('setting_a'), 'The "setting_a" was preserved.');
		$this->assertEquals('saved_b', $config_editor->get('setting_b'), 'The "setting_b" was preserved.');
		$this->assertEquals('default_c', $config_editor->get('setting_c'), 'The "setting_c" was added with default.');

		// Confirm, that upgrades settings were saved to disk.
		$config_editor = new ConfigEditor($this->configPath);
		$this->assertEquals('saved_a', $config_editor->get('setting_a'), 'The "setting_a" was preserved after save.');
		$this->assertEquals('saved_b', $config_editor->get('setting_b'), 'The "setting_b" was preserved after save.');
		$this->assertEquals('default_c', $config_editor->get('setting_c'), 'The "setting_c" was added with default after save.');
	}

	public function testConfigFileCreation()
	{
		if ( file_exists($this->configPath) ) {
			unlink($this->configPath);
		}

		new ConfigEditor($this->configPath);
		$this->assertFileExists($this->configPath, 'config with defaults is automatically created');
	}

	/**
	 * Creates config editor instance with given settings.
	 *
	 * @param array $settings Settings.
	 *
	 * @return ConfigEditor
	 */
	protected function createConfigEditor(array $settings)
	{
		file_put_contents($this->configPath, json_encode($settings));

		return new ConfigEditor($this->configPath);
	}

}
