<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2012 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Kdyby\Doctrine\Types;

use Doctrine;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Kdyby;
use Nette;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class Callback extends Type
{

	/**
	 * @param Nette\Callback $value
	 * @param AbstractPlatform $platform
	 * @return string
	 */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string)$value;
    }



	/**
	 * @param string $value
	 * @param AbstractPlatform $platform
	 * @return Nette\Callback
	 */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value ? new Nette\Callback($value) : NULL;
    }



	/**
	 * @param array $fieldDeclaration
	 * @param AbstractPlatform $platform
	 * @return mixed
	 */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }



	/**
	 * @param AbstractPlatform $platform
	 * @return int
	 */
    public function getDefaultLength(AbstractPlatform $platform)
    {
        return $platform->getVarcharDefaultLength();
    }



	/**
	 * @return string
	 */
    public function getName()
    {
        return 'callback';
    }

}
