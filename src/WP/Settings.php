<?php

namespace RP\AdsManager\WP;


use RP\AdsManager\HTML\Tag;
use RP\AdsManager\Util\Singleton;

class Settings extends Singleton {

	const OPTION_NAME = 'rpam_options';
	const OPTION_GROUP = 'rpam';
	const PAGE = 'rpam';

	private $options;

	protected function __construct() {
		$this->options = get_option( self::OPTION_NAME );
	}

	public function set( $key, $value ) {
		$this->options[ $key ] = $value;

		\update_option( self::OPTION_NAME, $this->options );
	}

	public function get( $key, $default = false ) {
		return is_array( $this->options ) && array_key_exists( $key, $this->options ) ? $this->options[ $key ] : $default;
	}

	public function register() {
		register_setting( self::OPTION_GROUP, self::OPTION_NAME );

		add_settings_section(
			'rpam_section_main',
			false,
			'__return_empty_string',
			self::PAGE
		);

//		add_settings_field(
//			'rpam_field_policy',
//			__( 'Display ads policy', 'rp-ads-manager' ),
//			[ $this, 'fieldPolicy' ],
//			self::PAGE,
//			'rpam_section_main',
//			[
//				'label_for' => 'rpam_field_policy'
//			]
//		);

		add_settings_field(
			'rpam_field_codeheader',
			__( 'Header code block', 'rp-ads-manager' ),
			[ $this, 'fieldCodetextarea' ],
			self::PAGE,
			'rpam_section_main',
			[
				'label_for' => 'rpam_field_codeheader'
			]
		);

		add_settings_field(
			'rpam_field_codefooter',
			__( 'Footer code block', 'rp-ads-manager' ),
			[ $this, 'fieldCodetextarea' ],
			self::PAGE,
			'rpam_section_main',
			[
				'label_for' => 'rpam_field_codefooter'
			]
		);
	}

	public function fieldPolicy( $args ) {
		$selected = $this->get( $args['label_for'] );

		echo '<select id="' . esc_attr( $args['label_for'] ) . '" name="rpam_options[' . esc_attr( $args['label_for'] ) . ']" disabled>
			<option value="none" ' . selected( $selected, 'none', false ) . '>' . esc_html__( 'display', 'rp-ads-manager' ) . '</option>
			<option value="block" ' . selected( $selected, 'block', false ) . '>' . esc_html__( 'block', 'rp-ads-manager' ) . '</option>
		</select>
		<p class="description">' . esc_html__( 'Global ads display policy', 'rp-ads-manager' ) . '</p>';
	}

	public function fieldCodetextarea( $args ) {
		$value = $this->get( $args['label_for'], '' );

		$textarea = new Tag( Tag::TEXTAREA );
		$attr     = [
			'id'    => esc_attr( $args['label_for'] ),
			'class' => 'code-editor',
			'name'  => 'rpam_options[' . esc_attr( $args['label_for'] ) . ']'
		];
		$textarea->setAttributes($attr);
		$textarea->setText($value);

		echo $textarea;
	}
}