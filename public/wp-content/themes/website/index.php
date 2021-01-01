<?php get_header(); ?>

    <div class="max-w-7xl mx-auto px-8">
        <?php if (have_posts()) : ?>
            <div>
                <?php while (have_posts()) : the_post(); ?>
                    <div>
                        <h2><?php the_title(); ?></h2>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>

<?php get_footer();
