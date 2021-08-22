<?php
/**
 *	@package BrizyCustomStyles\Compat
 *	@version 1.0.0
 *	2018-09-22
 */

namespace BrizyCustomStyles\Compat;

if ( ! defined('ABSPATH') ) {
	die('FU!');
}


use BrizyCustomStyles\Core;

class ACF extends Core\Singleton {

	/**
	 *	@var string prefix for field group json
	 */
	private $group_prefix = 'brizy_custom_styles';

	/**
	 *	@var Core\Core instance
	 */
	private $core;

	/**
	 *	@inheritdoc
	 */
	protected function __construct() {



	}


}
