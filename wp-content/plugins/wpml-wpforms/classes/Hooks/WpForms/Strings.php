<?php

namespace WPML\Forms\Hooks\WpForms;

use SitePress;
use WPML\Forms\Hooks\Registration;
use WPML\Forms\Translation\Factory;

class Strings extends Registration {

	/** @var SitePress */
	private $sitepress;

	/**
	 * WPML\Forms\Hooks\WpForms\Strings constructor.
	 *
	 * @param string    $slug Form type slug.
	 * @param string    $kind Translation package kind.
	 * @param Factory   $factory Translation package factory.
	 * @param SitePress $sitepress
	 */
	public function __construct( $slug, $kind, Factory $factory, SitePress $sitepress ) {
		$this->sitepress = $sitepress;
		parent::__construct( $slug, $kind, $factory );
	}

	/** Adds hooks. */
	public function addHooks() {
		parent::addHooks();
		add_action( 'wpforms_save_form', [ $this, 'register' ] );
		add_filter( 'wpforms_process_before_form_data', [ $this, 'applySubmissionTranslations' ] );
		add_filter( 'wpforms_frontend_form_data', [ $this, 'applyTranslations' ] );
	}

	/**
	 * Gets field ID from provided data.
	 *
	 * @param array $data Field data.
	 *
	 * @return string|null
	 */
	public function getFieldId( array $data ) {
		return ( array_key_exists( 'id', $data )
			&& ( is_string( $data['id'] ) || is_int( $data['id'] ) ) )
			? strval( $data['id'] ) : null;
	}

	/**
	 * Registers form for translation.
	 *
	 * @param int $formId Form ID.
	 */
	public function register( $formId ) {

		$content = get_post_field( 'post_content', $formId, 'raw' );
		$data    = json_decode( $content, true );
		$package = $this->newPackage( $formId );

		if ( $this->notEmpty( 'settings', $data ) ) {
			$package->registerFormSettings( $data['settings'] );
		}

		if ( $this->notEmpty( 'fields', $data ) ) {
			foreach ( $data['fields'] as $field ) {
				$fieldID = $this->getFieldId( $field );
				if ( ! is_null( $fieldID ) ) {
					$package->registerField( $fieldID, $field );
				}
			}
		}
		$package->cleanup();
	}

	/**
	 * Applies translations to form.
	 *
	 * @param array $formData Form data.
	 *
	 * @return array
	 */
	public function applyTranslations( array $formData ) {

		$package = $this->newPackage( $this->getId( $formData ) );

		if ( $this->notEmpty( 'settings', $formData ) ) {
			$formData['settings'] = $package->translateFormSettings( $formData['settings'] );
		}

		// if form was submitted, fields are already translated.
		if ( did_action( 'wpforms_process_before' ) ) {
			return $formData;
		} else {
			return $this->applySubmissionTranslations( $formData );
		}
	}

	/**
	 * Applies translations to form for processing submitted fields.
	 *
	 * @param array $formData Form data.
	 *
	 * @return array
	 */
	public function applySubmissionTranslations( array $formData ) {

		$package = $this->newPackage( $this->getId( $formData ) );

		$this->ajaxSwitchLanguage();

		if ( $this->notEmpty( 'fields', $formData ) ) {
			foreach ( $formData['fields'] as &$field ) {
				$fieldID = $this->getFieldId( $field );
				if ( ! is_null( $fieldID ) ) {
					$field = $package->translateField( $field, $fieldID );
				}
			}
		}

		return $formData;
	}

	/**
	 * Adds forms info for bulk registration.
	 *
	 * @param array $items Array of form infos.
	 *
	 * @return array
	 */
	public function bulkRegistrationItems( array $items ) {

		$forms = wpforms()->form->get();
		if ( is_array( $forms ) ) {
			foreach ( $forms as $form ) {
				$items[] = $this->getBulkRegistrationItem( $form->ID, $form->post_title );
			}
		}

		return $items;
	}

	/**
	 * Registers forms for translation.
	 *
	 * @param array $forms Array of form IDs.
	 */
	public function bulkRegistration( array $forms ) {
		foreach ( $forms as $formId ) {
			$this->register( $formId );
		}
	}

	/**
	 * If within an AJAX request, then parse the language from the 'page_url' submitted by WPForms and switch to it.
	 */
	private function ajaxSwitchLanguage() {
		if ( wpml_is_ajax() ) {
			$pageUrl = filter_input( INPUT_POST, 'page_url' );
			if ( $pageUrl ) {
				$languageCode = $this->sitepress->get_language_from_url( $pageUrl );
				$this->sitepress->switch_lang( $languageCode, true );
			}
		}
	}
}
