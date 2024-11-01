<?php

defined( 'ABSPATH' ) || exit;

class SkyWeb_Projects_Navigation {

	/**
	 * Projects navigation layout.
	 */
	public function projects_navigation_layout( $id, $title, $notice = '', $type = 'previous' ) {

		if ( ! in_array( $type, array( 'previous', 'next' ), true ) ) {
			return esc_html__( 'Navigation type is not set.', 'skyweb-projects' );
		}

		if ( $notice && in_array( $notice, array( 'first', 'last' ), true ) ) {

			switch ( $notice ) {
				case 'first':
					$notice = esc_attr__( 'The first project', 'skyweb-projects' );
					break;
				case 'last':
					$notice = esc_attr__( 'The last project', 'skyweb-projects' );
					break;
				default:
					$notice = '';
			}
		}

		if ( empty( $notice ) ) {

			switch ( $type ) {
				case 'previous':
					$notice = esc_attr__( 'Previous project', 'skyweb-projects' );
					break;
				case 'next':
					$notice = esc_attr__( 'Next project', 'skyweb-projects' );
					break;
				default:
					$notice = '';
			}
		}

		$id            = absint( $id );
		$title         = sanitize_text_field( $title );
		$thumbnail_url = esc_url( get_the_post_thumbnail_url( $id, 'medium' ) );
		$permalink     = esc_url( get_permalink( $id ) );
		$class         = 'project-' . $type;

		ob_start();
		?>

        <div class="<?php echo $class; ?>">
            <img src="<?php echo $thumbnail_url; ?>"
                 alt="<?php echo $title; ?>">

            <a href="<?php echo $permalink; ?>"
               title="<?php echo $notice; ?>">
				<?php echo $title; ?>
            </a>
        </div>

		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}


	/**
	 * Projects navigation.
	 */
	public function projects_navigation() {

		$next_project     = get_next_post( false, '', 'project-type' );
		$previous_project = get_previous_post( false, '', 'project-type' );

		ob_start();

		// Previous project.
		if ( $previous_project ) {

			$id    = $previous_project->ID;
			$title = $previous_project->post_title;

			echo $this->projects_navigation_layout( $id, $title );

		} else {

			$projects = new WP_Query( array(
				'post_type'      => 'projects',
				'posts_per_page' => - 1,
				'fields'         => 'ids'
			) );

			$id    = $projects->posts[0];
			$title = get_the_title( $id );

			echo $this->projects_navigation_layout( $id, $title, 'last' );

			wp_reset_postdata();
		}

		// Next project.
		if ( $next_project ) {

			$id    = $next_project->ID;
			$title = $next_project->post_title;

			echo $this->projects_navigation_layout( $id, $title, '', 'next' );

		} else {

			$projects = new WP_Query( array(
				'post_type'      => 'projects',
				'posts_per_page' => - 1,
				'fields'         => 'ids'
			) );

			$id    = $projects->posts[ $projects->found_posts - 1 ];
			$title = get_the_title( $id );

			echo $this->projects_navigation_layout( $id, $title, 'first', 'next' );

			wp_reset_postdata();
		}

		$html = ob_get_contents();
		ob_end_clean();

		$wrapper_title = sprintf( esc_html__( '%sPlease refer to our other projects%s', 'skyweb-projects' ), '<h3 class="more_projects_title">', '</h3>' );
		$wrapper       = '%s<div class="project-navigation">%s</div>';

		$html = sprintf( $wrapper, $wrapper_title, $html );

		return $html;
	}

}