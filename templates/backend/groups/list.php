<div class="wrap">
    <h1 class="wp-heading-inline"><?= esc_html( get_admin_page_title() ); ?></h1>
    <a href="<?= rpam_url( 'groups', 'view=editor' ) ?>" class="add-new-h2"><?= __( 'Create group', 'rp-ads-manager' ) ?></a>
    <hr class="wp-header-end">
    <div class="rpam-content">
        <form id="ads-filter" method="get">
            <input type="hidden" name="page" value="<?= $_GET['page']; ?>">
            <div class="tablenav top">
                <div class="alignleft actions bulkactions">
					<?= \RP\AdsManager\HTML\Form::select( 'bulk-at', $actions, false ) ?>
					<?= \RP\AdsManager\HTML\Form::inputSubmit( __( 'Apply', 'rp-ads-manager' ), [ 'class' => 'button' ] ); ?>
                </div>
            </div>
            <table class="wp-list-table widefat plugins ads-list striped">
                <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                        <input id="cb-select-all-1" type="checkbox" title="<?= __( 'Select all', 'rp-ads-manager' ) ?>">
                    </td>
                    <th scope="col" class="manage-column column-name column-primary"><?= __( 'Title', 'rp-ads-manager' ); ?></th>
                    <th scope="col" class="column-description"><?= __( 'Information', 'rp-ads-manager' ) ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if ( count( $records ) ): ?>
				<?php foreach ( $records as $record ): ?>
                    <tr>
                        <th scope="row" class="check-column">
                            <input id="cb-select-<?= $record['id'] ?>" type="checkbox" name="ids[]" value="<?= $record['id'] ?>" title="<?= __( 'Select', 'rp-ads-manager' ) ?>">
                        </th>
                        <td class="ad-title column-primary">
                            <strong><?= $record['title']; ?></strong>
                            <div class="row-actions visible">
                                <span class="0"><a href="<?= rpam_url( 'groups', 'view=editor&id=' . $record['id'] ) ?>"><?= __( 'Edit', 'rp-ads-manager' ) ?></a> | </span>
                                <span class="delete"><a href="<?= rpam_url( 'groups', 'action=delete&id=' . $record['id'] ) ?>" class="delete"><?= __( 'Delete', 'rp-ads-manager' ) ?></a></span>
                            </div>
                        </td>
                        <td class="column-description"></td>
                    </tr>
				<?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center"><?= __( 'No records found', 'rp-ads-manager' ) ?>, <a href="<?= rpam_url('groups', 'view=editor') ?>"><?= __('create new', 'rp-ads-manager') ?></a>.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            <div class="tablenav bottom">
                <div class="alignleft actions bulkactions">
					<?= \RP\AdsManager\HTML\Form::select( 'bulk-ab', $actions, false ) ?>
					<?= \RP\AdsManager\HTML\Form::inputSubmit( __( 'Apply', 'rp-ads-manager' ), [ 'class' => 'button' ] ); ?>
                </div>
            </div>
        </form>
        <div class="clear"></div>
    </div>
</div>