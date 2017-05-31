<?php
/**
 * @var $this AMPHTML_Template
 */
$count  = $this->get_posts_count( 'recent' );
$recent = $this->get_recent_posts( $count );

if ( $recent && $recent->have_posts()) : ?>
	<aside>
		<h3><?php echo $this->get_posts_title( 'recent' ); ?></h3>
		<ul class="wp-amp-recent-posts-list">
			<?php $show_thumbnail = $this->get_posts_thumbnail( 'recent' ); ?>
			<?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
				<?php $link = get_permalink( get_the_id() ); ?>
				<li>
					<?php if( $show_thumbnail && has_post_thumbnail( get_the_ID() ) ) : ?>
						<a href="<?php echo $this->get_amphtml_link( $link ); ?>" title="<?php the_title_attribute(); ?>">
							<?php $this->the_post_thumbnail_tpl( get_the_ID() ); ?>
						</a>
					<?php endif; ?>
					<a href="<?php echo $this->get_amphtml_link( $link ); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_title('<h4>', '</h4>'); ?>
					</a>
				</li>
			<?php endwhile; ?>
		</ul>
	</aside>
<?php
endif;
wp_reset_query();