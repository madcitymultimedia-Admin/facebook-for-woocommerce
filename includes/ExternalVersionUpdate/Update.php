<?php
/**
 * Copyright (c) Facebook, Inc. and its affiliates. All Rights Reserved
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package FacebookCommerce
 */

namespace WooCommerce\Facebook\ExternalVersionUpdate;

defined( 'ABSPATH' ) || exit;

use Exception;
use WC_Facebookcommerce_Utils;
use WooCommerce\Facebook\Utilities\Heartbeat;

/**
 * Facebook for WooCommerce External Plugin Version Update.
 *
 * Whenever this plugin gets updated, we need to inform the Meta server of the new version.
 * This is done by sending a request to the Meta server with the new version number.
 *
 * @since x.x.x
 */
class Update {

	/** @var string Name of the option that stores the latest version that was sent to the Meta server. */
	const LATEST_VERSION_SENT = 'wc_facebook_latest_version_sent_to_server';

	/**
	 * Update class constructor.
	 *
	 * @since x.x.x
	 */
	public function __construct() {
		add_action( Heartbeat::HOURLY, array( $this, 'maybe_update_external_plugin_version' ) );
	}

	/**
	 * Check if we need to inform the Meta server of a new version.
	 *
	 * @since x.x.x
	 */
	public function maybe_update_external_plugin_version() {
		$latest_version_sent = get_option( self::LATEST_VERSION_SENT, '0.0.0' );

		if ( WC_Facebookcommerce_Utils::PLUGIN_VERSION === $latest_version_sent ) {
			// Up to date. Nothing to do.
			return;
		}

		$this->send_new_version_to_facebook_server();
	}

	/**
	 * Sends the latest plugin version to the Meta server.
	 *
	 * @since x.x.x
	 */
	public function send_new_version_to_facebook_server() {
		// Send the request to the Meta server with the latest plugin version.
		try {
			$plugin               = facebook_for_woocommerce();
			$external_business_id = $plugin->get_connection_handler()->get_external_business_id();
			$plugin->get_api()->update_plugin_version_configuration( $external_business_id, WC_Facebookcommerce_Utils::PLUGIN_VERSION );
			update_option( self::LATEST_VERSION_SENT, WC_Facebookcommerce_Utils::PLUGIN_VERSION );
		} catch ( Exception $e ) {
			WC_Facebookcommerce_Utils::log( $e->getMessage() );
			// If the request fails, we should retry it in the next heartbeat.
			return;
		}
	}

}
