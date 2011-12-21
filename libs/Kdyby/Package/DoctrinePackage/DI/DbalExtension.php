<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2011 Filip Procházka (filip.prochazka@kdyby.org)
 *
 * @license http://www.kdyby.org/license
 */

namespace Kdyby\Package\DoctrinePackage\DI;

use Kdyby;
use Nette;
use Nette\DI\ContainerBuilder;
use Nette\Utils\Validators;



/**
 * DbalExtension is an extension for the Doctrine DBAL library.
 *
 * @author Filip Procházka <filip.prochazka@kdyby.org>
 */
class DbalExtension extends Kdyby\Config\CompilerExtension
{

	/**
	 * @var array
	 */
	public $connectionDefaults = array(
		'dbname' => NULL,
		'host' => 'localhost',
		'port' => 3306,
		'user' => NULL,
		'password' => NULL,
		'driver' => 'pdo_mysql',
		'driverClass' => NULL,
		'options' => NULL,
		'path' => NULL,
		'memory' => NULL,
		'unix_socket' => NULL,
		'wrapperClass' => NULL,
		'logging' => '%kdyby_debug%',
		'platformService' => NULL,
	);

	/**
	 * @var array
	 */
	protected $defaultTypes = array(
		Kdyby\Doctrine\Type::CALLBACK => 'Kdyby\Doctrine\Types\Callback',
		Kdyby\Doctrine\Type::PASSWORD => 'Kdyby\Doctrine\Types\Password'
	);



	/**
	 * dbal:
	 * 	dbname: database
	 * 	user: root
	 * 	password: 123
	 *
	 *
	 * @param \Nette\DI\ContainerBuilder $container
	 * @param array $config
	 */
	public function loadConfiguration(ContainerBuilder $container, array $config)
	{
		$connections = isset($config['connections']) ? $config['connections'] : array('default' => $config);

		if (empty($config['defaultConnection'])) {
			$keys = array_keys($connections);
			$config['defaultConnection'] = reset($keys);
		}
		$container->parameters['doctrine_defaultConnection'] = $config['defaultConnection'];

		// Validators::assertFields($config['types'], 'class')
		$container->parameters['doctrine_dbal_connectionFactory_types'] = $config['types'] + $this->defaultTypes;

		foreach (array_keys($connections) as $name) {
			$container->parameters['doctrine_connections'][$name] = 'doctrine_dba_' . $name . 'Connection';
		}

		// load connections
		foreach ($connections as $name => $connection) {
			$connection['name'] = $name;
			$this->loadConnection($container, $connection);
		}

		$this->addAlias('doctrine_dbal_connection', 'doctrine_dbal_' . $config['defaultConnection'] . 'Connection');
		$this->addAlias('doctrine_dbal_eventManager', 'doctrine_dbal_' . $config['defaultConnection'] . 'Connection_eventManager');
	}



	/**
	 * Loads a configured DBAL connection.
	 * @param \Nette\DI\ContainerBuilder $container
	 * @param array $config
	 */
	protected function loadConnection(ContainerBuilder $container, array $config)
	{
		$connectionName = 'doctrine_dbal_' . $config['name'] . 'Connection';

		// configuration
		$configuration = $container->addDefinition($connectionName . '_configuration')
			->setClass('Doctrine\DBAL\Configuration');

		// logging
		if (isset($config['logging'])) {
			Validators::assertField($config, 'logging', 'bool');

			$container->addDefinition($connectionName . '_logger')
				->setClass('Kdyby\Doctrine\Diagnostics\Panel')
				->setFactory('Kdyby\Doctrine\Diagnostics\Panel::register');

			if ($config['logging']) {
				$configuration->addSetup('setSQLLogger', array('@' . $connectionName . '_logger'));
			}
		}

		// event manager
		$container->addDefinition($connectionName . '_eventManager')
			->setClass('Doctrine\Common\EventManager');

		// options
		$options = self::getOptions($config, $this->connectionDefaults);

		// charset
		$this->loadConnectionCharset($container, $options, $connectionName);

		// connection factory
		$container->addDefinition($connectionName . '_factory')
			->setClass('Kdyby\Package\DoctrinePackage\ConnectionFactory', array('%doctrine_dbal_connectionFactory_types%'));

		$mappingTypes = array();
		if (isset($config['mapping_types'])) {
			Validators::assertField($config, 'mapping_types', 'array');
			$mappingTypes = $config['mapping_types'];
		}

		// connection
		$container->addDefinition($connectionName)
			->setFactory('@' . $connectionName . '_factory::createConnection', array(
				$options,
				'@' . $connectionName . '_configuration',
				'@' . $connectionName . '_eventManager',
				$mappingTypes
			));
	}



	/**
	 * @param \Nette\DI\ContainerBuilder $container
	 * @param array $config
	 * @param string $connectionName
	 */
	protected function loadConnectionCharset(ContainerBuilder $container, array $config, $connectionName)
	{
		if ($this->connectionUsesMysqlDriver($config)) {
			$container->addDefinition($connectionName . '_events_mysqlSessionInit')
				->setClass('Doctrine\DBAL\Event\Listeners\MysqlSessionInit', array($config['charset']));
		}
	}



	/**
	 * @param array $connection
	 *
	 * @return boolean
	 */
	protected function connectionUsesMysqlDriver(array $connection)
	{
		return (isset($connection['driver']) && stripos($connection['driver'], 'mysql') !== FALSE)
			|| (isset($connection['driverClass']) && stripos($connection['driverClass'], 'mysql') !== FALSE);
	}

}
