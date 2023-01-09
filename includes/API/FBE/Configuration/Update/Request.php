<?php
/**
 * Copyright (c) Facebook, Inc. and its affiliates. All Rights Reserved
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package FacebookCommerce
 */

namespace WooCommerce\Facebook\API\FBE\Configuration\Update;

defined( 'ABSPATH' ) || exit;

use WooCommerce\Facebook\API\FBE\Configuration;

/**
 * FBE Configuration update request object.
 *
 * @since 2.0.0
 */
class Request extends Configuration\Request {


	/**
	 * API request constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param string $external_business_id external business ID
	 */
	public function __construct( $external_business_id ) {
		parent::__construct( $external_business_id, 'POST' );
		// include the business ID in the request body
		$this->data['fbe_external_business_id'] = $external_business_id;
	}


	/**
	 * Sets the messenger configuration.
	 *
	 * Only the enabled and domains values are able to accept updates right now.
	 *
	 * @since 2.0.0
	 *
	 * @param Configuration\Messenger $configuration messenger configuration object
	 */
	public function set_messenger_configuration( Configuration\Messenger $configuration ) {

		$this->data['messenger_chat'] = array(
			'enabled' => $configuration->is_enabled(),
			'domains' => $configuration->get_domains(),
		);
	}

	/**
	 * Sets the plugin version for configuration update request.
	 *
	 * @since x.x.x
	 *
	 * @param string $plugin_version current plugin version.
	 */
	public function set_plugin_version( string $plugin_version ) {

		$this->data['business_config'] = array(
			'external_client_version_id' => "\"$plugin_version\"",
		);
	}


}
