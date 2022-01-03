<?php

namespace WPML\Forms\UI;

class Notices {

	/** Adds missing dependencies notice. */
	public function addHooksForMissingDependencies() {
		add_action( 'admin_notices', [ $this, 'renderMissingDependenciesNotice' ] );
	}

	/**
	 * Renders admin notice for missing dependencies.
	 *
	 * @codeCoverageIgnore
	 */
	public function renderMissingDependenciesNotice() {
		$message = __( 'WPML Forms: Please activate your WPML CMS, WPML String Translation and WPML Translation Management plugins.', 'wpml-forms' );
		echo '<div class="notice notice-error"><p>' . wp_kses_post( $message ) . '</p></div>';
	}
}
