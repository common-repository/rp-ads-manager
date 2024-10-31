<div class="wrap">
    <h1><?= is_null( $record['id'] ) ? __( 'Create group', 'rp-ads-manager' ) : __( 'Edit group', 'rp-ads-manager' ) ?> | RedPic Ads Manager</h1>
    <hr class="wp-header-end">
    <div class="rpam-content">
        <form action="" method="post">
            <div>
                <div id="titlediv">
					<?= \RP\AdsManager\HTML\Form::input( 'group[title]', $record['title'], [
						'id'           => 'title',
						'autocomplete' => 'off',
						'placeholder'  => __( 'Group title', 'rp-ads-manager' )
					] ); ?>
                </div>

                <h2 class="nav-tab-wrapper">
                    <span class="nav-tab nav-tab-active"><?= __( 'Targeting', 'rp-ads-manager' ) ?>
                        <i>(<?= __( 'soon', 'rp-ads-manager' ) ?>)</i></span>
                </h2>
                <div style="height:200px"></div>
            </div>

            <div class="rp-form-footer">
                <div class="buttons">
                    <a href="<?= admin_url( 'admin.php?page=rpam-groups' ) ?>" class="button"><?= __( 'Back', 'rp-ads-manager' ) ?></a>
                    <button class="button button-primary"><?= __( 'Save', 'rp-ads-manager' ) ?></button>
					<?php if ( ! is_null( $record['id'] ) ): ?>
                        <a href="<?= admin_url( 'admin.php?page=rpam-groups&action=delete&id=' . $record['id'] ) ?>" class="button delete"><?= __( 'Delete', 'rp-ads-manager' ) ?></a>
					<?php endif; ?>
                </div>
            </div>
        </form>
        <div class="clear"></div>
    </div>
</div>