<div class="wrap">
    <h1 class="wp-heading-inline">RedPic Ads Manager</h1> <i>v<?= RPAM_VERSION ?> (db v<?= \RP\AdsManager\Database\Schema::VERSION ?>)</i>
    <hr class="wp-header-end">
    <div class="rpam-content">
        <div id="welcome-panel" class="welcome-panel">
            <div class="welcome-panel-content">
                <h2><?= __( 'Welcome', 'rp-ads-manager' ) ?>!</h2>
                <div class="welcome-panel-column-container">
                    <div class="welcome-panel-column">
                        <h3><?= __( 'Get Started', 'rp-ads-manager' ) ?></h3>
                        <a class="button button-primary button-hero" href="<?= admin_url( 'admin.php?page=rpam-codes&view=editor' ) ?>"><?= __( 'Create code block', 'rp-ads-manager' ) ?></a>
                        <p><?= __( 'or', 'rp-ads-manager' ) ?> <a href="<?= admin_url( 'admin.php?page=rpam-codes' ) ?>"><?= __( 'customize existing', 'rp-ads-manager' ) ?></a></p>
                    </div>
                    <div class="welcome-panel-column">
                        <h3><?= __( 'Next Steps', 'rp-ads-manager' ) ?></h3>
                        <ul>
                            <li>
                                <div class="welcome-icon welcome-widgets-menus"><?= __( 'Manage', 'rp-ads-manager' ) ?>
                                    <a href="<?= admin_url( 'admin.php?page=rpam-groups' ) ?>"><?= __( 'groups', 'rp-ads-manager' ) ?></a> <?= __( 'and', 'rp-ads-manager' ) ?>
                                    <a href="<?= admin_url( 'admin.php?page=rpam-shortcodes' ) ?>"><?= __( 'shortcodes', 'rp-ads-manager' ) ?></a>
                                </div>
                            </li>
                            <li>
                                <a href="<?= admin_url( 'admin.php?page=rpam-settings' ) ?>"><?= __( 'Check other settings', 'rp-ads-manager' ) ?></a>
                            </li>
                        </ul>
                    </div>
                    <!--div class="welcome-panel-column welcome-panel-last">
                    <h3><?= __( 'Satistics', 'rp-ads-manager' ) ?></h3>
                    <ul>
                        <li><?= __( 'Total blocks', 'rp-ads-manager' ) ?>: <a href="">999</a></li>
                        <li><?= __( 'Active', 'rp-ads-manager' ) ?>: <a href="">777</a></li>
                        <li></li>
                    </ul>
                </div-->
                    <div class="clear"></div>
                </div>
            </div>
        </div>

    </div>
</div>