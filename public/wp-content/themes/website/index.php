<?php get_header(); ?>

    <main class="max-w-7xl mx-auto px-8">
        <?php if (have_posts()) : ?>
            <div>
                <?php while (have_posts()) : the_post(); ?>
                    <div>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php the_content(); ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </main>

<?php get_footer();
