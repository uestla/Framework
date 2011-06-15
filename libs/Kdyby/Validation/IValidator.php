<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2011 Filip Procházka (filip.prochazka@kdyby.org)
 *
 * @license http://www.kdyby.org/license
 */

namespace Kdyby\Validation;



/**
 * @author Filip Procházka
 */
interface IValidator
{

	/**
	 * @param IPropertyDecorator $decorator
	 * @param string|NULL $event
	 * @return Result
	 */
	function validate(IPropertyDecorator $decorator, $event = NULL);

}