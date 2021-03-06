<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2012 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Kdyby\Security\RBAC;

use Doctrine\ORM\Mapping as ORM;
use Kdyby;
use Kdyby\Security\Identity;
use Nette;



/**
 * @author Filip Procházka <filip@prochazka.su>
 * @ORM\Entity
 * @Kdyby\Doctrine\Mapping\DiscriminatorEntry(name="user")
 */
class UserPermission extends BasePermission
{
	/**
	 * @var Identity
	 * @ORM\ManyToOne(targetEntity="Kdyby\Security\Identity")
	 * @ORM\JoinColumn(name="identity_id", referencedColumnName="id")
	 */
	private $identity;



	/**
	 * @param Privilege $privilege
	 * @param Identity $identity
	 */
	public function __construct(Privilege $privilege, Nette\Security\IRole $identity)
	{
		if (!$identity instanceof Identity) {
			throw new Kdyby\InvalidArgumentException("Given role is not instanceof Kdyby\\Security\\Identity, '" . get_class($identity) . "' given");
		}

		if ($this->identity !== NULL) {
			throw new Kdyby\InvalidStateException("Association with identity is immutable.");
		}

		$this->identity = $identity;
		parent::__construct($privilege);
	}



	/**
	 * @return Identity
	 */
	public function getIdentity()
	{
		return $this->identity;
	}



	/**
	 * @return Identity
	 */
	public function getRole()
	{
		return $this->identity;
	}



	/**
	 * @return string
	 */
	protected function getRoleId()
	{
		return $this->getRole()->getRoleIds();
	}

}
