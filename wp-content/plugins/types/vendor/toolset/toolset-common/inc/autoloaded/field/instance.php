<?php

use OTGS\Toolset\Common\PublicAPI\CustomFieldInstance;
use OTGS\Toolset\Common\Utils\RequestMode;

/**
 * Instance of a field belonging to some object.
 *
 * @since 1.9
 */
abstract class Toolset_Field_Instance
	extends Toolset_Field_Instance_Unsaved
	implements CustomFieldInstance
{

	private $object_id;

	/**
	 * Toolset_Field_Instance constructor.
	 *
	 * @param Toolset_Field_Definition $definition Field definition.
	 * @param int $object_id Id of the object containing the field.
	 */
	public function __construct( $definition, $object_id ) {

		parent::__construct( $definition );

		if ( $object_id != (int) $object_id || 0 >= (int) $object_id ) {
			throw new InvalidArgumentException( 'Invalid object id.' );
		}
		$this->object_id = (int) $object_id;
	}


	/**
	 * Accessor object to manipulate the database value directly.
	 *
	 * @return Toolset_Field_Accessor_Abstract
	 */
	protected function get_accessor() {
		if( null === $this->accessor ) {
			$this->accessor = $this->get_definition()->get_accessor( $this );
		}
		return $this->accessor;
	}


	public function get_object_id() {
		return $this->object_id;
	}


	/**
	 * Delete all field values (both for single and repetitive fields).
	 */
	public function delete_all_values() {
		$this->get_accessor()->delete_raw_value();
	}


	/**
	 * Overwrite current field values with new ones.
	 *
	 * @param array $values Array of values. For non-repetitive field there must be exactly one value. Order of values
	 *     in this array will be stored as sort order.
	 * @return bool True on success, false if some error has occured.
	 */
	abstract public function update_all_values( $values );


	/**
	 * Add a single field value to the database.
	 *
	 * The value will be passed through filters as needed and stored, based on field configuration.
	 *
	 * @param mixed $value Raw value, which MUST be validated already.
	 *
	 * @return mixed
	 */
	abstract public function add_value( $value );


	/**
	 * @return string Meta key that is used to store value order for repetitive fields.
	 */
	public function get_order_meta_name() {
		return sprintf( '_%s-sort-order', $this->get_definition()->get_meta_key() );
	}


	/**
	 * @return Toolset_Field_Accessor_Abstract An accessor to get the sort order for repetitive fields.
	 */
	abstract public function get_order_accessor();


	/**
	 * For repetitive field, get the order of individual values.
	 *
	 * @return array Meta IDs in the order defining the field value order.
	 */
	public function get_sort_order() {
		$accessor = $this->get_order_accessor();
		return toolset_ensarr( $accessor->get_raw_value() );
	}


	/**
	 * Update order of inidvidual values for a repetitive field.
	 *
	 * @param int[] $order Array of meta IDs. It must be a complete match to actual values stored in the database.
	 * @return bool|mixed Update result. Depends on the underlying accessor.
	 */
	public function set_sort_order( $order ) {
		if( !is_array( $order ) ) {
			return false;
		}

		return $this->get_order_accessor()->update_raw_value( $order );
	}


	/**
	 * @return mixed Value of the field in the "intermediate" format.
	 */
	public function get_value() {
		$data_mapper = $this->get_definition()->get_data_mapper();
		$accessor = $this->get_accessor();
		return $data_mapper->database_to_intermediate( $accessor->get_raw_value() );
	}


	/**
	 * Get a field renderer.
	 *
	 * Note: Some combinations of purpose and environment are not supported.
	 *
	 * @param string $purpose Toolset_Field_Renderer_Purpose value.
	 * @param string $environment Toolset_Common_Bootstrap::MODE_* value.
	 * @param array $renderer_args Optional, custom arguments for the renderer.
	 *
	 * @return Toolset_Field_Renderer_Abstract
	 * @throws RuntimeException
	 * @since m2m
	 */
	public function get_renderer( $purpose, $environment, $renderer_args ) {
		return $this->get_field_type()
			->get_renderer( $purpose, $environment, $this, $renderer_args );
	}


	/**
	 * A shortcut to $this->get_renderer()->render() as required by the CustomFieldInstance interface.
	 *
	 * @param string $purpose
	 * @param null|string $environment
	 *
	 * @return array|mixed|string
	 * @throws RuntimeException When the field instance cannot be rendered.
	 * @since Types 3.3.5
	 */
	public function render( $purpose, $environment = null ) {
		if( null === $environment ) {
			$environment = ( new RequestMode() )->get();
		}

		return $this->get_renderer( $purpose, $environment, [] )->render();
	}

}
