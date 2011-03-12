<?php

/**
 * This file is part of the Framework - Content Managing System (CMF) Kdyby.
 *
 * Copyright (c) 2008, 2010 Filip Procházka (http://hosiplan.kdyby.org)
 *
 * For more information please see http://www.kdyby.org
 *
 * @package CMF Kdyby-Common
 */


namespace Kdyby\Location;

use Doctrine\Common\Collections\ArrayCollection;
use Nette;
use Kdyby;



/**
 * @author Filip Procházka <hosiplan@kdyby.org>
 * @Entity @Table(name="location_states")
 */
class State extends Kdyby\Doctrine\IdentifiedEntity
{
	/** @Column(type="string", unique=TRUE) */
	private $name;

	/** @OneToMany(targetEntity="Kdyby\Location\City", mappedBy="state") */
	private $cities;

	/** @OneToMany(targetEntity="Kdyby\Location\District", mappedBy="state") */
	private $districts;



	public function __construct()
	{
		$this->citites = new ArrayCollection();
		$this->districts = new ArrayCollection();
	}

	public function getName() { return $this->name; }
	public function setName($name) { $this->name = $name; }

	public function addCity(City $city)
	{
		$this->cities->add($city);
		$city->setState($this);
	}

	public function removeCity(City $city)
	{
		$this->cities->removeElement($city);
		$city->setState(NULL);
	}

	public function addDistrict(District $district)
	{
		$this->districts->add($district);
		$district->setState($this);
	}

	public function removeDistrict(District $district)
	{
		$this->districts->removeElement($district);
		$district->setState(NULL);
	}

}