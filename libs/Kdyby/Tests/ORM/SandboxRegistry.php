<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2012 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Kdyby\Tests\ORM;

use Doctrine;
use Doctrine\Common\DataFixtures;
use Doctrine\ORM\EntityManager;
use Kdyby;
use Kdyby\Doctrine\Dao;
use Kdyby\Doctrine\Mapping\ClassMetadata;
use Kdyby\Tests\OrmTestCase;
use Nette;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class SandboxRegistry extends Kdyby\Doctrine\Registry
{

	/** @var \Kdyby\Tests\TestCase|\Kdyby\Tests\OrmTestCase */
	private $currentTest;

	/** @var \Kdyby\Tests\ORM\SandboxConfigurator */
	private $configurator;

	/** @var \Kdyby\Doctrine\Dao[] */
	private $daoMocks = array();

	/** @var \Kdyby\Doctrine\Mapping\ClassMetadata[] */
	private $metaMocks = array();



	/**
	 * @param \Kdyby\Tests\TestCase $test
	 */
	public function setCurrentTest(Kdyby\Tests\TestCase $test)
	{
		$this->currentTest = $test;
	}



	/**
	 * @param \Kdyby\Tests\ORM\SandboxConfigurator $configurator
	 */
	public function setConfigurator(SandboxConfigurator $configurator)
	{
		$this->configurator = $configurator;
	}



	/**
	 * @param string $name
	 *
	 * @throws \PHPUnit_Framework_SkippedTestError
	 * @throws \Kdyby\InvalidStateException
	 */
	public function requireConfiguredManager($name = "default")
	{
		if (!$this->currentTest) {
			$method = get_called_class() . '::setCurrentTest()';
			throw new Kdyby\InvalidStateException("Please provide a TestCase instance using method $method.");
		}

		try {
			$this->getEntityManager($name);

		} catch (Kdyby\Doctrine\PDOException $e) {
			throw $e;

		} catch (Doctrine\ORM\ORMException $e) {
			throw $e;

		} catch (Doctrine\DBAL\Schema\SchemaException $e) {
			throw $e;

		} catch (Doctrine\Common\Annotations\AnnotationException $e) {
			throw $e;

		} catch (\Exception $e) {
			Nette\Diagnostics\Debugger::log($e);
			$configFile = $this->currentTest->getContext()->expand('%appDir%/config.orm.neon');
			$this->currentTest->markTestSkipped(
				"TestCase requires configured EntityManager named $name. " .
				"To run test properly, you have to provide valid database storage credentials in config file $configFile, " .
				"in section 'orm: entityManagers: $name:'. \nDoctrine cries: " . $e->getMessage()
			);
		}
	}



	/**
	 * @param string $emName
	 *
	 * @throws \Kdyby\InvalidStateException
	 * @return \Doctrine\ORM\EntityManager
	 */
	protected function createEntityManager($emName)
	{
		if (!$this->currentTest instanceof OrmTestCase) {
			throw new Kdyby\InvalidStateException("Your test case must be descendant of Kdyby\\Tests\\OrmTestCase to be able to use Doctrine.");
		}

		// create manager
		$service = $this->entityManagers[$emName];
		$em = $this->container->getService($service);

		// configure entities, schema, proxies
		$this->configurator->configureManager($em);

		// load fixtures
		$fixtureLoader = new DataFixturesLoader(
			$this->container->getService($service . '.dataFixtures.loader'),
			$this->container->getService($service . '.dataFixtures.executor')
		);
		$fixtureLoader->loadFixtures($this->currentTest);

		// return
		return $em;
	}



	/**
	 * @return \Doctrine\ORM\EntityManager[]
	 */
	public function getEntityManagers()
	{
		$ems = array();
		foreach ($this->entityManagers as $name => $service) {
			if (!$this->container->isCreated($service)) {
				// handle all necessary stuff
				$ems[$name] = $this->createEntityManager($name);
				continue;
			}

			$ems[$name] = $this->container->getService($service);
		}

		return $ems;
	}



	/**
	 * @param string $name
	 *
	 * @throws \Kdyby\InvalidArgumentException
	 * @return \Doctrine\ORM\EntityManager
	 */
	public function getEntityManager($name = NULL)
	{
		if ($name === NULL) {
			$name = $this->defaultEntityManager;
		}

		if (!isset($this->entityManagers[$name])) {
			throw new Kdyby\InvalidArgumentException('Doctrine EntityManager named "' . $name . '" does not exist.');
		}

		$service = $this->entityManagers[$name];
		if (!$this->container->isCreated($service)) {
			// handle all necessary stuff
			return $this->createEntityManager($name);
		}

		return $this->container->getService($service);
	}



	/**
	 * Gets the EntityRepository for an entity.
	 *
	 * @param string $entityName        The name of the entity.
	 * @param string $entityManagerName The entity manager name (null for the default one)
	 *
	 * @throws \Kdyby\InvalidArgumentException
	 * @return \Doctrine\ORM\EntityRepository
	 */
	public function getRepository($entityName, $entityManagerName = NULL)
	{
		if (!class_exists($entityName = is_object($entityName) ? get_class($entityName) : $entityName)) {
			throw new Kdyby\InvalidArgumentException("Expected entity name, '$entityName' given");
		}

		if (isset($this->daoMocks[$entityManagerName][$lEntityName = strtolower($entityName)])) {
			return $this->daoMocks[$entityManagerName][$lEntityName];
		}

		return parent::getRepository($entityName, $entityManagerName);
	}



	/**
	 * @param string $entityName
	 * @param \Kdyby\Doctrine\Dao $dao
	 * @param string $entityManagerName
	 *
	 * @throws \Kdyby\InvalidArgumentException
	 * @return \Kdyby\Doctrine\Dao
	 */
	public function setRepository($entityName, Dao $dao, $entityManagerName = NULL)
	{
		if (!class_exists($entityName = is_object($entityName) ? get_class($entityName) : $entityName)) {
			throw new Kdyby\InvalidArgumentException("Expected entity name, '$entityName' given");
		}

		return $this->daoMocks[$entityManagerName][strtolower($entityName)] = $dao;
	}



	/**
	 * Gets the Dao for an entity.
	 *
	 * @param string $entityName        The name of the entity.
	 * @param string $entityManagerName The entity manager name (null for the default one)
	 *
	 * @throws \Kdyby\InvalidArgumentException
	 * @return \Kdyby\Doctrine\Dao
	 */
	public function getDao($entityName, $entityManagerName = NULL)
	{
		if (!class_exists($entityName = is_object($entityName) ? get_class($entityName) : $entityName)) {
			throw new Kdyby\InvalidArgumentException("Expected entity name, '$entityName' given");
		}

		if (isset($this->daoMocks[$entityManagerName][$lEntityName = strtolower($entityName)])) {
			return $this->daoMocks[$entityManagerName][$lEntityName];
		}

		return parent::getDao($entityName, $entityManagerName);
	}



	/**
	 * @param string $entityName
	 * @param \Kdyby\Doctrine\Dao $dao
	 * @param string $entityManagerName
	 *
	 * @throws \Kdyby\InvalidArgumentException
	 * @return \Kdyby\Doctrine\Dao
	 */
	public function setDao($entityName, Dao $dao, $entityManagerName = NULL)
	{
		if (!class_exists($entityName = is_object($entityName) ? get_class($entityName) : $entityName)) {
			throw new Kdyby\InvalidArgumentException("Expected entity name, '$entityName' given");
		}

		return $this->daoMocks[$entityManagerName][strtolower($entityName)] = $dao;
	}



	/**
	 * Gets the Dao for an entity.
	 *
	 * @param string $entityName        The name of the entity.
	 * @param string $entityManagerName The entity manager name (null for the default one)
	 *
	 * @throws \Kdyby\InvalidArgumentException
	 * @return \Kdyby\Doctrine\Mapping\ClassMetadata
	 */
	public function getClassMetadata($entityName, $entityManagerName = NULL)
	{
		if (!class_exists($entityName = is_object($entityName) ? get_class($entityName) : $entityName)) {
			throw new Kdyby\InvalidArgumentException("Expected entity name, '$entityName' given");
		}

		if (isset($this->metaMocks[$entityManagerName][$lEntityName = strtolower($entityName)])) {
			return $this->metaMocks[$entityManagerName][$lEntityName];
		}

		return $this->getEntityManager($entityManagerName)->getClassMetadata($entityName);
	}



	/**
	 * @param string $entityName
	 * @param \Kdyby\Doctrine\Mapping\ClassMetadata $meta
	 * @param string $entityManagerName
	 *
	 * @throws \Kdyby\InvalidArgumentException
	 * @return \Kdyby\Doctrine\Mapping\ClassMetadata
	 */
	public function setClassMetadata($entityName, ClassMetadata $meta, $entityManagerName = NULL)
	{
		if (!class_exists($entityName = is_object($entityName) ? get_class($entityName) : $entityName)) {
			throw new Kdyby\InvalidArgumentException("Expected entity name, '$entityName' given");
		}

		return $this->metaMocks[$entityManagerName][strtolower($entityName)] = $meta;
	}

}
