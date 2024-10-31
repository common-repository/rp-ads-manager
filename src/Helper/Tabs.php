<?php

namespace RP\AdsManager\Helper;

use RP\AdsManager\HTML\Tag;
use RP\AdsManager\Util\Singleton;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
final class Tabs extends Singleton {

	private $tabs = [];

	private $active = null;

	public static function show() {
		$self = self::instance();

		echo $self->display();
	}

	public function register( $slug, $url, $title, $template, $callback, $order ) {
		$this->tabs[ $slug ] = [
			'url'      => $url,
			'title'    => $title,
			'template' => $template,
			'active'   => false,
			'callback' => $callback,
			'order'    => $order
		];
	}

	public function active() {
		return $this->active;
	}

	public function activate( $slug ) {
		$this->active = $slug;
	}

	public function template() {
		return $this->tabs[ $this->active ]['template'];
	}

	public function run() {
		$result = call_user_func( $this->tabs[ $this->active ]['callback'] );

		return $result;
	}

	public function display() {
		$tabs = new Tag( Tag::H2 );
		$tabs->setAttributes( [ 'class' => 'nav-tab-wrapper' ] );

		uasort( $this->tabs, function ( $a, $b ) {
			if ( $a['order'] == $b['order'] ) {
				return 0;
			}

			return ( $a['order'] < $b['order'] ) ? - 1 : 1;
		} );

		foreach ( $this->tabs as $key => $tab ) {
			$attr = [ 'class' => 'nav-tab' ];

			if ( $this->active == $key ) {
				$attr['class'] .= ' nav-tab-active';
			}

			if ( null !== $tab['url'] ) {
				$child = new Tag( Tag::A );
				$attr  = array_merge( $attr, [ 'href' => $tab['url'] ] );
			} else {
				$child = new Tag( Tag::SPAN );

			}

			$child->setAttributes( $attr );
			$child->setText( $tab['title'] );
			$tabs->addChild( $child );
		}

		return $tabs;
	}
}