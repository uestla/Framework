<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2012 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Kdyby\Doctrine\Audit;

use Nette;



/**
 * @author Benjamin Eberlei <eberlei@simplethings.de>
 * @author Filip Procházka <filip@prochazka.su>
 *
 * @method string getPrefix()
 * @method string getSuffix()
 * @method string getTableName()
 * @method string getCurrentUser()
 * @method setCurrentUser(string $username)
 */
class AuditConfiguration extends Nette\Object
{

	const REVISION_ID = '_revision';
	const REVISION_PREVIOUS = '_revision_previous';

	/**
	 * @var string
	 */
	public $prefix;

	/**
	 * @var string
	 */
	public $suffix;

	/**
	 * @var string
	 */
	public $tableName;

	/**
	 * @var string
	 */
	public $currentUser;



	/**
	 * @param $name
	 * @param $args
	 *
	 * @return mixed
	 */
	public function __call($name, $args)
	{
		return Nette\ObjectMixin::callProperty($this, $name, $args);
	}

}
