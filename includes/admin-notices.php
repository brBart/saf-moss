<?php

/**
 * MOSS SAF Admin notices
 *
 * @package     vat-moss-saf
 * @subpackage  Includes
 * @copyright   Copyright (c) 2014, Lyquidity Solutions Limited
 * @License:	GNU Version 2 or Any Later Version
 * @since       1.0
 */

namespace lyquidity\vat_moss_saf;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Admin Notices
 *
 * Outputs admin notices
 *
 * @package VAT MOSS SAF
 * @since 1.0
*/
function admin_notices() {

	if (!isset($_REQUEST['page']) || $_REQUEST['page'] !== 'moss-saf-settings') return;

	$settings =  vat_moss_saf()->settings;
	$vat_number = $settings->get( 'vat_number', '' );

	$out = new \StdClass();
	$country = get_establishment_country();
	if (!perform_simple_check("$country$vat_number", $out))
	{
		echo "<div class='error'><p>$out->message</p></div>";
	}
	
	$names = array(VAT_MOSS_ACTIVATION_ERROR_NOTICE, VAT_MOSS_ACTIVATION_UPDATE_NOTICE, VAT_MOSS_DEACTIVATION_ERROR_NOTICE, VAT_MOSS_DEACTIVATION_UPDATE_NOTICE);
	array_walk($names, function($name) {

		$message = get_transient($name);
		delete_transient($name);

		if (empty($message)) return;
		$class = strpos($name,"UPDATE") === FALSE ? "error" : "updated";
		echo "<div class='$class'><p>$message</p></div>";

	});

}
add_action('admin_notices', '\lyquidity\vat_moss_saf\admin_notices');