<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2011 Filip Procházka (filip.prochazka@kdyby.org)
 *
 * @license http://www.kdyby.org/license
 */

namespace Kdyby\Doctrine\ORM;

use Doctrine;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\EventManager;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Kdyby;
use Nette;



/**
 * @author Patrik Votoček
 * @author Filip Procházka
 *
 * @property-read Kdyby\DI\Container $context
 * @property-read Diagnostics\Panel $logger
 * @property-read Doctrine\ORM\Configuration $configurator
 * @property-read Doctrine\Common\Annotations\AnnotationReader $annotationReader
 * @property-read Doctrine\ORM\Mapping\Driver\AnnotationDriver $annotationDriver
 * @property-read Doctrine\DBAL\Event\Listeners\MysqlSessionInit $mysqlSessionInitListener
 * @property-read EventManager $eventManager
 * @property-read EntityManager $entityManager
 * @property-read Doctrine\ORM\Tools\SchemaTool $schemaTool
 * @property-read Doctrine\Common\DataFixtures\Loader $fixturesLoader
 * @property-read Doctrine\Common\DataFixtures\Purger\PurgerInterface $fixturesPurger
 * @property-read Doctrine\Common\DataFixtures\Executor\AbstractExecutor $fixturesExecutor
 * @property-read Kdyby\Testing\DataFixturesListener $dataFixturesListener
 */
class Container extends Kdyby\Doctrine\BaseContainer
{

	/** @var array */
	public $params = array(
			'host' => 'localhost',
			'charset' => 'utf8',
			'driver' => 'pdo_mysql',
			'entityDirs' => array('%appDir%', '%kdybyFrameworkDir%'),
			'proxiesDir' => '%tempDir%/proxies',
			'proxyNamespace' => 'Kdyby\Domain\Proxies',
			'listeners' => array(),
		);

	/** @var array */
	private static $types = array(
		'callback' => '\Kdyby\Doctrine\ORM\Types\Callback',
		'password' => '\Kdyby\Doctrine\ORM\Types\Password'
	);



	/**
	 * @param Kdyby\DI\Container $context
	 * @param array $parameters
	 */
	public function __construct(Kdyby\DI\Container $context, $parameters = array())
	{
		parent::__construct($context, $parameters);

		foreach (self::$types as $name => $className) {
			if (!Type::hasType($name)) {
				Type::addType($name, $className);
			}
		}
	}



	/**
	 * @return Diagnostics\Panel
	 */
	protected function createServiceLogger()
	{
		return Diagnostics\Panel::register();
	}



	/**
	 * @return Doctrine\Common\Annotations\AnnotationReader
	 */
	protected function createServiceAnnotationReader()
	{
		$this->registerAnnotationClasses();

		$reader = new Doctrine\Common\Annotations\AnnotationReader();
		$reader->setDefaultAnnotationNamespace('Doctrine\ORM\Mapping\\');
		// $reader->setAnnotationNamespaceAlias('Kdyby\Doctrine\ORM\Mapping\\', 'Kdyby');

		$reader->setIgnoreNotImportedAnnotations(TRUE);
		$reader->setEnableParsePhpImports(FALSE);

		return new Kdyby\Doctrine\Annotations\CachedReader(
			new Doctrine\Common\Annotations\IndexedReader($reader),
			$this->hasService('annotationCache') ? $this->annotationCache : $this->cache
		);
	}



	private function registerAnnotationClasses()
	{
		$loader = Kdyby\Loaders\SplClassLoader::getInstance();
		foreach ($loader->getTypeDirs('Doctrine\ORM') as $dir) {
			AnnotationRegistry::registerFile($dir . '/Mapping/Driver/DoctrineAnnotations.php');
		}

		AnnotationRegistry::registerFile(KDYBY_FRAMEWORK_DIR . '/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');
	}



	/**
	 * @return Mapping\Driver\AnnotationDriver
	 */
	protected function createServiceAnnotationDriver()
	{
		return new Mapping\Driver\AnnotationDriver(
				$this->annotationReader,
				$this->params['entityDirs']
			);
	}



