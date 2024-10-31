<?php

namespace RP\AdsManager\HTML;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 * @internal
 */
final /* abstract */
class Form {


	private function __construct() {
	}

	private function __clone() {
	}

	private function __wakeup() {
	}

	public static function input( $name, $value, $attributes, $hidden = false ) {
		$input = new Tag( Tag::INPUT, false );

		if ( $hidden ) {
			$attributes['disabled'] = 'disabled';
		}

		$attributes = array_merge( [ 'name' => $name, 'type' => 'text', 'value' => $value ], $attributes );
		$input->setAttributes( $attributes );

		return $input;
	}

	public static function textarea( $name, $value, $attributes ) {
		$tag = new Tag( Tag::TEXTAREA );

		if ( is_null( $value ) ) {
			$attributes['disabled'] = 'disabled';
			$value                  = '';
		}
		$attributes = array_merge( [ 'name' => $name ], $attributes );

		$tag->setText( $value );
		$tag->setAttributes( $attributes );

		return $tag;
	}

	public static function checkbox( $name, $value, $checked, $attributes, $disabled = false ) {
		$input = new Tag( Tag::INPUT, false );

		if ( $checked ) {
			$attributes['checked'] = 'checked';
		}

		if ( $disabled ) {
			$attributes['disabled'] = 'disabled';
		}

		$attributes = array_merge( [ 'name' => $name, 'type' => 'checkbox', 'value' => $value ], $attributes );
		$input->setAttributes( $attributes );

		return $input;
	}

	public static function radio( $name, $value, $checked, $attributes ) {
		$input = new Tag( Tag::INPUT, false );

		if ( true === $checked ) {
			$attributes['disabled'] = 'disabled';
			if ( $value == 'none' ) {
				$attributes['checked'] = 'checked';
			}
		} elseif ( $value == $checked ) {
			$attributes['checked'] = 'checked';
		}

		$attributes = array_merge( [ 'name' => $name, 'type' => 'radio', 'value' => $value ], $attributes );
		$input->setAttributes( $attributes );

		return $input;
	}

	/**
	 * @param string $value
	 * @param array $attributes
	 *
	 * @return Tag
	 */
	public static function inputSubmit( $value, $attributes ) {
		$input = new Tag( Tag::INPUT, false );

		$attributes = array_merge( [ 'type' => 'submit', 'value' => $value ], $attributes );
		$input->setAttributes( $attributes );

		return $input;
	}

	/**
	 * @param $name
	 * @param $values
	 * @param $selected
	 * @param array $attributes
	 * @param bool|array $empty
	 * @param bool $disabled
	 *
	 * @return Tag
	 */
	public static function select( $name, $values, $selected, $attributes = [], $empty = false, $disabled = false ) {
		$select = new Tag( Tag::SELECT );

		if ( $disabled ) {
			$attributes['disabled'] = 'disabled';
		}

		$attributes = array_merge( [ 'name' => $name ], $attributes );
		$select->setAttributes( $attributes );

		if ( false !== $empty ) {
			$option = new Tag( Tag::OPTION );

			if (is_array($empty)) {
				$option->setText( $empty['title'] );

				$attributes = [ 'value' => $empty['value'] ];
				if ( $selected == $empty['value'] ) {
					$attributes = array_merge( $attributes, [ 'selected' => 'selected' ] );
				}
			} else {
				$option->setText( $empty );

				$attributes = [ 'value' => '' ];
				if ( $selected == '' ) {
					$attributes = array_merge( $attributes, [ 'selected' => 'selected' ] );
				}
			}

			$option->setAttributes( $attributes );

			$select->addChild( $option );
		}

		foreach ( $values as $opt_value => $opt_name ) {
			if ( is_array( $opt_name ) ) {
				$optgroup = new Tag( Tag::OPTGROUP );
				$optgroup->setAttributes( [ 'label' => $opt_name['label'] ] );

				foreach ( $opt_name['values'] as $grouped_opt_value => $grouped_opt_name ) {
					$option = new Tag( Tag::OPTION );
					$option->setText( $grouped_opt_name );

					$attributes = [ 'value' => $grouped_opt_value ];
					if ( $selected == $grouped_opt_value ) {
						$attributes = array_merge( $attributes, [ 'selected' => 'selected' ] );
					}

					$option->setAttributes( $attributes );

					$optgroup->addChild( $option );
				}

				$select->addChild( $optgroup );
			} else {
				$option = new Tag( Tag::OPTION );
				$option->setText( $opt_name );

				$attributes = [ 'value' => $opt_value ];
				if ( $selected == $opt_value ) {
					$attributes = array_merge( $attributes, [ 'selected' => 'selected' ] );
				}
				$option->setAttributes( $attributes );

				$select->addChild( $option );
			}
		}

//
		return $select;
	}
}