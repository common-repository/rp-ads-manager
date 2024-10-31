<?php

namespace RP\AdsManager\WP;

use RP\AdsManager\Database\Query;
use RP\AdsManager\Helper\AdInjector;
use RP\AdsManager\Helper\wpView;

/**
 * Class Widget
 *
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class Widget extends \WP_Widget {
	const IDN = 'rp_ads_w_place';

	public function __construct() {
		$widget_options = array(
			'classname'   => self::IDN,
			'description' => __( 'Allows to show ads from RedPic Ads Manager', 'rp-ads-manager' ),
		);

		parent::__construct( self::IDN, __( 'Code placeholder (RP Ads Manager)', 'rp-ads-manager' ), $widget_options );
	}

	public static function stock( $api = false ) {
		$widgets = [];

		$sidebarsWidgets = \wp_get_sidebars_widgets();

		global $wp_registered_sidebars;

		$registeredOptions = \get_option( 'widget_' . self::IDN );

		foreach ( $sidebarsWidgets as $wpSidebar => $wpWidgets ) {
			foreach ( $wpWidgets as $widgetId ) {
				if ( 0 === \strpos( $widgetId, self::IDN . '-' ) ) {
					$widgetIntId = \str_replace( self::IDN . '-', '', $widgetId );

					$widgetTitle = $registeredOptions[ $widgetIntId ]['title'] == '' ? $widgetId : $registeredOptions[ $widgetIntId ]['title'] . ' (' . $widgetId . ')';

					if ( $api ) {
						if ( \array_key_exists( $wpSidebar, $widgets ) ) {
							$widgets[ $wpSidebar ]['values'][] = [ 'id' => $widgetId, 'title' => $widgetTitle ];
						} else {
							$widgets[ $wpSidebar ] = [
								'label'  => $wp_registered_sidebars[ $wpSidebar ]['name'],
								'values' => []
							];

							$widgets[ $wpSidebar ]['values'][] = [ 'id' => $widgetId, 'title' => $widgetTitle ];
						}
					} else {
						if ( \array_key_exists( $wpSidebar, $widgets ) ) {
							$widgets[ $wpSidebar ]['values'][ $widgetId ] = $widgetTitle;
						} else {
							$widgets[ $wpSidebar ] = [
								'label'  => $wp_registered_sidebars[ $wpSidebar ]['name'],
								'values' => []
							];

							$widgets[ $wpSidebar ]['values'][ $widgetId ] = $widgetTitle;
						}
					}
				}
			}
		}

		return \array_values( $widgets );
	}

	public function widget( $args, $instance ) {
		$ads = Query::instance()->codesByPlace( wpView::WIDGET, $args['widget_id'] );

		if ( count( $ads ) ) {
			$randomKey = array_rand( $ads );

			echo $args['before_widget'];
			AdInjector::printit( $ads[ $randomKey ] );
			echo $args['after_widget'];
		}
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' ) ?>:
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
        </label>
        </p><?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}
}
