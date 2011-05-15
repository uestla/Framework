<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2011 Filip Procházka (filip.prochazka@kdyby.org)
 *
 * @license http://www.kdyby.org/license
 */

namespace Kdyby\Templates;

use Kdyby;
use Nette;



/**
 * @author Filip Procházka
 */
interface ITemplateFactory
{

	/**
	 * @param Nette\ComponentModel\Component $component
	 * @return Nette\Templating\ITemplate
	 */
	function createTemplate(Nette\ComponentModel\Component $component);

}