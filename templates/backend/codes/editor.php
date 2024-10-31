<div class="wrap">
    <h1 class="wp-heading-inline"><?= __( is_null( $record['id'] ) ? 'Create code' : 'Edit code', 'rp-ads-manager' ) ?></h1>
    <hr class="wp-header-end">
    <div id="message-error" class="error notice notice-error" style="display: none;">
        <p></p>
    </div>

    <div class="rpam-content">
        <form id="rpam-form"
              action="<?= admin_url( 'admin.php?page=rpam-codes&view=editor' . ( is_null( $record['id'] ) ? '' : '&id=' . $record['id'] ) ) ?>"
              method="post">
            <div>
                <div id="titlediv">
					<?= \RP\AdsManager\HTML\Form::input( 'block[title]', $record['title'], [
						'id'           => 'title',
						'autocomplete' => 'off',
						'placeholder'  => __( 'Code block title', 'rp-ads-manager' )
					] ); ?>
                </div>

                <h2 class="nav-tab-wrapper">
                    <a href="#" class="nav-tab nav-tab-active"><?= __( 'Main options', 'rp-ads-manager' ) ?></a>
                    <span class="nav-tab"><?= __( 'Targeting', 'rp-ads-manager' ) ?>
                        <i>(<?= __( 'soon', 'rp-ads-manager' ) ?>)</i></span>
                </h2>

                <div class="rp-flex-container">
                    <div class="rp-form-block-1">
                        <label for="rp-ads-manager-code"
                               class="code-editor"><?= __( 'Code (js/html)', 'rp-ads-manager' ); ?>:</label>
						<?= \RP\AdsManager\HTML\Form::textarea( 'block[code]', $record['code'], [
							'id'   => 'rp-ads-manager-code',
							'cols' => '70',
							'rows' => '15'
						] ); ?>

                        <div class="separate-init-code-wrapper">
                            <div class="code-editor">
                                <label><?= \RP\AdsManager\HTML\Form::checkbox(
										'block[use_init]',
										'yes',
										! is_null( $record['init'] ),
										[
											'id' => 'separate-init-code-checker'
										]
									); ?><?php _e( 'Use separated init code (js only) and output it to footer', 'rp-ads-manager' ); ?>
                                    :</label>
                            </div>
                            <div class="text-area-holder"
							     <?php if ( is_null( $record['init'] ) ): ?>style="display:none;"<?php endif; ?>>
								<?= \RP\AdsManager\HTML\Form::textarea( 'block[init]', $record['init'], [
									'id'   => 'rp-ads-manager-init-code',
									'cols' => '70',
									'rows' => '15'
								] ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="rp-form-block-2">
                        <span class="code-editor"><?= __( 'Select group', 'rp-ads-manager' ); ?></span>
                        <div class="group-container">
							<?= \RP\AdsManager\HTML\Form::select( 'block[group]', $groups, $record['group'], [
								'id'    => 'rpam-group',
								'title' => 'group',
								'class' => 'w-100'
							] ); ?>
                            <p class="description"><?= __('Select existing or enter new value', 'rp-ads-manager') ?></p>
                        </div>
                        <span class="code-editor"><?= __( 'Where to display block', 'rp-ads-manager' ); ?></span>
						<?php foreach ( $places as $key => $place ): ?>
							<?php $hidden = empty( $record['places'] ) || ! array_key_exists( $key, $record['places'] ); ?>
                            <div class="place-container">
                                <div class="p-tb">
                                    <label><?= \RP\AdsManager\HTML\Form::checkbox(
											'block[places][' . $key . ']',
											'yes',
											! $hidden,
											[
												'id'    => 'rpam-' . $key,
												'class' => 'show-positions'
											],
											empty( $place['positions'] )
										); ?><b><?php echo $place['title'] ?></b></label>
									<?php if ( empty( $place['positions'] ) &&  $key == 'widget' ): ?>
                                        <p class="description"><?= __( 'You must add widget to page to use this option. Go to', 'rp-ads-manager' ); ?>
                                            <a href="<?= admin_url( 'widgets.php' ); ?>"><?= __( 'Widgets' ); ?></a>
                                        </p>
                                    <?php elseif ( empty( $place['positions'] ) &&  $key == 'shortcode' ): ?>
                                        <p class="description"><?= __( 'You must add shortcode to use this option. Go to', 'rp-ads-manager' ); ?>
                                            <a href="<?= rpam_url( 'shortcodes' ); ?>"><?= __( 'Shortcodes', 'rp-ads-manager' ); ?></a>
                                        </p>
									<?php endif; ?>
                                </div>
                                <div class="place-options" <?php if ( $hidden ): ?>style="display: none"<?php endif; ?>>
                                    <div class="f-block">
                                        <div class="f-item-1 label-holder"><?= __( 'Aligment', 'rp-ads-manager' ) ?></div>
                                        <div class="f-item-2">
                                            <?= \RP\AdsManager\HTML\Form::select(
	                                            'block[places][' . $key . '][aligment]',
                                                [
                                                    'none' => __( 'none', 'rp-ads-manager' ),
                                                    'left' => __( 'left', 'rp-ads-manager' ),
                                                    'center' => __( 'center', 'rp-ads-manager' ),
                                                    'right' => __( 'right', 'rp-ads-manager' )
                                                ],
	                                            array_key_exists($key, $record['places']) ? $record['places'][ $key ]['aligment'] : 'none',
                                                [],
                                                false,
	                                            $hidden
                                            ) ?>
                                        </div>
                                    </div>
                                    <div class="f-block">
                                        <div class="f-item-1 label-holder"><?= __( 'Custom CSS class', 'rp-ads-manager' ) ?></div>
                                        <div class="f-item-2"><?= \RP\AdsManager\HTML\Form::input(
												'block[places][' . $key . '][class]',
												$hidden ? '' : $record['places'][ $key ]['class'],
												[
													'id'    => 'rp-ads-' . $key . '-css',
													'class' => 'w-100'
												],
												$hidden
											); ?></div>
                                    </div>
									<?php if ( ! in_array( $key, [ 'homepage', 'archive', 'shortcode' ] ) ): ?>
                                        <div class="f-block">
                                            <div class="f-item-1 select-holder">
												<?= \RP\AdsManager\HTML\Form::select(
													'block[places][' . $key . '][policy]',
													[
														'none' => __( 'Show everywhere', 'rp-ads-manager' ),
														'incl' => __( 'Show on', 'rp-ads-manager' ),
														'excl' => __( 'Do not show on', 'rp-ads-manager' )
													],
													$hidden ? null : $record['places'][ $key ]['policy'],
													[ 'title' => 'policy' ],
													false,
													$hidden
												); ?>
                                            </div>
                                            <div class="f-item-2">
												<?= \RP\AdsManager\HTML\Form::input(
													'block[places][' . $key . '][ids]',
													$hidden || ! array_key_exists( 'ids', $record['places'][ $key ] ) ? '' : $record['places'][ $key ]['ids'],
													[
														'id'    => 'rp-ads-' . $key . '-ids',
														'class' => 'w-100',
														'title' => 'ids'
													],
													$hidden || ! array_key_exists( 'ids', $record['places'][ $key ] )
												); ?>
                                            </div>
                                        </div>
									<?php endif; ?>
                                    <div class="f-block">
                                        <div class="f-item-1 label-holder"><?= __( 'Position', 'rp-ads-manager' ) ?></div>
                                        <div class="f-item-2">
											<?= \RP\AdsManager\HTML\Form::select(
												'block[places][' . $key . '][position]',
												$place['positions'],
												$hidden ? null : $record['places'][ $key ]['position'],
												[ 'id' => 'rpam-' . $key . '-position' ],
												[ 'title' => __( 'Nowhere', 'rp-ads-manager' ), 'value' => 'none' ],
												$hidden
											); ?>
                                        </div>
                                    </div>
                                    <div class="view-options"
									     <?php if ( $hidden || ! array_key_exists( 'number', $record['places'][ $key ] ) ): ?>style="display: none;"<?php endif; ?>>
                                        <label><?php _e( 'number', 'rp-ads-manager' ); ?> <input
                                                    type="number"
                                                    name="block[places][<?php echo $key ?>][number]"
													<?php if ( $hidden || ! array_key_exists( 'number', $record['places'][ $key ] ) ): ?>disabled<?php elseif ( array_key_exists( 'number', $record['places'][ $key ] ) ): ?> value="<?php echo $record['places'][ $key ]['number'] ?>"<?php endif; ?>
                                                    class="w-50p view-option"></label>
                                        <label><?php _e( 'or', 'rp-ads-manager' ); ?> <input type="checkbox"
                                                                                             name="block[places][<?php echo $key ?>][in_the_end]"
                                                                                             class="view-option"
										                                                     <?php if ( $hidden || ! in_array( $record['places'][ $key ]['position'], $hasViewOptions ) ): ?>disabled<?php endif; ?> <?php if ( ! $hidden && array_key_exists( 'in_the_end', $record['places'][ $key ] ) ): ?> checked <?php endif; ?>> <?php _e( 'in the end if not enought elements', 'rp-ads-manager' ); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr>
						<?php endforeach; ?>
                    </div>
                    <div class="rp-form-footer">
                        <div class="buttons">
                            <a href="<?= admin_url( 'admin.php?page=rpam-codes' ); ?>"
                               class="button"><?= __( 'Back', 'rp-ads-manager' ); ?></a>
                            <button class="button button-primary"><?php _e( 'Save', 'rp-ads-manager' ); ?></button>
							<?php if ( ! is_null( $record['id'] ) ): ?>
                                <a href="<?php echo admin_url( 'admin.php?page=rpam-codes&action=delete&id=' . $record['id'] ) ?>"
                                   class="button delete"><?php _e( 'Delete', 'rp-ads-manager' ); ?></a>
							<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var rpam_errors = {
        'title': '<?= __( 'Enter <b>title</b> min: 2 symbols', 'rp-ads-manager' ); ?>',
        'number': '<?= __( 'You must enter <b>number</b> with this option selected', 'rp-ads-manager' ); ?>',
        'code': '<?= __( 'Enter <b>block code</b> min: 10 symbols', 'rp-ads-manager' ); ?>',
        'init': '<?= __( 'Enter <b>init code</b> min: 10 symbols or disable it', 'rp-ads-manager' ); ?>',
        'policy': '<?= __( 'Enter <b>ids</b> with this display policy', 'rp-ads-manager' ); ?>',
        'group': '<?= __( 'Enter <b>Group name</b> min: 2 symbols', 'rp-ads-manager' ) ?>'
    };

    (function ($) {
        $('.delete').on('click', function (e) {
            var confirm = window.confirm('<?php _e( 'Do you want to delete this object?', 'rp-ads-manager' ); ?>');
            if (!confirm) {
                e.preventDefault();
            }
        });
    })(jQuery);

</script>
