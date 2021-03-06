<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2012 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Kdyby\Migrations;

use Kdyby;
use Nette;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class SqlVersion extends Version
{

	/** @var string */
	private $file;



	/**
	 * @param \Kdyby\Migrations\History $history
	 * @param string $file
	 */
	public function __construct(History $history, $file)
	{
		parent::__construct($history, substr(pathinfo($file, PATHINFO_FILENAME), -14));
		$this->file = $file;
	}



	/**
	 * @return mixed
	 */
	public function getFile()
	{
		return $this->file;
	}



	/**
	 * @return boolean
	 */
	public function isReversible()
	{
		return FALSE;
	}



	/**
	 * @param \Kdyby\Migrations\MigrationsManager $manager
	 * @param boolean $commit
	 *
	 * @return array
	 * @throws \Kdyby\Migrations\MigrationException
	 */
	public function up(MigrationsManager $manager, $commit = TRUE)
	{
		$dump = new Tools\SqlDump($this->file);

		$this->setOutputWriter($manager->getOutputWriter());
		$connection = $manager->getConnection();
		$connection->beginTransaction();

		try {
			$start = microtime(TRUE);
			$this->message('<comment>-></comment> executing sql dump');

			// migration
			foreach ($dump as $query) {
				if ($commit) {
					$connection->executeQuery($query);
				}
			}

			$this->markMigrated($commit);
			$this->time = microtime(TRUE) - $start;

			$time = number_format($this->time * 1000, 1, '.', ' ');
			$this->message('<info>++</info> migrated <comment>' . $this->getVersion() . '</comment> in ' . $time . ' ms');

			$connection->commit();
			return array();

		} catch (\Exception $e) {
			$this->message('<error>Migration ' . $this->getVersion() . ' failed. ' . $e->getMessage() . '</error>');
			$connection->rollback();
			throw $e;
		}
	}



	/**
	 * @param \Kdyby\Migrations\MigrationsManager $manager
	 * @param boolean $commit
	 *
	 * @throws \Kdyby\Migrations\MigrationException
	 */
	public function down(MigrationsManager $manager, $commit = TRUE)
	{
		throw new MigrationException('Version ' . $this->getVersion() . ' is irreversible.');
	}



	/**
	 * @param \Kdyby\Migrations\MigrationsManager $manager
	 * @param boolean $up
	 *
	 * @return array
	 */
	public function dump(MigrationsManager $manager, $up = TRUE)
	{
		if (!$up) {
			$this->down($manager);
		}

		$dump = new Tools\SqlDump($this->file);
		return $dump->getSqls();
	}



	/**
	 * @param string $sql
	 * @param array $params
	 * @param array $types
	 *
	 * @throws \Kdyby\NotSupportedException
	 */
	public function addSql($sql, array $params = array(), array $types = array())
	{
		throw new Kdyby\NotSupportedException;
	}

}
