<?php /* Template Name: Рестораны */
/**
 * The template for displaying the homepage v1.
 *
 * This page template will display any functions hooked into the `pizzaro_homepage_v1` action.
 *
 * Template name: Homepage v1
 *
 * @package pizzaro
 */

remove_action( 'pizzaro_content_top', 'pizzaro_breadcrumb', 10 );

do_action( 'pizzaro_before_homepage_v1' );

get_header( 'v1' ); ?>

  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
      <h1><?php the_title(); ?></h1>
      <div class="brand_wrapp row">
        <?php $terms = get_terms( array(
                'taxonomy' => 'product_brand',
                'hide_empty' => true,
            ) ); ?>
        <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
          foreach ( $terms as $term ) { $termlinks= get_term_link($term,$taxonomy); ?>
           <div class="col-md-3">
             <a href="<?php echo $termlinks ?>">
               <h4 class="brand_title">
                <?php echo $term->name; ?>
               </h4>
                <?php $thumbnail_id = get_woocommerce_term_meta($term->term_id, 'thumbnail_id', true);
                $image = wp_get_attachment_url($thumbnail_id); ?>
                <?php echo "<img src='{$image}' alt='' width='400' height='400' />"; ?>
            </a>
           </div>
        <?php }} ?>
      </div>
    </main><!-- #main -->
  </div><!-- #primary -->
<?php
get_footer( 'v1' );
