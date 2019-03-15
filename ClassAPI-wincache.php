<?php

/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines http://www.simplemachines.org
 * @copyright 2019 Simple Machines and individual contributors
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.1 RC1
 */

if (!defined('SMF'))
	die('Hacking attempt...');

/**
 * Our Cache API class
 *
 * @package cacheAPI
 */
class wincache_cache extends cache_api
{
	/**
	 * {@inheritDoc}
	 */
	public function isSupported($test = false)
	{
		$supported = function_exists('wincache_ucache_set') && function_exists('wincache_ucache_get');
		if ($test)
			return $supported;
		return parent::isSupported() && $supported;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getData($key, $ttl = null)
	{
		$key = $this->prefix . strtr($key, ':/', '-_');
		return wincache_ucache_get($key);
	}

	/**
	 * {@inheritDoc}
	 */
	public function putData($key, $value, $ttl = null)
	{
		$key = $this->prefix . strtr($key, ':/', '-_');
		$ttl = !empty($ttl) ? $ttl : $this->getDefaultTTL();

		// No value, delete it.
		if ($value === null)
			return wincache_ucache_delete($key);
		else
			return wincache_ucache_set($key, $value, $ttl);
	}

	/**
	 * {@inheritDoc}
	 */
	public function cleanCache($type = '')
	{
		$this->invalidateCache();
		return wincache_ucache_clear();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getVersion()
	{
		return phpversion('wincache');
	}
}
