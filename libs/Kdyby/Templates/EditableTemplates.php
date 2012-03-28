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
use Kdyby\Caching\LatteStorage;
use Kdyby\Doctrine\Registry;
use Nette;
use Nette\Caching\Cache;



/**
 * @author Filip Procházka <filip.prochazka@kdyby.org>
 */
class EditableTemplates extends Nette\Object
{

	const CACHE_NS = 'Kdyby.EditableTemplates';

	/**
	 * @var \Kdyby\Doctrine\Dao
	 */
	private $sourcesDao;

	/**
	 * @var \Nette\Caching\Cache
	 */
	private $cache;

	/**
	 * @var \Kdyby\Caching\LatteStorage
	 */
	private $storage;



	/**
	 * @param \Kdyby\Doctrine\Registry $doctrine
	 * @param \Kdyby\Caching\LatteStorage $storage
	 */
	public function __construct(Registry $doctrine, LatteStorage $storage)
	{
		$this->sourcesDao = $doctrine->getDao('Kdyby\Templates\TemplateSource');
		$this->cache = new Cache($this->storage = $storage, static::CACHE_NS);
	}



	/**
	 * @param \Kdyby\Templates\TemplateSource $template
	 */
	public function save(TemplateSource $template)
	{
		$this->storage->hint = (string)$template->getId();

		$this->sourcesDao->save($template);
		if ($source = $template->getSource()) { // entity could be partial
			$this->cache->save($template->getId(), $source);
		}
	}



	/**
	 * @param \Kdyby\Templates\TemplateSource $template
	 */
	public function remove(TemplateSource $template)
	{
		$this->storage->hint = (string)$template->getId();

		$this->cache->remove($template->getId());
		$this->sourcesDao->delete($template);
	}



	/**
	 * @param \Kdyby\Templates\TemplateSource $template
	 *
	 * @throws \Kdyby\FileNotFoundException
	 * @return string
	 */
	public function getTemplateFile(TemplateSource $template)
	{
		$this->storage->hint = (string)$template->getId();

		if (!$cached = $this->cache->load($template->getId())) {
			$this->cache->save($template->getId(), $template->getSource());
		}

		if (!file_exists($cached['file'])) {
			throw Kdyby\FileNotFoundException::fromFile($cached['file']);
		}

		@fclose($cached['handle']);
		return $cached['file'];
	}

}