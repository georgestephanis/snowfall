<?php

/*
 * Plugin Name: Snowfall
 * Plugin URI: http://github.com/georgestephanis/snowfall/
 * Description: Snowfall
 * Author: George Stephanis
 * Version: 1.0
 * Author URI: http://stephanis.info/
 */

class WP_Snowfall {
	static $instance;

	const POST_TYPE = 'snowfall';

	function __construct() {
		self::$instance = $this;

		add_action( 'init',                  array( $this, 'register_post_type' ) );
		add_action( 'edit_form_after_title', array( $this, 'replacement_editor' ) );

		return $this;
	}

	function register_post_type() {
		$labels = array( 
			'name'               => _x( 'Articles',                   'post_type', 'snowfall' ),
			'singular_name'      => _x( 'Article',                    'post_type', 'snowfall' ),
			'add_new'            => _x( 'Add New',                    'post_type', 'snowfall' ),
			'all_items'          => _x( 'Articles',                   'post_type', 'snowfall' ),
			'add_new_item'       => _x( 'Add New Article',            'post_type', 'snowfall' ),
			'edit_item'          => _x( 'Edit Article',               'post_type', 'snowfall' ),
			'new_item'           => _x( 'New Article',                'post_type', 'snowfall' ),
			'view_item'          => _x( 'View Article',               'post_type', 'snowfall' ),
			'search_items'       => _x( 'Search Articles',            'post_type', 'snowfall' ),
			'not_found'          => _x( 'No articles found',          'post_type', 'snowfall' ),
			'not_found_in_trash' => _x( 'No articles found in Trash', 'post_type', 'snowfall' ),
			'parent_item_colon'  => _x( 'Parent Article:',            'post_type', 'snowfall' ),
			'menu_name'          => _x( 'Articles',                   'post_type', 'snowfall' ),
		);

		$args = array( 
			'labels'       => $labels,
			'hierarchical' => false,
			'public'       => true,
			'supports'     => array(
				'title',
				'editor',
				'thumbnail',
			),
			'taxonomies'   => array(
				'post_tag',
				'category',
			),
		);

		register_post_type( self::POST_TYPE, $args );
	}

	function replacement_editor( $post ) {
		if ( self::POST_TYPE !== $post->post_type ) return;

		// Remove this, so the native editor never shows.
		remove_post_type_support( self::POST_TYPE, 'editor' );
		wp_enqueue_style( 'snowfall-edit-screen', plugins_url( 'css/edit-screen.css', __FILE__ ) );
		wp_enqueue_script( 'snowfall-edit-screen', plugins_url( 'js/edit-screen.js', __FILE__ ), array( 'jquery-ui-sortable' ) );
		// ?

		$block_types = apply_filters( 'snowfall_block_types', array(
			'text'    => _x( 'Text',    'block type', 'snowfall' ),
			'image'   => _x( 'Image',   'block type', 'snowfall' ),
			'gallery' => _x( 'Gallery', 'block type', 'snowfall' ),
			'video'   => _x( 'Video',   'block type', 'snowfall' ),
			'audio'   => _x( 'Audio',   'block type', 'snowfall' ),
			'quote'   => _x( 'Quote',   'block type', 'snowfall' ),
		) );

		?>

		<div id="content-blocks-wrap">

			<article>
				<section data-type="text">
					<div class="handle"></div>
					<?php wp_editor( '', 'content-section-1', array( 'media_buttons' => false, 'quicktags' => false ) ); ?>
				</section>
			</article>

			<ul id="available-blocks">
				<?php foreach ( $block_types as $type => $label ) : ?>
					<li class="<?php echo esc_attr( $type ); ?>"><a href="javascript:;" title="<?php echo esc_attr( $label ); ?>" data-type="<?php echo esc_attr( $type ); ?>"><span class="screen-reader-text"><?php echo esc_html( $label ); ?></span></a></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<?php
	}

}
new WP_Snowfall;