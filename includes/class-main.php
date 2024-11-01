<?php

defined( 'ABSPATH' ) || exit;

class SkyWeb_Projects {

	/**
	 * Bootstraps the class and hooks required actions.
	 */
	public function __construct() {
		register_activation_hook( SKYWEB_PROJECTS_FILE, array( $this, 'plugin_activate' ) );
		register_deactivation_hook( SKYWEB_PROJECTS_FILE, array( $this, 'plugin_deactivate' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts_and_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts_and_styles' ) );
		add_action( 'init', array( $this, 'register_projects_shortcodes' ) );
		add_action( 'init', array( $this, 'register_projects_ajax' ) );

		add_filter( 'template_include', array( $this, 'projects_templates' ) );

		add_action( 'after_setup_theme', array( $this, 'add_projects_thumbnail_size' ) );

		add_filter( 'manage_projects_posts_columns', array( $this, 'projects_admin_columns' ) );
		add_action( 'manage_projects_posts_custom_column', array( $this, 'projects_admin_column_image' ), 10, 2 );
	}

	/**
	 * Plugin activation.
	 */
	public function plugin_activate() {
		flush_rewrite_rules();
	}

	/**
	 * Plugin deactivation.
	 */
	public function plugin_deactivate() {
		unregister_post_type( 'projects' );
		unregister_taxonomy( 'project-type' );

		flush_rewrite_rules();
	}

	/**
	 * Enqueue admin styles.
	 */
	public function enqueue_admin_scripts_and_styles() {
		// Styles.
		wp_enqueue_style( SKYWEB_PROJECTS_SLUG . '-admin', plugin_dir_url( SKYWEB_PROJECTS_FILE ) . '/assets/css/skyweb-projects-admin.css', array(), SKYWEB_PROJECTS_VERSION );
	}

	/**
	 * Enqueue public styles.
	 */
	public function enqueue_public_scripts_and_styles() {
		// Styles.
		wp_enqueue_style( SKYWEB_PROJECTS_SLUG . '-public', plugin_dir_url( SKYWEB_PROJECTS_FILE ) . '/assets/css/skyweb-projects-public.css', array(), SKYWEB_PROJECTS_VERSION );

		// Scripts.
		wp_enqueue_script( SKYWEB_PROJECTS_SLUG . '-public',
			plugin_dir_url( SKYWEB_PROJECTS_FILE ) . '/assets/js/skyweb-projects-public.js',
			array( 'jquery' ),
			SKYWEB_PROJECTS_VERSION,
			true );

		wp_localize_script( SKYWEB_PROJECTS_SLUG . '-public', 'skyweb_projects_ajax',
			array(
				'url'   => esc_url( admin_url( 'admin-ajax.php' ) ),
				'nonce' => wp_create_nonce( 'skyweb-projects-nonce' ),
			)
		);
	}

	/**
	 * Register projects filter and list shortcodes.
	 */
	public function register_projects_shortcodes() {
		add_shortcode( 'skyweb_projects_filter', array( $this, 'projects_filter' ) );
		add_shortcode( 'skyweb_projects_list', array( $this, 'list_shortcode' ) );
		add_shortcode( 'skyweb_projects_navigation', array( $this, 'projects_navigation' ) );

	}

	/**
	 * Projects filter.
	 */
	public function projects_filter() {

		ob_start();
		?>

        <div class="projects-filter">
            <div class="projects-filter-wrapper">

                <label for="all_projects_types">
                    <input type="checkbox"
                           name="all_projects_types"
                           id="all_projects_types">

                    <span class="projects-filter-checkbox">
						<?php esc_html_e( 'All projects', 'skyweb-projects' ); ?>
                    </span>
                </label>

            </div>
        </div>

		<?php

		$terms = get_terms( array(
			'taxonomy' => 'project-type',
			'fields'   => 'id=>name',
		) );

		foreach ( $terms as $term_id => $term_name ) {
			?>

            <div class="projects-filter">
                <div class="projects-filter-wrapper">

                    <label for="<?php echo $term_id; ?>-projects-filter">
                        <input type="checkbox"
                               name="<?php echo $term_id; ?>-projects-filter"
                               id="<?php echo $term_id; ?>-projects-filter">

                        <span class="projects-filter-checkbox">
							<?php echo sanitize_text_field( $term_name ); ?>
                        </span>
                    </label>

                </div>
            </div>

			<?php
		}

		$html = ob_get_contents();
		ob_end_clean();

		$wrapper = '<div class="projects-filters">%s</div>';

		$html = sprintf( $wrapper, $html );

		return $html;
	}

	/**
	 * Projects list shortcode.
	 */
	public function list_shortcode( $atts = array() ) {

		$atts = shortcode_atts( array(
			'post_type'      => 'projects',
			'posts_per_page' => - 1,
			'terms'          => '',
		), $atts, 'skyweb_projects_list' );

		$args = array(
			'post_type'      => $atts['post_type'],
			'posts_per_page' => $atts['posts_per_page'],
		);

		$terms = ! empty( absint( $atts['terms'] ) ) ? $atts['terms'] : '';

		return $this->projects_list_render( $args, $terms );
	}

	/**
	 * Projects list rendering.
	 */
	public function projects_list_render( $args = array(), $terms = '' ) {

		$default_args = array(
			'post_type'      => 'projects',
			'posts_per_page' => - 1,
		);

		if ( ! empty( absint( $terms ) ) ) {

			$args['tax_query'] = array(
				array(
					'taxonomy' => 'project-type',
					'field'    => 'term_id',
					'terms'    => $terms,
					'operator' => 'IN',
				),
			);
		}

		$args = wp_parse_args( $args, $default_args );

		$projects = new WP_Query( $args );

		ob_start();
		?>

        <div class="ae-masonry ae-masonry-sm-2 ae-masonry-md-3 ae-masonry-xl-4">

			<?php

			if ( $projects->have_posts() ) {
				while ( $projects->have_posts() ) {

					$projects->the_post();
					?>

                    <div class="rk-item ae-masonry__item">
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<?php
							if ( has_post_thumbnail() ) {
								the_post_thumbnail();
							}
							?>

							<div class="project-item-meta">
								<h3><?php the_title(); ?></h3>

								<?php the_excerpt(); ?>

									<? //php esc_html_e( 'Details', 'skyweb-projects' ); ?>
							
							</div>
						</a>

                    </div>

					<?php
				}

			} else {

				esc_html_e( 'No projects found.', 'skyweb-projects' );
			}
			?>

        </div>

		<?php

		wp_reset_postdata();

		$html = ob_get_contents();
		ob_end_clean();

		$wrapper = '<div id="skyweb_projects_list" class="projects-container">%s</div>';

		$html = sprintf( $wrapper, $html );

		return $html;
	}

	/**
	 * Register projects filter AJAX handler.
	 */
	public function register_projects_ajax() {
		add_action( 'wp_ajax_skyweb_ajax_project_filter', array( $this, 'skyweb_ajax_project_filter' ) );
		add_action( 'wp_ajax_nopriv_skyweb_ajax_project_filter', array( $this, 'skyweb_ajax_project_filter' ) );
	}

	/**
	 * Projects filter AJAX handler.
	 */
	public function skyweb_ajax_project_filter() {

		check_ajax_referer( 'skyweb-projects-nonce', 'nonce' );

		$projects_types = isset( $_POST['projects_types'] ) ? (array) $_POST['projects_types'] : array();

		if ( ! empty( $projects_types ) ) {

			$projects_types = array_map( function ( $item ) {
				return absint( $item );
			}, $projects_types );

			if ( in_array( 'all_projects_types', $projects_types ) ) {

				echo $this->projects_list_render();

			} else {

				$args = array(
					'tax_query' => array(
						'relation' => 'OR',
						array(
							'taxonomy' => 'project-type',
							'field'    => 'term_id',
							'terms'    => $projects_types,
							'operator' => 'IN',
						),
					),
				);

				echo $this->projects_list_render( $args );
			}
		}

		wp_die();
	}

	/**
	 * Set template for projects list.
	 */
	public function projects_templates( $template ) {
		global $post;

		$single_template  = 'single-project.php';
		$archive_template = 'archive-projects.php';

		// Single page.
		if ( is_single() && $post->post_type === 'projects' ) {

			$local_single_template = locate_template( $single_template );
			$template              = $local_single_template ? $local_single_template : plugin_dir_path( SKYWEB_PROJECTS_FILE ) . 'templates/' . $single_template;
		}

		// Archive page.
		if ( is_archive() && $post->post_type === 'projects' ) {

			$local_archive_template = locate_template( $archive_template );
			$template               = $local_archive_template ? $local_archive_template : plugin_dir_path( SKYWEB_PROJECTS_FILE ) . 'templates/' . $archive_template;
		}

		return $template;
	}

	/**
	 * Add image size for projects list in admin panel.
	 */
	public function add_projects_thumbnail_size() {
		add_image_size( 'skyweb-projects-thumbnail', 60, 60, true );
	}

	/**
	 * Add image column to projects list in admin panel.
	 */
	public function projects_admin_columns( $columns ) {

		$columns['title'] = _x( 'Project', 'Post Type', 'skyweb-projects' );

		if ( current_theme_supports( 'post-thumbnails' ) ) {
			$columns = array_slice( $columns, 0, 1, true ) +
			           array( 'skyweb-projects-thumbnail' => __( 'Image', 'skyweb-projects' ) ) +
			           array_slice( $columns, 1, null, true );
		}

		return $columns;
	}

	/**
	 * Add project image to projects list in admin panel.
	 */
	public function projects_admin_column_image( $column_name, $id ) {

		if ( ! current_theme_supports( 'post-thumbnails' ) ) {
			return;
		}

		if ( 'skyweb-projects-thumbnail' == $column_name ) {

			$post_featured_image_id = get_post_thumbnail_id( $id );

			if ( $post_featured_image_id ) {
				$post_featured_image = wp_get_attachment_image_src( $post_featured_image_id, 'skyweb-projects-thumbnail' )[0];
			}

			echo '<a href="' . esc_url( get_edit_post_link( $id ) ) . '">';

			if ( $post_featured_image ) {

				the_post_thumbnail( 'skyweb-projects-thumbnail' );

			} else {

				echo '<span class="no-image dashicons dashicons-format-image">';
			}

			echo '</a>';
		}
	}

	/**
	 * Get project price.
	 *
	 * Returns:
	 * 1. 'Free' text if the project's price sets to zero.
	 * 2. False if the price is empty.
	 * 3. Formatted price in other case.
	 */
	public function get_project_price( $project_ID ) {

		$project_price = get_post_meta( $project_ID, 'skyweb_project_price', true );

		if ( $project_price === '0' ) {

			$project_price = esc_html__( 'Free', 'skyweb-projects' );

		} elseif ( empty( $project_price ) ) {

			$project_price = false;

		} else {

			$project_price = number_format_i18n( absint( $project_price ) ) . esc_html__( ' $', 'skyweb-projects' );
		}

		return $project_price;
	}

}

$skyweb_projects = new SkyWeb_Projects();