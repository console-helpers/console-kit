<?php
/**
 * This file is part of the Console-Kit library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/console-helpers/console-kit
 */

namespace Tests\ConsoleHelpers\ConsoleKit\Helper;


use ConsoleHelpers\ConsoleKit\Helper\ContainerHelper;
use ConsoleHelpers\ConsoleKit\Container;
use PHPUnit\Framework\TestCase;

class ContainerHelperTest extends TestCase
{

	/**
	 * Container helper
	 *
	 * @var ContainerHelper
	 */
	protected $containerHelper;

	/**
	 * Container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * @before
	 * @return void
	 */
	protected function setupTest()
	{
		$this->container = $this->prophesize('ConsoleHelpers\\ConsoleKit\\Container')->reveal();
		$this->containerHelper = new ContainerHelper($this->container);
	}

	public function testGetContainer()
	{
		$this->assertSame($this->container, $this->containerHelper->getContainer());
	}

	public function testGetName()
	{
		$this->assertEquals('container', $this->containerHelper->getName());
	}

}
