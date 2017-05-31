<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AMPHTML_Tab_Templates extends AMPHTML_Tab_Abstract {
	
	public function __construct( $name, $options, $is_current = false ) {
		parent::__construct($name, $options, $is_current);
		add_filter('wpamp_content_tags', array( $this, 'additional_content_tags' ) );
	}
	
	public function get_sections() {
		return array(
			'posts'    => __( 'Posts', 'amphtml' ),
			'pages'    => __( 'Pages', 'amphtml' ),
			'search'   => __( 'Search', 'amphtml' ),
			'blog'     => __( 'Blog Page', 'amphtml' ),
			'archives' => __( 'Archives', 'amphtml' ),
			'404'      => __( '404 Page', 'amphtml' )
		);
	}

	public function get_fields() {
		return array_merge(
			$this->get_404_fields( '404' ),
			$this->get_posts_fields( 'posts' ),
			$this->get_page_fields( 'pages' ),
			$this->get_search_fields( 'search' ),
			$this->get_blog_fields( 'blog' ),
			$this->get_archive_fields( 'archives' )
		);
	}

	/*
	 * 404 Page Section
	 */
	public function get_404_fields( $section ) {
		$fields = array(
			array(
				'id'                    => 'breadcrumbs_404',
				'title'                 => __( 'Breadcrumbs', 'amphtml' ),
				'default'               => 0,
				'section'               => $section,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'breadcrumbs_404' ),
				'description'           => __( 'Show breadcrumbs', 'amphtml' )
			),
			array(
				'id'                    => 'title_404',
				'title'                 => __( '404 Page Title', 'amphtml' ),
				'default'               => __( 'Oops! That page can&rsquo;t be found.', 'amphtml' ),
				'section'               => $section,
				'display_callback'      => array( $this, 'display_text_field' ),
				'display_callback_args' => array( 'title_404' ),
				'description'           => ''
			),
			array(
				'id'                    => 'content_404',
				'title'                 => __( '404 Page Content', 'amphtml' ),
				'default'               => __( 'Nothing was found at this location.', 'amphtml' ),
				'section'               => $section,
				'display_callback'      => array( $this, 'display_textarea_field' ),
				'display_callback_args' => array( 'content_404' ),
				'sanitize_callback'     => array( $this, 'sanitize_textarea_content' ),
				'description'           => __( 'Plain html without inline styles allowed. '
				                               . '(<a href="https://github.com/ampproject/amphtml/blob/master/spec/amp-tag-addendum.md#html5-tag-whitelist" target="_blank">HTML5 Tag Whitelist</a>)', 'amphtml' )
			),
			array(
				'id'                    => 'search_form',
				'title'                 => __( 'Search Form', 'amphtml' ),
				'default'               => 0,
				'section'               => $section,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'search_form' ),
				'description'           => __( 'Enable search form. Needs SSL certificate for AMP validation.', 'amphtml' )
			),
		);
		return apply_filters( 'amphtml_template_404_fields', $fields, $section, $this );
	}

	/**
	 * Add additional allowed tags for custom content here
	 * 
	 * @param array $tags
	 * @return array of tags
	 */
	public function additional_content_tags( $tags ) {
		$tags['amp-ad'] = array(
			'src'            => true,
			'type'           => true,
			'width'          => true,
			'height'         => true,
			'data-slot'      => true,
			'data-ad-client' => true,
			'data-ad-slot'   => true
		);

		$tags['amp-img'] = array(
			'src'    => true,
			'srcset' => true,
			'alt'    => true,
			'height' => true,
			'width'  => true,
			'class'  => true,
			'id'     => true,
			'layout' => true,
			'title'  => true
		);

		$tags['form'] = array(
			'action'         => true,
			'action-xhr'     => true,
			'method'         => true,
			'target'         => true,
			'autocomplete'   => true,
			'name'           => true,
			'enctype'        => true,
			'accept-charset' => true
		);

		$tags['input'] = array(
			'type'  => true,
			'name'  => true,
			'value' => true
		);

		return $tags;
	}

	public function sanitize_textarea_content( $textarea_content ) {
		$tags = wp_kses_allowed_html( 'post' );
		$tags['form']['action-xhr'] = true;

		$not_allowed = array(
			'font',
			'menu',
			'nav'
		);

		foreach ( $tags as $key => $attr ) {
			if ( in_array( $key, $not_allowed ) ) {
				unset( $tags[ $key ] );
			}
		}

		$tags = apply_filters('wpamp_content_tags', $tags );
		return wp_kses( $textarea_content, $tags );
	}

	public function display_textarea_field( $args ) {
		$id       = current( $args );
		$name     = sprintf( "%s", $this->options->get( $id, 'name' ) );
		?>
		<textarea id="<?php echo $id ?>" name="<?php echo $name ?>" rows="6" cols="46"><?php echo trim( $this->options->get( $id ) ); ?></textarea>
		<?php if ( $this->options->get( $id, 'description' ) ): ?>
			<p class="description"><?php echo $this->options->get( $id, 'description' ) ?></p>
		<?php endif;
	}

	/*
	 * Posts Section
	 */
	public function get_posts_fields( $section ) {

		$top_ad_block    = array();
		$bottom_ad_block = array();

		$fields = array(
			array(
				'id'                    => 'post_breadcrumbs',
				'title'                 => __( 'Breadcrumbs', 'amphtml' ),
				'default'               => 0,
				'section'               => $section,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'post_breadcrumbs' ),
				'template_name'         => 'breadcrumb',
				'description'           => __( 'Show breadcrumbs', 'amphtml' )
			),
			// Search form
			array(
				'id'                    => 'post_search_form',
				'title'                 => __( 'Search Form', 'amphtml' ),
				'default'               => 0,
				'section'               => $section,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'post_search_form' ),
				'description'           => __( 'Enable search form. Needs SSL certificate for AMP validation.', 'amphtml' )
			),
			// Block original button
			array(
				'id'                    => 'post_original_btn_block',
				'title'                 => __( 'Original Button', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, 'display_original_btn_block' ),
				'display_callback_args' => array( 'post_original_btn_block' ),
				'sanitize_callback'     => array( $this, 'sanitize_original_btn_block' ),
				'section'               => $section,
				'description'           => __( 'Show link to the original version of the post', 'amphtml' )
			),
			array(
				'id'                    => 'post_original_btn_text',
				'title'                 => '',
				'default'               => __( 'View Original Version' ),
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'post_original_btn_text' ),
				'description'           => __( 'Button title', 'amphtml' ),
			),
			// Block commnets button
			array(
				'id'                    => 'post_comments_btn_block',
				'title'                 => __( 'Comments Button', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, 'display_comments_btn_block' ),
				'display_callback_args' => array( 'post_comments_btn_block' ),
				'sanitize_callback'     => array( $this, 'sanitize_comments_btn_block' ),
				'section'               => $section,
				'description'           => __( 'Show link to the comment form', 'amphtml' )
			),
			array(
				'id'                    => 'post_comments_btn_text',
				'title'                 => '',
				'default'               => __( 'Comments' ),
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'post_comments_btn_text' ),
				'description'           => __( 'Button title', 'amphtml' ),
			),
			// Post title
			array(
				'id'                    => 'post_title',
				'title'                 => __( 'Post Title', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'post_title' ),
				'section'               => $section,
				'description'           => 'Show post title',
			),
			array(
				'id'                    => 'post_custom_html',
				'title'                 => __( 'Custom HTML', 'amphtml' ),
				'display_callback'      => array( $this, 'display_textarea_field' ),
				'display_callback_args' => array( 'post_custom_html' ),
				'sanitize_callback'     => array( $this, 'sanitize_textarea_content' ),
				'section'               => $section,
				'template_name'         => 'custom_html',
				'description'           => __( 'Plain html without inline styles allowed. '
				                               . '(<a href="https://github.com/ampproject/amphtml/blob/master/spec/amp-tag-addendum.md#html5-tag-whitelist" target="_blank">HTML5 Tag Whitelist</a>)', 'amphtml' )
			),
			array(
				'id'                    => 'post_featured_image',
				'title'                 => __( 'Featured Image', 'amphtml' ),
				'default'               => 1,
				'section'               => $section,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'post_featured_image' ),
				'description'           => __( 'Show post thumbnail', 'amphtml' ),
			),
			array(
				'id'                    => 'post_meta',
				'title'                 => __( 'Post Meta Block', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'post_meta' ),
				'section'               => $section,
				'description'           => __( 'Show post author, categories and published time', 'amphtml' ),
			),
			array(
				'id'                    => 'post_content',
				'title'                 => __( 'Post Content', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'post_content', 'disabled', 'checked' ),
				'section'               => $section,
				'description'           => 'Show post content',
			),
			array(
				'id'                    => 'post_social_share',
				'title'                 => __( 'Social Share Buttons', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'post_social_share' ),
				'template_name'         => 'social-share',
				'section'               => $section,
				'description'           => __( 'Show social share buttons', 'amphtml' ),
			),
			// Related posts block
			array(
				'id'                    => 'post_related_content_block',
				'title'                 => __( 'Related Posts', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_related_posts_block' ),
				'display_callback_args' => array( 'post_related_content_block' ),
				'sanitize_callback' => array( $this, 'sanitize_related_posts_content_block' ),
				'section'               => $section,
				'description'           => __( 'Show related posts', 'amphtml' )
			),
			array(
				'id'                    => 'post_related_title',
				'title'                 => __( 'Related Posts Title', 'amphtml' ),
				'default'               => __('You May Also Like'),
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'post_related_title' ),
				'description'           => __( 'Title', 'amphtml' ),
			),
			array(
				'id'                    => 'post_related_count',
				'title'                 => __( 'Number of Related Posts', 'amphtml' ),
				'default'               => 3,
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'post_related_count' ),
				'description'           => __( 'Post count', 'amphtml' ),
			),
			array(
				'id'                    => 'post_related_thumbnail',
				'title'                 => __( 'Post', 'amphtml' ),
				'default'               => 0,
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'post_related_thumbnail' ),
				'description'           => __( 'Show Post Thumbnail', 'amphtml' ),
			),
			
			// Recent posts block
			array(
				'id'                    => 'post_recent_content_block',
				'title'                 => __( 'Recent Posts', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_recent_posts_block' ),
				'display_callback_args' => array( 'post_recent_content_block' ),
				'sanitize_callback' => array( $this, 'sanitize_recent_posts_content_block' ),
				'section'               => $section,
				'description'           => __( 'Show recent posts', 'amphtml' )
			),
			array(
				'id'                    => 'post_recent_title',
				'title'                 => __( 'Recent Posts Title', 'amphtml' ),
				'default'               => __('Recent Posts'),
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'post_recent_title' ),
				'description'           => __( 'Title', 'amphtml' ),
			),
			array(
				'id'                    => 'post_recent_count',
				'title'                 => __( 'Number of Recent Posts', 'amphtml' ),
				'default'               => 3,
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'post_recent_count' ),
				'description'           => __( 'Post count', 'amphtml' ),
			),
			array(
				'id'                    => 'post_recent_thumbnail',
				'title'                 => __( 'Post', 'amphtml' ),
				'default'               => 0,
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'post_recent_thumbnail' ),
				'description'           => __( 'Show Post Thumbnail', 'amphtml' ),
			),
			array(
				'id'                    => 'post_comments',
				'title'                 => __( 'Post Comments', 'amphtml' ),
				'default'               => 0,
				'section'               => $section,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'post_comments' ),
				'description'           => __( 'Show post comments', 'amphtml' ),
			),
		);

		return apply_filters( 'amphtml_template_posts_fields', $fields, $section, $this );
	}

	/*
	 * Pages Section
	 */
	public function get_page_fields( $section ) {
		$top_ad_block    = array();
		$bottom_ad_block = array();
		$socail_share    = array();

		$fields = array(
			array(
				'id'                    => 'page_breadcrumbs',
				'title'                 => __( 'Breadcrumbs', 'amphtml' ),
				'default'               => 0,
				'section'               => $section,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'page_breadcrumbs' ),
				'template_name'         => 'breadcrumb',
				'description'           => __( 'Show breadcrumbs', 'amphtml' )
			),
			// Block original button
			array(
				'id'                    => 'page_original_btn_block',
				'title'                 => __( 'Original Button', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, 'display_page_original_btn_block' ),
				'display_callback_args' => array( 'page_original_btn_block' ),
				'sanitize_callback'     => array( $this, 'sanitize_page_original_btn_block' ),
				'section'               => $section,
				'description'           => __( 'Show link to the original version of the page', 'amphtml' )
			),
			array(
				'id'                    => 'page_original_btn_text',
				'title'                 => '',
				'default'               => __( 'View Original Version' ),
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'page_original_btn_text' ),
				'description'           => __( 'Button title', 'amphtml' ),
			),
			// Search form
			array(
				'id'                    => 'page_search_form',
				'title'                 => __( 'Search Form', 'amphtml' ),
				'default'               => 0,
				'section'               => $section,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'page_search_form' ),
				'description'           => __( 'Enable search form. Needs SSL certificate for AMP validation.', 'amphtml' )
			),
			array(
				'id'                    => 'page_title',
				'title'                 => __( 'Page Title', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'page_title' ),
				'template_name'         => 'post_title',
				'section'               => $section,
				'description'           => 'Show page title',
			),
			array(
				'id'                    => 'page_featured_image',
				'title'                 => __( 'Featured Image', 'amphtml' ),
				'default'               => 1,
				'section'               => $section,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'page_featured_image' ),
				'template_name'         => 'post_featured_image',
				'description'           => __( 'Show page thumbnail', 'amphtml' ),
			),
			array(
				'id'                    => 'page_custom_html',
				'title'                 => __( 'Custom HTML', 'amphtml' ),
				'display_callback'      => array( $this, 'display_textarea_field' ),
				'display_callback_args' => array( 'page_custom_html' ),
				'sanitize_callback'     => array( $this, 'sanitize_textarea_content' ),
				'section'               => $section,
				'template_name'         => 'custom_html',
				'description'           => __( 'Plain html without inline styles allowed. '
				                               . '(<a href="https://github.com/ampproject/amphtml/blob/master/spec/amp-tag-addendum.md#html5-tag-whitelist" target="_blank">HTML5 Tag Whitelist</a>)', 'amphtml' )

			),
			array(
				'id'                    => 'page_content',
				'title'                 => __( 'Page Content', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'page_content', 'disabled', 'checked' ),
				'section'               => $section,
				'description'           => 'Show page content',
				'template_name'         => 'post_content'
			),
			array(
				'id'                    => 'page_social_share',
				'title'                 => __( 'Social Share Buttons', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'page_social_share' ),
				'section'               => $section,
				'template_name'         => 'social-share',
				'description'           => __( 'Show social share buttons', 'amphtml' ),
			)
		);

		return apply_filters( 'amphtml_template_page_fields', $fields, $section, $this );
	}

	/*
	 * Search Page Section
	 */
	public function get_search_fields( $section ) {
		$top_ad_block    = array();
		$bottom_ad_block = array();

		$fields = array(
			array(
				'id'                    => 'search_breadcrumbs',
				'title'                 => __( 'Breadcrumbs', 'amphtml' ),
				'default'               => 0,
				'section'               => $section,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'search_breadcrumbs' ),
				'template_name'         => 'breadcrumb',
				'description'           => __( 'Show breadcrumbs', 'amphtml' )
			),
			array(
				'id'                    => 'search_page_title',
				'title'                 => __( 'Page Title', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'search_page_title' ),
				'section'               => $section,
				'description'           => 'Show page title',
			),
			array(
				'id'                    => 'search_page_custom_html',
				'title'                 => __( 'Custom HTML', 'amphtml' ),
				'display_callback'      => array( $this, 'display_textarea_field' ),
				'display_callback_args' => array( 'search_page_custom_html' ),
				'sanitize_callback'     => array( $this, 'sanitize_textarea_content' ),
				'section'               => $section,
				'template_name'         => 'custom_html',
				'description'           => __( 'Plain html without inline styles allowed. '
				                               . '(<a href="https://github.com/ampproject/amphtml/blob/master/spec/amp-tag-addendum.md#html5-tag-whitelist" target="_blank">HTML5 Tag Whitelist</a>)', 'amphtml' )

			),
			array(
				'id'                => 'search_page_content_block',
				'title'             => __( 'Content Block', 'amphtml' ),
				'default'           => 1,
				'display_callback'  => array( $this, 'display_search_page_content_block' ),
				'sanitize_callback' => array( $this, 'sanitize_search_page_content_block' ),
				'section'           => $section,
				'description'       => __( 'Search Page Content', 'amphtml' ),
			),
			array(
				'id'                    => 'search_page_post_featured_image',
				'title'                 => __( 'Featured Image', 'amphtml' ),
				'default'               => 1,
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'search_page_post_featured_image' ),
				'description'           => __( 'Show posts thumbnail', 'amphtml' ),
			),
			array(
				'id'                    => 'search_page_post_meta',
				'title'                 => __( 'Post Meta Block', 'amphtml' ),
				'default'               => 1,
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'search_page_post_meta' ),
				'description'           => __( 'Show posts author, categories and published time', 'amphtml' ),
			),
		);

		return apply_filters( 'amphtml_template_search_fields', $fields, $section, $this );
	}

	/*
	 * Blog Page Section
	 */
	public function get_blog_fields( $section ) {

		$top_ad_block    = array();
		$bottom_ad_block = array();

		$fields = array(
			// Block original button
			array(
				'id'                    => 'blog_original_btn_block',
				'title'                 => __( 'Original Button', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, 'display_blog_original_btn_block' ),
				'display_callback_args' => array( 'blog_original_btn_block' ),
				'sanitize_callback'     => array( $this, 'sanitize_blog_original_btn_block' ),
				'section'               => $section,
				'description'           => __( 'Show link to the original version of the blog', 'amphtml' )
			),
			array(
				'id'                    => 'blog_original_btn_text',
				'title'                 => '',
				'default'               => __( 'View Original Version' ),
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'blog_original_btn_text' ),
				'description'           => __( 'Button title', 'amphtml' ),
			),
			array(
				'id'                    => 'blog_page_title',
				'title'                 => __( 'Blog Page Title', 'amphtml' ),
				'default'               => __( 'Blog', 'amphtml' ),
				'section'               => $section,
				'display_callback'      => array( $this, 'display_text_field' ),
				'display_callback_args' => array( 'blog_page_title' ),
				'description'           => ''
			),
			array(
				'id'                    => 'blog_custom_html',
				'title'                 => __( 'Custom HTML', 'amphtml' ),
				'display_callback'      => array( $this, 'display_textarea_field' ),
				'display_callback_args' => array( 'blog_custom_html' ),
				'sanitize_callback'     => array( $this, 'sanitize_textarea_content' ),
				'section'               => $section,
				'template_name'         => 'custom_html',
				'description'           => __( 'Plain html without inline styles allowed. '
				                               . '(<a href="https://github.com/ampproject/amphtml/blob/master/spec/amp-tag-addendum.md#html5-tag-whitelist" target="_blank">HTML5 Tag Whitelist</a>)', 'amphtml' )

			),
			array(
				'id'                => 'blog_content_block',
				'title'             => __( 'Content Block', 'amphtml' ),
				'default'           => 1,
				'display_callback'  => array( $this, 'display_blog_content_block' ),
				'sanitize_callback' => array( $this, 'sanitize_blog_content_block' ),
				'section'           => $section,
				'description'       => __( 'Blog Page Content', 'amphtml' )
			),
			array(
				'id'                    => 'blog_page_post_featured_image',
				'title'                 => __( 'Featured Image', 'amphtml' ),
				'default'               => 1,
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'blog_page_post_featured_image' ),
				'description'           => __( 'Show posts thumbnail', 'amphtml' ),
			),
			array(
				'id'                    => 'blog_page_post_meta',
				'title'                 => __( 'Post Meta Block', 'amphtml' ),
				'default'               => 1,
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'blog_page_post_meta' ),
				'description'           => __( 'Show posts author, categories and published time', 'amphtml' ),
			),
		);

		return apply_filters( 'amphtml_template_blog_fields', $fields, $section, $this );
	}

	/*
	 * Archive Page Section
	 */
	public function get_archive_fields( $section ) {
		$top_ad_block    = array();
		$bottom_ad_block = array();

		$fields = array(
			array(
				'id'                    => 'archive_breadcrumbs',
				'title'                 => __( 'Breadcrumbs', 'amphtml' ),
				'default'               => 0,
				'section'               => $section,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'archive_breadcrumbs' ),
				'template_name'         => 'breadcrumb',
				'description'           => __( 'Show breadcrumbs', 'amphtml' )
			),
			// Block original button
			array(
				'id'                    => 'archive_original_btn_block',
				'title'                 => __( 'Original Button', 'amphtml' ),
				'default'               => 0,
				'display_callback'      => array( $this, 'display_archive_original_btn_block' ),
				'display_callback_args' => array( 'archive_original_btn_block' ),
				'sanitize_callback'     => array( $this, 'sanitize_archive_original_btn_block' ),
				'section'               => $section,
				'description'           => __( 'Show link to the original version of the archive', 'amphtml' )
			),
			array(
				'id'                    => 'archive_original_btn_text',
				'title'                 => '',
				'default'               => __( 'View Original Version' ),
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'archive_original_btn_text' ),
				'description'           => __( 'Button title', 'amphtml' ),
			),
			array(
				'id'                    => 'archive_title',
				'title'                 => __( 'Archive Title', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'archive_title', 'disabled', 'checked' ),
				'section'               => $section,
				'description'           => 'Show archive title',
			),
			array(
				'id'                    => 'archive_desc',
				'title'                 => __( 'Description', 'amphtml' ),
				'default'               => 1,
				'display_callback'      => array( $this, 'display_checkbox_field' ),
				'display_callback_args' => array( 'archive_desc' ),
				'section'               => $section,
				'description'           => __( 'Show description of archive page', 'amphtml' ),
			),
			array(
				'id'                    => 'archive_custom_html',
				'title'                 => __( 'Custom HTML', 'amphtml' ),
				'display_callback'      => array( $this, 'display_textarea_field' ),
				'display_callback_args' => array( 'archive_custom_html' ),
				'sanitize_callback'     => array( $this, 'sanitize_textarea_content' ),
				'section'               => $section,
				'template_name'         => 'custom_html',
				'description'           => __( 'Plain html without inline styles allowed. '
				                               . '(<a href="https://github.com/ampproject/amphtml/blob/master/spec/amp-tag-addendum.md#html5-tag-whitelist" target="_blank">HTML5 Tag Whitelist</a>)', 'amphtml' )

			),
			array(
				'id'                => 'archive_content_block',
				'title'             => __( 'Content BLock', 'amphtml' ),
				'default'           => 1,
				'display_callback'  => array( $this, 'display_archive_content_block' ),
				'sanitize_callback' => array( $this, 'sanitize_archive_content_block' ),
				'section'           => $section,
				'description'       => '',
			),
			array(
				'id'                    => 'archive_featured_image',
				'title'                 => __( 'Featured Images', 'amphtml' ),
				'default'               => 1,
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'archive_featured_image' ),
				'description'           => __( 'Show posts thumbnails', 'amphtml' ),
			),
			array(
				'id'                    => 'archive_featured_image_link',
				'title'                 => __( 'Featured Images Link', 'amphtml' ),
				'default'               => 1,
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'archive_featured_image_link' ),
				'description'           => __( 'Link featured images to the post', 'amphtml' ),
			),
			array(
				'id'                    => 'archive_meta',
				'title'                 => __( 'Post Meta Block', 'amphtml' ),
				'default'               => 1,
				'section'               => $section,
				'display_callback'      => array( $this, '' ),
				'display_callback_args' => array( 'archive_meta' ),
				'description'           => __( 'Show post author, categories and published time', 'amphtml' ),
			),
		);

		return apply_filters( 'amphtml_template_archive_fields', $fields, $section, $this );
	}

	public function get_section_fields( $id ) {
		$fields_order = get_option( self::ORDER_OPT );
		$fields_order = maybe_unserialize( $fields_order );
		$fields_order = isset( $fields_order[ $id ] ) ? maybe_unserialize( $fields_order[ $id ] ) : array();
		if ( ! count( $fields_order ) ) {
			return parent::get_section_fields( $id );
		}
		$fields = array();
		foreach ( $fields_order as $field_name ) {
			$fields[] = $this->search_field_id( $field_name );
		}

		return array_merge( $fields, parent::get_section_fields( $id ) );
	}

	public function display_archive_content_block() {
		?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'archive_featured_image' ) ); ?>
			<?php $this->display_checkbox_field( array( 'archive_featured_image_link' ) ); ?>
			<?php $this->display_checkbox_field( array( 'archive_meta' ) ); ?>
		</fieldset>
		<?php
	}
	
	public function display_original_btn_block() {
		?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'post_original_btn_block' ) ); ?>
			<br>
			<?php $this->display_text_field( array( 'post_original_btn_text' ) ); ?>
		</fieldset>
		<?php
	}
	
	public function display_page_original_btn_block() {
		?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'page_original_btn_block' ) ); ?>
			<br>
			<?php $this->display_text_field( array( 'page_original_btn_text' ) ); ?>
		</fieldset>
		<?php
	}
	
	public function display_comments_btn_block() {
		?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'post_comments_btn_block' ) ); ?>
			<br>
			<?php $this->display_text_field( array( 'post_comments_btn_text' ) ); ?>
		</fieldset>
		<?php
	}
	
	public function display_related_posts_block() {
		?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'post_related_content_block' ) ); ?>
			<br>
			<?php $this->display_text_field( array( 'post_related_title' ) ); ?>
			<br>
			<?php $this->display_text_field( array( 'post_related_count' ), 'number' ); ?>
			<br>
			<?php $this->display_checkbox_field( array( 'post_related_thumbnail' ) ); ?>
		</fieldset>
		<?php
	}
	
	public function display_recent_posts_block() {
		?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'post_recent_content_block' ) ); ?>
			<br>
			<?php $this->display_text_field( array( 'post_recent_title' ) ); ?>
			<br>
			<?php $this->display_text_field( array( 'post_recent_count' ), 'number' ); ?>
			<br>
			<?php $this->display_checkbox_field( array( 'post_recent_thumbnail' ) ); ?>
		</fieldset>
		<?php
	}

	public function display_blog_content_block() {
		?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'blog_page_post_featured_image' ) ); ?>
			<?php $this->display_checkbox_field( array( 'blog_page_post_meta' ) ); ?>
		</fieldset>
		<?php
	}

	public function display_search_page_content_block() {
		?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'search_page_post_featured_image' ) ); ?>
			<?php $this->display_checkbox_field( array( 'search_page_post_meta' ) ); ?>
		</fieldset>
		<?php
	}

	public function sanitize_blog_content_block() {
		$this->update_fieldset( array(
			'blog_page_post_featured_image',
			'blog_page_post_meta'
		) );
		return 1;
	}

	public function sanitize_archive_content_block() {
		$this->update_fieldset( array(
			'archive_featured_image',
			'archive_featured_image_link',
			'archive_meta'
		) );
		return 1;
	}

	public function sanitize_original_btn_block() {
		$this->update_fieldset( array(
			'post_original_btn_text',
		) );

		$block_name = $this->options->get( 'post_original_btn_block', 'name' );

		return isset( $_POST[ $block_name ] ) ? sanitize_text_field( $_POST[ $block_name ] ) : '';
	}
	
	public function sanitize_page_original_btn_block() {
		$this->update_fieldset( array(
			'page_original_btn_text',
		) );

		$block_name = $this->options->get( 'page_original_btn_block', 'name' );

		return isset( $_POST[ $block_name ] ) ? sanitize_text_field( $_POST[ $block_name ] ) : '';
	}
	
	
	
	public function display_blog_original_btn_block() {
		?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'blog_original_btn_block' ) ); ?>
			<br>
			<?php $this->display_text_field( array( 'blog_original_btn_text' ) ); ?>
		</fieldset>
		<?php
	}
	
	public function sanitize_blog_original_btn_block() {
		$this->update_fieldset( array(
			'blog_original_btn_text',
		) );

		$block_name = $this->options->get( 'blog_original_btn_block', 'name' );

		return isset( $_POST[ $block_name ] ) ? sanitize_text_field( $_POST[ $block_name ] ) : '';
	}
	
	public function display_archive_original_btn_block() {
		?>
		<fieldset>
			<?php $this->display_checkbox_field( array( 'archive_original_btn_block' ) ); ?>
			<br>
			<?php $this->display_text_field( array( 'archive_original_btn_text' ) ); ?>
		</fieldset>
		<?php
	}
	
	public function sanitize_archive_original_btn_block() {
		$this->update_fieldset( array(
			'archive_original_btn_text',
		) );

		$block_name = $this->options->get( 'archive_original_btn_block', 'name' );

		return isset( $_POST[ $block_name ] ) ? sanitize_text_field( $_POST[ $block_name ] ) : '';
	}
	
	
	
	
	
	public function sanitize_comments_btn_block() {
		$this->update_fieldset( array(
			'post_comments_btn_text',
		) );

		$block_name = $this->options->get( 'post_comments_btn_block', 'name' );

		return isset( $_POST[ $block_name ] ) ? sanitize_text_field( $_POST[ $block_name ] ) : '';

	}

	public function sanitize_related_posts_content_block() {
		$this->update_fieldset( array(
			'post_related_title',
			'post_related_count',
			'post_related_thumbnail'
		) );

		$block_name = $this->options->get( 'post_related_content_block', 'name' );

		return isset( $_POST[ $block_name ] ) ? sanitize_text_field( $_POST[ $block_name ] ) : '';
	}
	
	public function sanitize_recent_posts_content_block() {
		$this->update_fieldset( array(
			'post_recent_title',
			'post_recent_count',
			'post_recent_thumbnail'
		) );

		$block_name = $this->options->get( 'post_recent_content_block', 'name' );

		return isset( $_POST[ $block_name ] ) ? sanitize_text_field( $_POST[ $block_name ] ) : '';

	}

	public function sanitize_search_page_content_block() {
		$this->update_fieldset( array(
			'search_page_post_featured_image',
			'search_page_post_meta',
		) );
		return 1;
	}
	
	public function get_section_callback( $id ) {
		return array( $this, 'section_callback' );
	}

	public function section_callback( $page, $section ) {
		global $wp_settings_fields;

		if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
			return;
		}
		$row_id = 0;
		foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
			$class = '';

			if ( ! method_exists( $field['callback'][0], $field['callback'][1] ) ) {
				continue;
			}

			if ( ! empty( $field['args']['class'] ) ) {
				$class = ' class="' . esc_attr( $field['args']['class'] ) . '"';
			}

			echo "<tr data-name='{$field['id']}' id='pos_{$row_id}' {$class}>";
			echo '<th class="drag"></th>';
			if ( ! empty( $field['args']['label_for'] ) ) {
				echo '<th scope="row"><label for="' . esc_attr( $field['args']['label_for'] ) . '">' . $field['title'] . '</label></th>';
			} else {
				echo '<th scope="row">' . $field['title'] . '</th>';
			}

			echo '<td>';
			call_user_func( $field['callback'], $field['args'] );
			echo '</td>';
			echo '</tr>';
			$row_id ++;
		}
	}

}
