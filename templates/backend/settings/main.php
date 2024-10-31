<div class="wrap">
    <h1><?= esc_html( get_admin_page_title() ); ?></h1>
    <hr class="wp-header-end">
	<?php settings_errors( 'rpam_messages' ); ?>

	<?= \RP\AdsManager\Helper\Tabs::show() ?>

    <div class="rpam-content">
        <form action="<?= admin_url( 'options.php' ) ?>" method="post">
			<?php
			settings_fields( \RP\AdsManager\WP\Settings::OPTION_GROUP );
			do_settings_sections( \RP\AdsManager\WP\Settings::PAGE );

			submit_button( __( 'Save Settings', 'rp-ads-manager' ) );
			?>
        </form>
    </div>
</div>