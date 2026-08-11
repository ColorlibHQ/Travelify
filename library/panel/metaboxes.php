<?php
/**
 * Travelify Meta Boxes
 *
 * Per-post/per-page sidebar layout override.
 */

/****************************************************************************************/

add_action( 'add_meta_boxes', 'travelify_add_custom_box' );
/**
 * Add Meta Boxes.
 *
 * Add Meta box in page and post post types.
 */
function travelify_add_custom_box() {
	add_meta_box(
		'siderbar-layout',
		__( 'Select layout for this specific Page only ( Note: This setting only reflects if page Template is set as Default Template and Blog Type Templates.)', 'travelify' ),
		'travelify_sidebar_layout',
		'page'
	);
	add_meta_box(
		'siderbar-layout',
		__( 'Select layout for this specific Post only', 'travelify' ),
		'travelify_sidebar_layout',
		'post'
	);
}

/****************************************************************************************/

/**
 * The sidebar layouts offered by the per-post metabox.
 *
 * Built on demand rather than at file scope: the labels are translated, and
 * translating before `init` trips WordPress 6.7+'s _load_textdomain_just_in_time
 * notice on every request.
 *
 * @return array Layout definitions keyed by slug.
 */
function travelify_get_sidebar_layouts() {
	$images = get_template_directory_uri() . '/library/panel/images/';

	return array(
		'default-sidebar'       => array(
			'id'        => 'travelify_sidebarlayout',
			'value'     => 'default',
			/* translators: %s: link to the Customizer layout options. */
			'label'     => sprintf(
				esc_html__( 'Default Layout Set in %s', 'travelify' ),
				'<a href="' . esc_url( admin_url( 'customize.php?autofocus[section]=travelify_layout_options' ) ) . '">' . esc_html__( 'Theme Settings', 'travelify' ) . '</a>'
			),
			'thumbnail' => '',
		),
		'no-sidebar'            => array(
			'id'        => 'travelify_sidebarlayout',
			'value'     => 'no-sidebar',
			'label'     => esc_html__( 'No sidebar', 'travelify' ),
			'thumbnail' => $images . 'no-sidebar.png',
		),
		'no-sidebar-full-width' => array(
			'id'        => 'travelify_sidebarlayout',
			'value'     => 'no-sidebar-full-width',
			'label'     => esc_html__( 'No sidebar, Full Width', 'travelify' ),
			'thumbnail' => $images . 'no-sidebar-fullwidth.png',
		),
		'no-sidebar-one-column' => array(
			'id'        => 'travelify_sidebarlayout',
			'value'     => 'no-sidebar-one-column',
			'label'     => esc_html__( 'No Sidebar, One Column', 'travelify' ),
			'thumbnail' => $images . 'one-column.png',
		),
		'left-sidebar'          => array(
			'id'        => 'travelify_sidebarlayout',
			'value'     => 'left-sidebar',
			'label'     => esc_html__( 'Left sidebar', 'travelify' ),
			'thumbnail' => $images . 'left-sidebar.png',
		),
		'right-sidebar'         => array(
			'id'        => 'travelify_sidebarlayout',
			'value'     => 'right-sidebar',
			'label'     => esc_html__( 'Right sidebar', 'travelify' ),
			'thumbnail' => $images . 'right-sidebar.png',
		),
	);
}

add_action( 'init', 'travelify_setup_sidebar_layout_global' );
/**
 * Keep the legacy $sidebar_layout global populated for child themes.
 *
 * Nothing in the theme reads it any more; travelify_get_sidebar_layouts() is
 * the supported way to reach this data.
 */
function travelify_setup_sidebar_layout_global() {
	$GLOBALS['sidebar_layout'] = travelify_get_sidebar_layouts();
}

/****************************************************************************************/

/**
 * Displays metabox to for sidebar layout
 */
function travelify_sidebar_layout( $post ) {
	// Use nonce for verification.
	wp_nonce_field( 'travelify_save_sidebar_layout', 'custom_meta_box_nonce' );

	$meta = get_post_meta( $post->ID, 'travelify_sidebarlayout', true );
	if ( empty( $meta ) ) {
		$meta = 'default';
	}
	?>
	<table id="sidebar-metabox" class="form-table" width="100%">
		<tbody>
			<tr>
				<?php
				foreach ( travelify_get_sidebar_layouts() as $field ) {
					if ( '' === $field['thumbnail'] ) :
						?>
						<label class="description">
						<input type="radio" name="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" <?php checked( $field['value'], $meta ); ?> />&nbsp;&nbsp;<?php echo wp_kses( $field['label'], array( 'a' => array( 'href' => array() ) ) ); ?>
						</label>
					<?php else : ?>
						<td>
							<label class="description">
							<span><img src="<?php echo esc_url( $field['thumbnail'] ); ?>" width="136" height="122" alt="" /></span><br />
							<input type="radio" name="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" <?php checked( $field['value'], $meta ); ?> />&nbsp;&nbsp;<?php echo esc_html( $field['label'] ); ?>
							</label>
						</td>
						<?php
					endif;
				}
				?>
			</tr>
		</tbody>
	</table>
	<?php
}

/****************************************************************************************/

add_action( 'save_post', 'travelify_save_custom_meta' );
/**
 * Save the custom metabox data.
 *
 * @param int $post_id Post being saved.
 * @hooked to save_post hook
 */
function travelify_save_custom_meta( $post_id ) {

	// Verify the nonce before proceeding.
	if ( ! isset( $_POST['custom_meta_box_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['custom_meta_box_nonce'] ) ), 'travelify_save_sidebar_layout' ) ) {
		return;
	}

	// Stop WP from clearing custom fields on autosave.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['travelify_sidebarlayout'] ) ) {
		return;
	}

	$submitted = sanitize_key( wp_unslash( $_POST['travelify_sidebarlayout'] ) );

	// Only ever store a layout the theme actually offers.
	$allowed = wp_list_pluck( travelify_get_sidebar_layouts(), 'value' );

	if ( ! in_array( $submitted, $allowed, true ) ) {
		return;
	}

	if ( 'default' === $submitted ) {
		delete_post_meta( $post_id, 'travelify_sidebarlayout' );
		return;
	}

	update_post_meta( $post_id, 'travelify_sidebarlayout', $submitted );
}
