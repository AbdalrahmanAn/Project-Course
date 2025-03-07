<?php get_header();
while(have_posts()){
    the_post();
    pageBanner();
?>
<div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program') ?>"><i
                    class="fa fa-home" aria-hidden="true"></i> All Programs</a>
            <span class="metabox__main"><?php the_title(); ?></span>
        </p>
    </div>

    <div class="generic-content"><?php the_content(); ?></div>

    <?php 
    $relatedProfessors = new wp_query(array(
        'posts_per_page' => -1,
        'post_type' => 'professor',
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => 'related_programs',
                'compare' => 'like',
                'value' => '"' . get_the_id() . '"'
            )
        )
        ));
        if ($relatedProfessors->have_posts()) {
        echo "<hr class='section-break'>";
        echo "<h2 class='headline headline--medium'>" . get_the_title() ." Professor(s)</h2>";

        echo "<ul class='professor-cards'>";
        while($relatedProfessors->have_posts()) {
        $relatedProfessors->the_post();?>
    <li class="professor-card__list-item">
        <a class="professor-card" href="<?php the_permalink(); ?>">

            <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLand'); ?>">
            <span class="professor-card__name">
                <?php the_title(); ?>
            </span>

        </a>
    </li>

    <?php }
        echo "</ul>" ;
        }
    wp_reset_postdata(); ?>

    <?php 
    $today = date('Ymd');
    $relatedEvents = new wp_query(array(
        'posts_per_page' => 2,
                'post_type' => 'event',
                'mete_key' => 'event_date',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_query' => array(
                array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric',
                ),
                array(
                    'key' => 'related_programs',
                    'compare' => 'like',
                    'value' => '"' . get_the_id() . '"'
                )
                )
        ));
        if ($relatedEvents->have_posts()) {
        echo "<hr class='section-break'>";
        echo "<h2 class='headline headline--medium'>Upcoming " . get_the_title() ." Event(s)</h2>";

        echo "<ul class='professor-cards'>";
        while($relatedEvents->have_posts()) {
        $relatedEvents->the_post(); 
        get_template_part('template-parts/content-event');
        ?>


    <?php }
        echo "</ul>" ;
        }
    wp_reset_postdata();
        
    $relatedCampuses = get_field('related_campus');
    if($relatedCampuses) {
        echo "<hr class='section-break'>";
        echo '<h2 class="headline headline--medium">' . get_the_title() . ' Is Available At These Campuses :</h2>';
        echo "<ul class='link-list min-list'>";
            foreach($relatedCampuses as $campus) { ?>
    <li>
        <a href="<?php echo get_the_permalink($campus); ?>">
            <?php echo get_the_title($campus); ?></a>
    </li>
    <?php   }
        echo "</ul>";
        }
        ?>

</div>

<?php }
get_footer();
?>


