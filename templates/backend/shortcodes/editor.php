<div class="wrap">
    <h1><?= is_null( $record['id'] ) ? __( 'Create shortcode', 'rp-ads-manager' ) : __( 'Edit shortcode', 'rp-ads-manager' ) ?> | RedPic Ads Manager</h1>
    <hr class="wp-header-end">
    <div class="rpam-content">
        <form action="" method="post">
            <div>
                <div id="titlediv">
					<?= \RP\AdsManager\HTML\Form::input( 'shortcode[title]', $record['title'], [
						'id'           => 'title',
						'autocomplete' => 'off',
						'placeholder'  => __( 'Shortcode title', 'rp-ads-manager' )
					] ); ?>
                </div>
            </div>

            <div class="rp-form-footer">
                <div class="buttons">
                    <a href="<?= rpam_url( 'shortcodes' ) ?>" class="button"><?= __( 'Back', 'rp-ads-manager' ) ?></a>
                    <button class="button button-primary"><?= __( 'Save', 'rp-ads-manager' ) ?></button>
					<?php if ( ! is_null( $record['id'] ) ): ?>
                        <a href="<?= rpam_url( 'shortcodes', 'action=delete&id=' . $record['id'] ) ?>" class="button delete"><?= __( 'Delete', 'rp-ads-manager' ) ?></a>
					<?php endif; ?>
                </div>
            </div>
        </form>
        <div class="clear"></div>
    </div>
</div>