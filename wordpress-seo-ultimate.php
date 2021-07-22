<?php
/**
 * Plugin Name: Wordpress SEO Ultimate 🚀
 * Description: The magical plugin to glue Yoast and acf.
 * Text Domain: WSU
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
 * Load plugin.
 *
 * @since 1.0.0
 */
class WordpressSeoUltimate
{
	/**
	 * Setup WordpressSeoUltimate for flight 🚀
	 */
	public function init()
	{
		add_action('init', [$this, 'addTextDomain']);
		add_filter('Yoast\WP\ACF\headlines', [$this, 'addHeadlines']);
		add_action('acf/render_field_settings/type=text', [$this, 'addRenderField']);
		add_action('acf/render_field_settings/type=textarea', [$this, 'addRenderField']);
	}

	/**
	 * Add custom headlines of acf fields.
	 *
	 * @param array $headlines
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function addHeadlines(array $headlines): array
	{
		$fields = get_field_objects();
		foreach ($fields as $field) {
			if ( ! empty($field['type'])) {
				if ($field['type'] === 'flexible_content') {
					if ( ! empty($field['layouts'])) {
						$headlines = $this->getTagsOfFlexibleContent($field['layouts'], $headlines);
					}
				}

				$headlines = $this->addTagToHeadlines($field, $headlines);
			}

		}

		return $headlines;
	}

	/**
	 * Check if current field is a text or textarea to add tags.
	 *
	 * @param array $field
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	private function checkFieldIfHasHeadlines(array $field): bool
	{
		if ($field['type'] === 'text' || $field['type'] === 'textarea') {
			return true;
		}

		return false;
	}

	/**
	 * Add tag to headline if is set.
	 *
	 * @param array $field
	 * @param array $headlines
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function addTagToHeadlines(array $field, array $headlines): array
	{
		if ($this->checkFieldIfHasHeadlines($field)) {

			if ( ! empty($field['tag'])) {
				if (is_int($field['tag'])) {
					// value from 1-6, 1=h1, 6=h6
					$headlines[$field['key']] = $field['tag'];
				}
			}
		}

		return $headlines;
	}

	/**
	 * Loop trough the layouts to check if we need to add headlines
	 *
	 * @param array $layouts
	 * @param array $headlines
	 *
	 * @return array
	 * @since 1.0.0
	 *
	 */
	private function getTagsOfFlexibleContent(array $layouts, array $headlines): array
	{
		foreach ($layouts as $layout) {
			if ( ! empty($layout['sub_fields'])) {
				foreach ($layout['sub_fields'] as $subField) {
					$headlines = $this->addTagToHeadlines($subField, $headlines);
				}
			}
		}

		return $headlines;
	}

	/**
	 * Add tag option
	 *
	 * @param array $field
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function addRenderField(array $field)
	{
		acf_render_field_setting($field, array(
			'label'        => __('Select tag', WSU_TEXT_DOMAIN),
			'instructions' => __('This tag is used for the acf glue in yoast text analyst', WSU_TEXT_DOMAIN),
			'name'         => 'tag',
			'type'         => 'select',
			'choices'      => $this->getTags(),
			'ui'           => 1,
		), true);
	}

	/**
	 * Return array with all tags
	 * @return string[]
	 *
	 * @since 1.0.0
	 */
	private function getTags(): array
	{
		return [
			'1' => 'H1',
			'2' => 'H2',
			'3' => 'H3',
			'4' => 'H4',
			'5' => 'H5',
			'6' => 'H6',
			'p' => 'P'
		];
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

$wsu = new WordpressSeoUltimate;
$wsu->init();
