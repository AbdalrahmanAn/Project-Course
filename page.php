<?php 
get_header();

while(have_posts()) {
the_post();
pageBanner()

?>

<div class="container container--narrow page-section">
    <?php 
    $the = wp_get_post_parent_id(get_the_ID());
      if($the) { ?>

    <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <a class="metabox__blog-home-link" href="<?php echo get_permalink($the); ?>"><i class="fa fa-home"
                    aria-hidden="true"></i> Back To <?php echo get_the_title($the); ?></a>
            <span class="metabox__main"><?php the_title() ?></span>
        </p>
    </div>

    <?php }
    $myn = get_pages(array(
      'child_of' => get_the_ID()
    ));
    ?>

    <?php if($the or $myn) { ?>
    <div class="page-links">
        <h2 class="page-links__title"><a href="<?php echo get_permalink($the) ?>"><?php echo get_the_title($the); ?></a>
        </h2>
        <ul class="min-list">
            <?php 
          if($the) {
            $findchildrenof = $the;
          }else{
            $findchildrenof = get_the_ID();
          }
          wp_list_pages(array(
            'title_li' => false,
            'child_of' => $findchildrenof,
          ));
          ?>
        </ul>
    </div>
    <?php } ?>


    <div class="generic-content">
        <?php the_content(); ?>
    </div>
</div>

<?php } 
get_footer(); ?>