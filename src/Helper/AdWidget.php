<?php

namespace RP\AdsManager\Helper;

/**
 * Class Widget
 *
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class AdWidget extends \WP_Widget {
	const IDN = 'rp_ads_w_place';

	public function __construct() {
		$widget_options = array(
			'classname'   => self::IDN,
			'description' => __( 'Allows to show ads from RedPic Ads Manager', 'rp-ads-manager' ),
		);

		parent::__construct( self::IDN, __( 'Ad placeholder (RP Ads Manager)', 'rp-ads-manager' ), $widget_options );
	}

	public function widget( $args, $instance ) {
		$ads = Code::instance()->byPlaceId( wpView::WIDGET, $args['widget_id'] );

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
