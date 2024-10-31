<?php

namespace RP\AdsManager\HTML;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 * @internal
 */
final class Tag {

	const A = 'a';
	const H2 = 'h2';
	const SPAN = 'span';
	const INPUT = 'input';
	const TEXTAREA = 'textarea';
	const SELECT = 'select';
	const OPTGROUP = 'optgroup';
	const OPTION = 'option';

	private $node;
	private $closable;

	/**
	 * @var Attributes|null
	 */
	private $attributes = null;

	private $text = '';

	private $children = [];

	public function __construct( $node, $closable = true ) {
		$this->node     = $node;
		$this->closable = $closable;
	}

	/**
	 * @param array $attributes
	 */
	public function setAttributes( $attributes ) {
		$this->attributes = new Attributes();
		foreach ( $attributes as $name => $value ) {
			$this->attributes[ $name ] = $value;
		}
	}

	public function setText( $text ) {
		$this->children = [];

		$this->text = $text;
	}

	public function addChild( Tag $child ) {
		$this->children[] = $child;
	}

	public function __toString() {
		if ( $this->closable ) {
			$tag = sprintf(
				'<%s%s>%s</%s>',
				$this->node,
				null === $this->attributes ? '' : ' ' . $this->attributes,
				! empty( $this->children ) ? implode( '', $this->children ) : $this->text,
				$this->node
			);
		} else {
			$tag = sprintf(
				'<%s%s>',
				$this->node,
				null === $this->attributes ? '' : ' ' . $this->attributes
			);
		}

		return $tag;
	}
}