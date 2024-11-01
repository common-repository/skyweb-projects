<?php

defined( 'ABSPATH' ) || exit;

class SkyWeb_Projects_Post_Type {

	/**
	 * Bootstraps the class and hooks required actions.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'save_post_projects', array( $this, 'save_meta_box' ) ); // Dynamic hook save_post_{type}
	}

	/**
	 * Register projects post type and taxonomy.
	 */
	public function register_post_type() {

		$post_type      = 'project';
		$taxonomy       = 'project-type';
		$post_label     = _x( 'Project', 'Post Type', 'skyweb-projects' );
		$taxonomy_label = _x( 'Project Type', 'Taxonomy', 'skyweb-projects' );

		// Post type.
		// https://developer.wordpress.org/reference/functions/get_post_type_labels/
		$post_type_labels = array(
			'name'                     => sprintf( _x( '%ss', 'Post Type', 'skyweb-projects' ), $post_label ),
			'singular_name'            => _x( $post_label, 'Post Type', 'skyweb-projects' ),
			'add_new'                  => _x( 'Add New', 'Post Type', 'skyweb-projects' ),
			'add_new_item'             => sprintf( _x( 'Add New %s', 'Post Type', 'skyweb-projects' ), $post_label ),
			'edit_item'                => sprintf( _x( 'Edit %s', 'Post Type', 'skyweb-projects' ), $post_label ),
			'new_item'                 => sprintf( _x( 'New %s', 'Post Type', 'skyweb-projects' ), $post_label ),
			'view_item'                => sprintf( _x( 'View %s', 'Post Type', 'skyweb-projects' ), $post_label ),
			'view_items'               => sprintf( _x( 'View %ss', 'Post Type', 'skyweb-projects' ), $post_label ),
			'search_items'             => sprintf( _x( 'Search %ss', 'Post Type', 'skyweb-projects' ), $post_label ),
			'not_found'                => sprintf( _x( 'No %ss found', 'Post Type', 'skyweb-projects' ), $post_label ),
			'not_found_in_trash'       => sprintf( _x( 'No %ss found in Trash', 'Post Type', 'skyweb-projects' ), $post_label ),
			'parent_item_colon'        => sprintf( _x( 'Parent %s:', 'Post Type', 'skyweb-projects' ), $post_label ),
			'all_items'                => sprintf( _x( 'All %ss', 'Post Type', 'skyweb-projects' ), $post_label ),
			'archives'                 => sprintf( _x( '%s Archives', 'Post Type', 'skyweb-projects' ), $post_label ),
			'attributes'               => sprintf( _x( '%s Attributes', 'Post Type', 'skyweb-projects' ), $post_label ),
			'insert_into_item'         => sprintf( _x( 'Insert into %s', 'Post Type', 'skyweb-projects' ), $post_label ),
			'uploaded_to_this_item'    => sprintf( _x( 'Uploaded to this %s', 'Post Type', 'skyweb-projects' ), $post_label ),
			// 'featured_image'           => __( 'Featured Image', 'skyweb-projects' ),
			// 'set_featured_image'       => __( 'Set featured image', 'skyweb-projects' ),
			// 'remove_featured_image'    => __( 'Remove featured image', 'skyweb-projects' ),
			// 'use_featured_image'       => __( 'Use as featured image', 'skyweb-projects' ),
			'menu_name'                => sprintf( _x( '%ss', 'Post Type', 'skyweb-projects' ), $post_label ),
			'filter_items_list'        => sprintf( _x( 'Filter %ss list', 'Post Type', 'skyweb-projects' ), $post_label ),
			'items_list_navigation'    => sprintf( _x( '%ss list navigation', 'Post Type', 'skyweb-projects' ), $post_label ),
			'items_list'               => sprintf( _x( '%ss list', 'Post Type', 'skyweb-projects' ), $post_label ),
			'name_admin_bar'           => _x( $post_label, 'Post Type', 'skyweb-projects' ),
			'item_published'           => sprintf( _x( '%s published.', 'Post Type', 'skyweb-projects' ), $post_label ),
			'item_published_privately' => sprintf( _x( '%s published privately.', 'Post Type', 'skyweb-projects' ), $post_label ),
			'item_reverted_to_draft'   => sprintf( _x( '%s reverted to draft.', 'Post Type', 'skyweb-projects' ), $post_label ),
			'item_scheduled'           => sprintf( _x( '%s scheduled.', 'Post Type', 'skyweb-projects' ), $post_label ),
			'item_updated'             => sprintf( _x( '%s updated.', 'Post Type', 'skyweb-projects' ), $post_label ),
		);

		// https://developer.wordpress.org/reference/functions/register_post_type/
		$post_type_args = array(
			'label'                => sprintf( _x( '%ss', 'Post Type', 'skyweb-projects' ), $post_label ),
			'labels'               => $post_type_labels,
			'description'          => sprintf( _x( 'Managing your %ss.', 'Post Type description', 'skyweb-projects' ), $post_label ),
			'public'               => true,
			'hierarchical'         => false,
			// 'exclude_from_search' => false, // Default: value of the opposite of 'public'
			// 'publicly_queryable'  => true, // If no value is specified for 'exclude_from_search' it inherits value from 'public'
			// 'show_ui'             => true, // If no value is specified for 'exclude_from_search' it inherits value from 'public'
			// 'show_in_nav_menus'   => true, // If no value is specified for 'exclude_from_search' it inherits value from 'public'
			'show_in_menu'         => true,
			'show_in_admin_bar'    => true,
			// Must be true to enable the Gutenberg editor.
			'show_in_rest'         => true,
			// 'rest_base'   => '',
			// 'rest_controller_class'   => '',
			'menu_position'        => 8,
			'menu_icon'            => 'dashicons-portfolio',
			'capability_type'      => 'post',
			// 'capabilities' => array(),
			'map_meta_cap'         => true,
			'supports'             => array(
				'title',
				'editor',
				'author',
				'thumbnail',
				'excerpt',
				'trackbacks',
				'custom-fields',
				'comments',
				'revisions',
				'page-attributes'
			),
			'register_meta_box_cb' => array( $this, 'add_meta_box' ),
			'taxonomies'           => array( $taxonomy ),
			'has_archive'          => true,
			'rewrite'              => array(
				// 'slug'       => '', // Defaults to $post_type key
				// 'with_front' => true, // Default true
				// 'feeds' => 'true', // Default has_archive value
				// 'pages' => true, // Default true
				// 'ep_mask' => EP_PERMALINK, // Default EP_PERMALINK
			),
			// 'query_var'     => 'projects', // Default $post_type key
			'can_export'           => true,
			'delete_with_user'     => null,
			'_builtin'             => false,
			// '_edit_link' => 'post.php?post=%d',
		);

		register_post_type( $post_type . 's', $post_type_args );

		// Taxonomy.
		// https://developer.wordpress.org/reference/functions/get_taxonomy_labels/
		$taxonomy_labels = array(
			'name'                       => sprintf( _x( '%ss', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'singular_name'              => _x( $taxonomy_label, 'Taxonomy', 'skyweb-projects' ),
			'search_items'               => sprintf( _x( 'Search %ss', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'popular_items'              => sprintf( _x( 'Popular %ss', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'all_items'                  => sprintf( _x( 'All %ss', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => sprintf( _x( 'Edit %s', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'view_item'                  => sprintf( _x( 'View %s', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'update_item'                => sprintf( _x( 'Update %s', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'add_new_item'               => sprintf( _x( 'Add New %s', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'new_item_name'              => sprintf( _x( 'New %s Name', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'separate_items_with_commas' => sprintf( _x( 'Separate %ss with commas', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'add_or_remove_items'        => sprintf( _x( 'Add or remove %ss', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'choose_from_most_used'      => sprintf( _x( 'Choose from the most used %ss', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'not_found'                  => sprintf( _x( 'No %ss found', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'no_terms'                   => sprintf( _x( 'No %ss', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'items_list_navigation'      => sprintf( _x( '%ss', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'items_list'                 => sprintf( _x( '%ss', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'most_used'                  => sprintf( _x( 'Most Used %ss', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
			'back_to_items'              => sprintf( _x( 'Back to %ss', 'Taxonomy', 'skyweb-projects' ), $taxonomy_label ),
		);

		// https://developer.wordpress.org/reference/functions/register_taxonomy/
		$taxonomy_args = array(
			'labels'            => $taxonomy_labels,
			'description'       => sprintf( _x( '%ss of %ss', 'Taxonomy description', 'skyweb-projects' ), $taxonomy_label, $post_label ),
			'public'            => true,
			// 'publicly_queryable' => true, // Inherited from 'public'
			// 'show_ui'            => true, // Inherited from 'public'
			// 'show_in_nav_menus'  => true, // Inherited from 'public'
			// 'show_in_menu'  => true, // Default is inherited from 'show_ui'
			'hierarchical'      => false,
			// Must be true to enable the Gutenberg editor.
			'show_in_rest'      => true,
			// 'rest_base'   => '',
			// 'rest_controller_class'   => '',
			'show_tagcloud'     => false, // Default is inherited from 'show_ui'
			// 'show_in_quick_edit' => ture, // Default is inherited from 'show_ui'
			'show_admin_column' => true,
			// 'meta_box_cb' => '',
			// 'meta_box_sanitize_cb' => '',
			// 'capabilities'  => array(
			// 'manage_terms' => 'manage_categories',
			// 'edit_terms' => 'manage_categories',
			// 'delete_terms' => 'manage_categories',
			// 'assign_terms' => 'manage_categories',
			// ),
			// 'rewrite'       => array(
			// 'slug'       => '', //  Default $taxonomy key
			// 'with_front' => true,
			// 'hierarchical' => false,
			// 'ep_mask' => EP_NONE,
			// ),
			// 'query_var'     => 'project-type', // Default $taxonomy key
			// 'update_count_callback' => '',
			// 'default_term' => array(
			// 'name' => '',
			// 'slug' => '',
			// 'description' => '',
			// ),
			// '_builtin' => false,
		);

		register_taxonomy( $taxonomy, $post_type . 's', $taxonomy_args );
	}

	/**
	 * Add projects meta box.
	 */
	public function add_meta_box() {
		add_meta_box(
			'skyweb_projects_meta_box', // ID
			__( 'Project Information', 'skyweb-projects' ),
			array( $this, 'display_meta_box' ),
			'projects', // Post type
			'normal',
			'default'
		);
	}

	/**
	 * Returns array of fields.
	 */
	public function get_fields() {

		$fields = apply_filters( 'skyweb_projects_add_fields', array(
				'year'  => array(
					'title'    => esc_html__( 'Year:', 'skyweb-projects' ),
					'type'     => 'number',
					'name'     => 'skyweb_project_year',
					'id'       => 'skyweb_project_year',
					'class'    => '',
					'desc'     => sprintf( esc_html__( 'When was the project completed? Example: %s', 'skyweb-projects' ), date( 'Y' ) ),
					'min'      => 2000,
					'step'     => 1,
					'sanitize' => 'absint',
				),
				'price' => array(
					'title'    => esc_html__( 'Price:', 'skyweb-projects' ),
					'type'     => 'number',
					'name'     => 'skyweb_project_price',
					'id'       => 'skyweb_project_price',
					'class'    => '',
					'desc'     => esc_html__( 'What the cost of the project? Example: 1000', 'skyweb-projects' ),
					'min'      => 1,
					'step'     => 1,
					'sanitize' => 'absint',
				),
				'link'  => array(
					'title'    => esc_html__( 'Link:', 'skyweb-projects' ),
					'type'     => 'text',
					'name'     => 'skyweb_project_link',
					'id'       => 'skyweb_project_link',
					'class'    => 'regular-text',
					'desc'     => esc_html__( 'Link to a live project. Example: https://skyweb.site', 'skyweb-projects' ),
					'min'      => '',
					'step'     => '',
					'sanitize' => 'url',
				),
			)
		);

		return $fields;
	}

	/**
	 * Display projects meta box fields.
	 */
	public function display_meta_box( $post ) {

		echo '<div class="skyweb-field-container">';

		do_action( 'skyweb_project_admin_form_start' );

		foreach ( $this->get_fields() as $field ) {

			$value = get_post_meta( $post->ID, $field['name'], true );

			echo '<div class="field-row">';

			printf(
				'<label for="%1$s">%2$s</label><br/>',
				esc_attr( $field['name'] ),
				esc_html( $field['title'] )
			);

			if ( $field['type'] === 'number' ) {
				printf( '<input type="%1$s" name="%2$s" id="%3$s" value="%4$s" class="%5$s" min="%6$s" step="%7$s"> ',
					esc_attr( $field['type'] ),
					esc_attr( $field['name'] ),
					esc_attr( $field['id'] ),
					esc_attr( $value ),
					esc_attr( $field['class'] ),
					absint( $field['min'] ),
					absint( $field['step'] ),
				);
			} else {
				printf( '<input type="%1$s" name="%2$s" id="%3$s" value="%4$s" class="%5$s"> ',
					esc_attr( $field['type'] ),
					esc_attr( $field['name'] ),
					esc_attr( $field['id'] ),
					esc_attr( $value ),
					esc_attr( $field['class'] ),
				);
			}

			echo '<br/><small>' . esc_attr( $field['desc'] ) . '</small>';

			echo '</div>';
		}

		do_action( 'skyweb_project_admin_form_end' );

		echo '</div>';

		wp_nonce_field( 'skyweb_project_nonce', 'skyweb_project_nonce_field' );
	}

	/**
	 * Save projects meta box fields.
	 */
	public function save_meta_box( $post_id ) {

		if ( ! isset( $_POST['skyweb_project_nonce_field'] ) ) {
			return $post_id;
		}

		check_ajax_referer( 'skyweb_project_nonce', 'skyweb_project_nonce_field' );

		/* if ( ! wp_verify_nonce( sanitize_key( $_POST['skyweb_project_nonce_field'] ), 'skyweb_project_nonce' ) ) {
			return $post_id;
		} */

		/* if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		} */

		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return $post_id;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		foreach ( $this->get_fields() as $field ) {

			if ( isset( $_POST[ $field['name'] ] ) ) {

				if ( '' === $_POST[ $field['name'] ] ) {

					$value = '';

				} elseif ( 'absint' == $field['sanitize'] ) {

					$value = absint( wp_unslash( $_POST[ $field['name'] ] ) );

				} elseif ( 'url' == $field['sanitize'] ) {

					$value = esc_url_raw( wp_unslash( $_POST[ $field['name'] ] ) );

				} else {

					$value = sanitize_text_field( wp_unslash( $_POST[ $field['name'] ] ) );
				}

				update_post_meta( $post_id, $field['name'], $value );

			} else {

				delete_post_meta( $post_id, $field['name'] );
			}
		}

		do_action( 'skyweb_project_admin_save', $post_id, $_POST );
	}
}

$skyweb_projects_post_type = new SkyWeb_Projects_Post_Type();