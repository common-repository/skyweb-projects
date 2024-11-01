<?php
get_header();

global $skyweb_projects;
?>

    <div class="container">

		<?php if ( have_posts() ) { ?>

            <header class="page-header">
				<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
            </header>

            <div class="term-description">
				<?php echo term_description(); ?>
            </div>

			<?php
		}

		if ( is_post_type_archive( 'projects' ) ) {

			echo do_shortcode( '[skyweb_projects_filter]' );
		}

		$term = get_queried_object_id();

		echo $skyweb_projects->projects_list_render( array(), $term );
		?>

    </div>

<?php
get_footer();