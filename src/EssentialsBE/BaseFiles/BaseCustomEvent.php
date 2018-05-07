<?php

declare(strict_types=1);

namespace EssentialsBE\BaseFiles;

use pocketmine\event\plugin\PluginEvent;
use pocketmine\plugin\Plugin;

abstract class BaseCustomEvent extends PluginEvent
{
	/** @var BaseAPI */
	private $api;

	/**
	 * @param BaseAPI $api
	 */
	public function __construct(BaseAPI $api)
	{
		parent::__construct($api->getEssentialsBEPlugin());
		$this->api = $api;
	}


	/**
	 * @return Plugin
	 */
	public final function getPlugin() : Plugin
	{
		return $this->getAPI()->getEssentialsBEPlugin();
	}

	/**
	 * @return BaseAPI
	 */
	public final function getAPI() : BaseAPI
	{
		return $this->api;
	}
}