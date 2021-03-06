<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2012 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Kdyby\Console;

use Kdyby;
use Kdyby\Packages\PackageManager;
use Nette;
use Symfony;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class PackageManagerHelper extends Symfony\Component\Console\Helper\Helper
{

	/** @var \Kdyby\Packages\PackageManager */
	protected $packageManager;



	/**
	 * @param \Kdyby\Packages\PackageManager $packageManager
	 */
	public function __construct(PackageManager $packageManager)
	{
		$this->packageManager = $packageManager;
	}



	/**
	 * @return \Kdyby\Packages\PackageManager
	 */
	public function getPackageManager()
	{
		return $this->packageManager;
	}



	/**
	 * @see \Symfony\Component\Console\Helper\Helper::getSelector()
	 */
	public function getName()
	{
		return 'packageManager';
	}

}
