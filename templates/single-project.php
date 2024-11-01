<?php
get_header();

global $skyweb_projects;
?>

    <div class="container">

		<?php

		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();

				$project_ID     = get_the_ID();
				$project_terms  = get_the_term_list( $project_ID, 'project-type', '', ', ', '' );
				$project_year   = get_post_meta( $project_ID, 'skyweb_project_year', true ) ? absint( get_post_meta( $project_ID, 'skyweb_project_year', true ) ) : '';
				$project_price  = $skyweb_projects->get_project_price( $project_ID );
				$project_link   = esc_url( get_post_meta( $project_ID, 'skyweb_project_link', true ) );
				$project_domain = rtrim( str_ireplace( array( 'https://', 'http://' ), '', $project_link ), '/' );

				the_title( '<h1>', '</h1>' );
				?>

                <div class="case-header">

					<?php if ( ! empty( $project_terms ) ) { ?>

                        <p>
							<?php echo esc_html__( 'Project Type:', 'skyweb-projects' ) . ' '; ?>
                            <span><?php echo $project_terms; ?></span>
                        </p>

					<?php } ?>

					<?php if ( ! empty( $project_year ) ) { ?>

                        <p>
							<?php echo esc_html__( 'Year:', 'skyweb-projects' ) . ' '; ?>
                            <span><?php echo $project_year; ?></span>
                        </p>

					<?php } ?>

					<?php if ( ! empty( $project_price ) ) { ?>

                        <p>
							<?php echo esc_html__( 'Price:', 'skyweb-projects' ) . ' '; ?>
                            <span><?php echo $project_price; ?></span>
                        </p>

					<?php } ?>

					<?php if ( ! empty( $project_link ) ) { ?>

                        <p>
							<?php echo esc_html__( 'Link:', 'skyweb-projects' ) . ' '; ?>
                            <a href="<?php echo $project_link; ?>" rel="nofollow" target="new">
								<?php echo $project_domain; ?>
                            </a>
                        </p>

					<?php } ?>

                </div>

				<?php

				the_content();

				$skyweb_projects_navigation = new SkyWeb_Projects_Navigation();

				echo $skyweb_projects_navigation->projects_navigation();

				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
			}

		} else {
			esc_html_e( 'No project data found.', 'skyweb-projects' );
		}

		wp_reset_postdata();
		?>

    </div>

<?php
get_footer();