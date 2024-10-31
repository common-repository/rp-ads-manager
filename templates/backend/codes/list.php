<div class="wrap">
    <h1 class="wp-heading-inline"><?= esc_html( get_admin_page_title() ); ?></h1>
    <a href="<?= admin_url( 'admin.php?page=rpam-codes&view=editor' ) ?>"
       class="add-new-h2"><?= __( 'Create Ad', 'rp-ads-manager' ) ?></a>
    <hr class="wp-header-end">
	<?php if ( $updated ): ?>
        <div id="message-success" class="updated notice notice-success is-dismissible">
            <p><?= $updated ?></p>
            <button class="notice-dismiss"></button>
        </div>
	<?php endif; ?>
    <div class="rpam-content">
        <ul class="subsubsub">
            <li class="all"><a href="<?= admin_url( 'admin.php?page=rpam-codes' ) ?>"
			                   <?php if ( $filter['status'] == 'all' ): ?>class="current"<?php endif; ?>><?= __( 'All', 'rp-ads-manager' ); ?>
                    <span class="count">(<?= $counters['total']; ?>)</span></a> |
            </li>
            <li class="active"><a href="<?= admin_url( 'admin.php?page=rpam-codes&status=active' ) ?>"
			                      <?php if ( $filter['status'] == 'active' ): ?>class="current"<?php endif; ?>><?= __( 'Active', 'rp-ads-manager' ); ?>
                    <span class="count">(<?= $counters['active']; ?>)</span></a> |
            </li>
            <li class="inactive"><a href="<?= admin_url( 'admin.php?page=rpam-codes&status=inactive' ) ?>"
			                        <?php if ( $filter['status'] == 'inactive' ): ?>class="current"<?php endif; ?>><?= __( 'Inactive', 'rp-ads-manager' ); ?>
                    <span class="count">(<?= $counters['inactive']; ?>)</span></a></li>
        </ul>
        <form id="ads-filter" method="get">
            <input type="hidden" name="page" value="<?= $_GET['page']; ?>">
            <input type="hidden" name="status" class="ads_status_page" value="<?= $filter['status']; ?>">
            <div class="tablenav top">
                <div class="alignleft actions bulkactions">
					<?= \RP\AdsManager\HTML\Form::select( 'bulk-at', $actions, false ) ?>
					<?= \RP\AdsManager\HTML\Form::inputSubmit( __( 'Apply', 'rp-ads-manager' ), [ 'class' => 'button' ] ); ?>
                </div>
                <div class="alignleft actions">
					<?= \RP\AdsManager\HTML\Form::select( 'group', $groups, $filter['group'], [], __( 'All groups', 'rp-ads-manager' ) ) ?>
					<?= \RP\AdsManager\HTML\Form::select( 'p3e', $places, $filter['p3e'], [], __( 'All places', 'rp-ads-manager' ) ) ?>
					<?= \RP\AdsManager\HTML\Form::select( 'p6n', $positions, $filter['p6n'], [], __( 'All positions', 'rp-ads-manager' ) ) ?>
					<?= \RP\AdsManager\HTML\Form::inputSubmit( __( 'Filter', 'rp-ads-manager' ), [ 'class' => 'button' ] ); ?>
                </div>
                <br class="clear">
            </div>
            <table class="wp-list-table widefat plugins ads-list">
                <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                        <label class="screen-reader-text"
                               for="cb-select-all-1"><?= __( 'Select all', 'rp-ads-manager' ) ?></label><input
                                id="cb-select-all-1" type="checkbox">
                    </td>
                    <th scope="col"
                        class="manage-column column-name column-primary"><?= __( 'Ad record', 'rp-ads-manager' ); ?></th>
                    <th scope="col" class="manage-column column-categories"><?= __( 'Group', 'rp-ads-manager' ); ?></th>
                    <th scope="col"
                        class="manage-column column-description"><?= __( 'Options', 'rp-ads-manager' ); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if ( count( $records ) ): ?>
                    <?php foreach ( $records as $key => $record ): ?>
                        <tr<?php if ( $record['enabled'] ): ?> class="active"<?php endif; ?>>
                            <th scope="row" class="check-column">
                                <label class="screen-reader-text"
                                       for="cb-select-<?= $key ?>"><?= __( 'Select', 'rp-ads-manager' ) ?>
                                    rp-<?= $record['type'] ?>-<?= $key ?></label>
                                <input id="cb-select-<?= $key ?>" type="checkbox" name="ids[]" value="<?= $key ?>">
                            </th>
                            <td class="ad-title column-primary">
                                <strong><?= $record['title']; ?></strong>
                                <p class="description">rp-ads-<?= $key; ?></p>
                                <div class="row-actions visible">
                                <span class="0"><a
                                            href="<?= admin_url( 'admin.php?page=rpam-codes&view=editor&action=copy&id=' . $key ); ?>"><?= __( 'Duplicate', 'rp-ads-manager' ) ?></a> | </span>
                                    <span class="0"><a
                                                href="<?= admin_url( 'admin.php?page=rpam-codes&view=editor&id=' . $key ); ?>"><?= __( 'Edit', 'rp-ads-manager' ) ?></a> | </span>
                                    <?php if ( $record['enabled'] ): ?>
                                        <span class="deactivate"><a
                                                    href="<?= admin_url( 'admin.php?page=rpam-codes&action=deactivate&id=' . $key ) ?>"><?= __( 'Deactivate', 'rp-ads-manager' ) ?></a> | </span>
                                    <?php else: ?>
                                        <span class="activate"><a
                                                    href="<?= admin_url( 'admin.php?page=rpam-codes&action=activate&id=' . $key ) ?>"
                                                    class="edit"><?= __( 'Activate', 'rp-ads-manager' ) ?></a> | </span>
                                    <?php endif; ?>
                                    <span class="delete"><a
                                                href="<?= admin_url( 'admin.php?page=rpam-codes&action=delete&id=' . $key ) ?>"
                                                class="delete"><?= __( 'Delete', 'rp-ads-manager' ) ?></a></span>
                                </div>
                            </td>
                            <td class="categories column-categories"><?= null == $record['group'] ? __( 'No group', 'rp-ads-manager' ) : $record['group'] ?></td>
                            <td class="column-description desc">
                                <div class="ad-description">
                                    <?php if ( ! empty( $record['places'] ) ): ?>
                                        <b><?= __( 'Displays at', 'rp-ads-manager' ) ?>:</b>
                                        <ul>
                                            <?php foreach ( $record['places'] as $place ): ?>
                                                <li><?= $place['title'] ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center"><?= __( 'No records found', 'rp-ads-manager' ) ?>, <a href="<?= rpam_url('codes', 'view=editor') ?>"><?= __('create new', 'rp-ads-manager') ?></a>.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            <div class="tablenav bottom">

                <div class="alignleft actions bulkactions">
					<?= \RP\AdsManager\HTML\Form::select( 'bulk-ab', $actions, false ) ?>
					<?= \RP\AdsManager\HTML\Form::inputSubmit( __( 'Apply', 'rp-ads-manager' ), [ 'class' => 'button' ] ); ?>
                </div>
                <div class="alignleft actions">
                </div>
            </div>
        </form>
        <div class="clear"></div>
    </div>
</div>

<script>
    (function ($) {
        $('a.delete').on('click', function (e) {
            var confirm = window.confirm('<?php _e( 'Do you want to delete this object?', 'rp-ads-manager' ); ?>');
            if (!confirm) {
                e.preventDefault();
            }
        });
    })(jQuery);
</script>