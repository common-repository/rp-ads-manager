<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <hr class="wp-header-end">
    <?php if (!isset($step)): ?>
        <p class="description">Select plugin to proceed with import</p>
        <div>
            <?php foreach($rules as $rule): ?>
                <a href="<?php echo admin_url( 'admin.php?page=rpam-import&plugin='.$rule->getPluginKey().'&step=1' ); ?>" class="button choose-type"><span class="dashicons dashicons-admin-plugins"></span><?php echo $rule->getInfo('Name'); ?></a>
            <?php endforeach; ?>
        </div>
    <?php elseif($step == 1): ?>
        <p><?php _e('Processing data from', 'rp-ads-manager'); ?> <b><?php echo $current->getInfo('Name'); ?></b></p>
        <form action="<?php echo admin_url( 'admin.php?page=rpam-import&step=2' ); ?>" method="post">
            <textarea title="Import" name="import_data" style="display: none;"><?php echo serialize($ported_config); ?></textarea>
            <?php if (empty($ported_config)): ?>
                <h3><?php _e('No data to import found =('); ?></h3>
            <?php else: ?>
                <ul>
                    <li>
                        <p><?php _e('Ready to import', 'rp-ads-manager'); ?>:</p>
                        <ul style="padding-left: 20px">
                            <?php if (array_key_exists('ads', $ported_config)): ?>
                                <li>
                                    <b><?php _e('Ad blocks', 'rp-ads-manager'); ?></b>: <?php echo count($ported_config['ads']); ?>
                                    <table class="wp-list-table widefat striped" style="max-width:600px">
                                        <thead>
                                            <tr>
                                                <th><?php _e('Ad code (js/html)', 'rp-ads-manager'); ?></th>
                                            </tr>
                                        </thead>
                                        <?php foreach ($ported_config['ads'] as $ad): ?>
                                        <tr>
                                            <td><?php echo esc_html($ad['code']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </li>
                            <?php endif; ?>
                            <?php if (array_key_exists('wgt', $ported_config)): ?>
                                <li><b><?php _e('Widgets', 'rp-ads-manager'); ?></b>: <?php echo count($ported_config['wgt']); ?></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>
            <div class="buttons">
                <a href="<?php echo admin_url( 'admin.php?page=rpam-import' ); ?>" class="button"><?php _e('Back', 'rp-ads-manager'); ?></a>
                <?php if (!empty($ported_config)): ?>
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Do Import', 'rp-ads-manager'); ?>">
                <?php endif; ?>
            </div>
        </form>
    <?php elseif($step == 2): ?>
        <?php if (!isset($error)): ?>
        <h4><?php _e('Successfuly imported all data. Now navigate to Dashboard and check ads options.', 'rp-ads-manager'); ?></h4>
            <div class="buttons">
                <a href="<?php echo admin_url('admin.php?page=rpam-dashboard'); ?>" class="button button-primary"><?php echo __( 'Dashboard', 'rp-ads-manager' ) . ' | RedPic Ads Manager'; ?></a>
            </div>
        <?php else: ?>
            <h4><?php _e('There was an error while importing data. Please start import from the begining.', 'rp-ads-manager'); ?></h4>
            <div class="buttons">
                <a href="<?php echo admin_url('admin.php?page=rpam-import'); ?>" class="button button-primary"><?php echo __( 'Import', 'rp-ads-manager' ) . ' | RedPic Ads Manager'; ?></a>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>