<?php
/**
 * Plugin Name: Wordpress SEO Ultimate 🚀
 * Description: The magical plugin to glue Yoast and acf.
 * Text Domain: WSU
 * Domain Path: /languages
 *
 * Author: Joeri Abbo
 * Author URI: https://nl.linkedin.com/in/joeri-abbo-43a457144
 *
 * Version: 1.0.0
 */

// File Security Check
defined('ABSPATH') or die("No script kiddies please!");

const WSU_TEXT_DOMAIN = 'WSU';
const WSU_VERSION     = '1.0.0';

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
class WordpressSeoUltimate
{
	/**
	 * Setup WordpressSeoUltimate for flight 🚀
	 */
	public static function init()
	{
		add_action('init', [__CLASS__, 'addTextDomain']);

	}

	public function __construct()
	{
		self::init();
	}

	/**
	 * Add text domain
	 *
	 * @since 1.0.0
	 */
	public static function addTextDomain()
	{
		load_plugin_textdomain(WSU_TEXT_DOMAIN, false, basename(dirname(__FILE__)) . '/languages');
	}
}

new WordpressSeoUltimate();
