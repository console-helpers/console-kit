<?php
/**
 * This file is part of the Console-Kit library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/console-helpers/console-kit
 */

namespace ConsoleHelpers\ConsoleKit;


use Stecman\Component\Symfony\Console\BashCompletion\CompletionCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{

	/**
	 * Running command.
	 *
	 * @var Command
	 */
	private $_runningCommand;

	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	protected $dic;

	/**
	 * Creates application.
	 *
	 * @param Container $container Container.
	 */
	public function __construct(Container $container)
	{
		$this->dic = $container;

		parent::__construct($this->dic['app_name'], $this->dic['app_version']);

		$helper_set = $this->getHelperSet();
		$helper_set->set($this->dic['container_helper']);

		$that = $this;
		$this->dic['helper_set'] = function () use ($that) {
			return $that->getHelperSet();
		};
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getDefaultCommands()
	{
		$default_commands = parent::getDefaultCommands();
		$default_commands[] = new CompletionCommand();

		return $default_commands;
	}

	/**
	 * @inheritDoc
	 */
	protected function doRunCommand(Command $command, InputInterface $input, OutputInterface $output)
	{
		try {
			$this->_runningCommand = $command;

			return parent::doRunCommand($command, $input, $output);
		}
		finally {
			$this->_runningCommand = null;
		}
	}

	/**
	 * Determines if this is topmost command.
	 *
	 * @param Command $command Command.
	 *
	 * @return boolean
	 */
	public function isTopmostCommand(Command $command)
	{
		return is_object($this->_runningCommand) && $command->getName() === $this->_runningCommand->getName();
	}

	/**
	 * {@inheritdoc}
	 */
	public function run(InputInterface $input = null, OutputInterface $output = null)
	{
		if ( !isset($input) ) {
			$input = $this->dic['input'];
		}

		if ( !isset($output) ) {
			$output = $this->dic['output'];
		}

		return parent::run($input, $output);
	}

	/**
	 * Detects, when we're inside PHAR file.
	 *
	 * @return boolean
	 */
	protected function isPharFile()
	{
		return strpos(__DIR__, 'phar://') === 0;
	}

}
