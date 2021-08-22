<?php
/**
 *	@package BrizyCustomStyles\Core
 *	@version 1.0.1
 *	2018-09-22
 */

namespace BrizyCustomStyles\Core;

if ( ! defined('ABSPATH') ) {
	die('FU!');
}
use BrizyCustomStyles\Asset;
use BrizyCustomStyles\Compat;

class Core extends Plugin {

	const ACF_OPTIONS_POST_ID = 'brizy_custom_styles';

	/**
	 *	@inheritdoc
	 */
	protected function __construct() {

		add_action( 'acf/init', [ $this, 'acf_init' ] );
		add_filter( 'acf/settings/load_json', [ $this, 'acf_load_json_path'] );
		add_filter( 'acf/load_value/key=field__brizy_styles', [ $this, 'get_brizy_styles' ], 10, 4 );
		add_action( 'acf/save_post', [ $this, 'acf_save_post' ], 15 );
		$args = func_get_args();
		parent::__construct( ...$args );
	}

	/**
	 *	Saves ACF field value to brizy style
	 *
	 *	@action acf/save_post
	 */
	public function acf_save_post( $post_id ) {

		if ( $post_id !== Core::ACF_OPTIONS_POST_ID ) {
			return;
		}
		// prevent loading stlyes from brizy settings
		remove_filter( 'acf/load_value/key=field__brizy_styles', [ $this, 'get_brizy_styles' ], 10 );

		$to_object = function($style) { return (object) $style; };
		$styles = $this->acf2brizy( get_field( 'brizy_styles', Core::ACF_OPTIONS_POST_ID, true ) );

		$project = \Brizy_Editor_Project::get();
		$data = $project->getDecodedData();

		$foundStyle = false;
		$firstStyle = false;
		$data->styles = $styles;
		foreach ( $data->styles as $style ) {
			if ( $firstStyle === false ) {
				$firstStyle = $style->id;
			}
			if ( $style->id === $data->selectedStyle ) {
				$foundStyle = true;
				break;
			}
		}
		if ( ! $foundStyle ) {
			$data->selectedStyle = $firstStyle;
		}

		$project->setDataAsJson( json_encode( $data ) );
		$project->setDataVersion( $project->getCurrentDataVersion() + 1 );
		$project->getStorage()->loadStorage( $project->convertToOptionValue() );

	}

	/**
	 *	Load styles from brizy
	 *
	 *	@filter acf/load_value/key=field__brizy_styles
	 */
	public function get_brizy_styles( $value ) {

		$project = \Brizy_Editor_Project::get();
		$data = $project->getDecodedData();

		$value = $this->brizy2acf( $data->styles );

		return $value;
	}


	/**
	 *	Convert brizy style objects to ACF field values
	 */
	private function acf2brizy( $styles ) {
		return array_map( function($item) use ($to_object) {
			$item = (object) $item;
			$item->colorPalette = array_map( function($color,$id) {
				return (object) [
					'id' => $id,
					'hex' => $color,
				];
			}, $item->colorPalette, array_keys( $item->colorPalette ) );
			$item->fontStyles = array_map( $to_object, $item->fontStyles );
			return $item;
		}, $styles );

	}

	/**
	 *	Convert brizy style objects to ACF field values
	 */
	private function brizy2acf( $styles ) {
		$prefixField = function($str) { return "field__brizy_style_{$str}"; };
		$prefixFontField = function($str) { return "field__brizy_style_font_{$str}"; };

		$acf = [];
		foreach ( $styles as $style ) {
			$acfStyle = get_object_vars($style);
			$acfStyle['colorPalette'] = array_combine(
				array_map( function($item) { return "field__brizy_style_{$item->id}"; }, $acfStyle['colorPalette'] ),
				array_map( function($item) { return $item->hex; }, $acfStyle['colorPalette'] )
			);
			$acfStyle['fontStyles'] = array_map( function($item) use ($prefixFontField) {
				$item = get_object_vars( $item );
				$item = array_combine(
					array_map( $prefixFontField, array_keys( $item ) ),
					array_values( $item )
				);
				return $item;
			}, $acfStyle['fontStyles'] );

			$acfStyle = array_combine(
				array_map( $prefixField, array_keys( $acfStyle )),
				array_values( $acfStyle )
			);
			$acf[] = $acfStyle;
		}
		return $acf;
	}

	/**
	 *	Load ACF-JSON from plugin
	 *
	 *	@action acf/settings/load_json
	 */
	public function acf_load_json_path( $path ) {
		$path[] = $this->get_plugin_dir() . '/acf-json';
		return $path;
	}

	/**
	 *	@action acf_init
	 */
	public function acf_init() {
		acf_add_options_sub_page([
			'page_title' => __('Custom Styles'),
			'menu_title' => __('Custom Styles'),
			'capability' => 'edit_posts',
			'position' => 50,
			'parent_slug' => 'brizy-settings',
			'post_id' => Core::ACF_OPTIONS_POST_ID,
		]);
	}

}