	/**
	 * @return Doctrine\ORM\Configuration
	 */
	protected function createServiceConfiguration()
	{
		$config = new Doctrine\ORM\Configuration;

		// Cache
		$config->setMetadataCacheImpl($this->hasService('metadataCache') ? $this->metadataCache : $this->cache);
		$config->setQueryCacheImpl($this->hasService('queryCache') ? $this->queryCache : $this->cache);

		// Metadata
		$config->setClassMetadataFactoryName('Kdyby\Doctrine\ORM\Mapping\ClassMetadataFactory');
		$config->setMetadataDriverImpl($this->annotationDriver);

		// Proxies
		$config->setProxyDir($this->params['proxiesDir']);
		$config->setProxyNamespace($this->getParam('proxyNamespace', 'Kdyby\Domain\Proxies'));
		if ($this->context->params['productionMode']) {
			$config->setAutoGenerateProxyClasses(FALSE);

		} else {
			$config->setAutoGenerateProxyClasses(TRUE);
			$config->setSQLLogger($this->logger);
		}

		return $config;
	}



	/**
	 * @return Doctrine\DBAL\Event\Listeners\MysqlSessionInit
	 */
	protected function createServiceMysqlSessionInitListener()
	{
		return new Doctrine\DBAL\Event\Listeners\MysqlSessionInit($this->params['charset']);
	}



	/**
	 * @return Mapping\DiscriminatorMapDiscoveryListener
	 */
	protected function createServiceDiscriminatorMapDiscoveryListener()
	{
		return new Mapping\DiscriminatorMapDiscoveryListener($this->annotationReader, $this->annotationDriver);
	}



	/**
	 * @return Mapping\EntityDefaultsListener
	 */
	protected function createServiceEntityDefaultsListener()
	{
		return new Mapping\EntityDefaultsListener();
	}



	/**
	 * @return EventManager
	 */
	protected function createServiceEventManager()
	{
		$evm = new EventManager;
		foreach ($this->params['listeners'] as $listener) {
			$evm->addEventSubscriber($this->getService($listener));
		}

		$evm->addEventSubscriber($this->discriminatorMapDiscoveryListener);
		$evm->addEventSubscriber($this->entityDefaultsListener);
		// $evm->addEventSubscriber(new Kdyby\Media\Listeners\Mediable($this->context));
		return $evm;
	}



	/**
	 * @return EntityManager
	 */
	protected function createServiceEntityManager()
	{
		if (key_exists('driver', $this->params) && $this->params['driver'] == "pdo_mysql" && key_exists('charset', $this->params)) {
			$this->eventManager->addEventSubscriber($this->mysqlSessionInitListener);
		}

		$this->freeze();
		return EntityManager::create($this->params, $this->configuration, $this->eventManager);
	}



	/**
	 * @return Doctrine\ORM\Tools\SchemaTool
	 */
	protected function createServiceSchemaTool()
	{
		return new Doctrine\ORM\Tools\SchemaTool($this->getEntityManager());
	}



	/**
	 * @return Doctrine\Common\DataFixtures\Loader
	 */
	protected function createServiceFixturesLoader()
	{
		return new Doctrine\Common\DataFixtures\Loader();
	}



	/**
	 * @return Doctrine\Common\DataFixtures\Purger\PurgerInterface
	 */
	protected function createServiceFixturesPurger()
	{
		return new Doctrine\Common\DataFixtures\Purger\ORMPurger($this->getEntityManager());
	}



	/**
	 * @return Doctrine\Common\DataFixtures\Executor\AbstractExecutor
	 */
	protected function createServiceFixturesExecutor()
	{
		return new Doctrine\Common\DataFixtures\Executor\ORMExecutor($this->getEntityManager(), $this->fixturesPurger);
	}



	/**
	 * @return Kdyby\Testing\DataFixturesListener
	 */
	protected function createServiceDataFixturesListener()
	{
		return new Kdyby\Testing\DataFixturesListener($this->fixturesLoader, $this->fixturesExecutor);
	}



	/**
	 * @return EntityManager
	 */
	public function getEntityManager()
	{
		return $this->entityManager;
	}



	/**
	 * @param string $entityName
	 * @return EntityRepository
	 */
	public function getRepository($entityName)
	{
		return $this->getEntityManager()->getRepository($entityName);
	}



	/**
	 * @param string $className
	 * @return bool
	 */
	public function isManaging($className)
	{
		try {
			$this->getEntityManager()->getClassMetadata($className);
			return TRUE;

		} catch (Doctrine\ORM\Mapping\MappingException $e) {
			return FALSE;
		}
	}

}