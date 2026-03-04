<?php

namespace WeboMCP\Core\Tools;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WordPressTools {

	/**
	 * Returns basic site diagnostics information.
	 *
	 * @param array<string, mixed> $arguments Tool arguments.
	 * @return array<string, mixed>
	 */
	public static function get_site_info( array $arguments ) {
		unset( $arguments );

		return array(
			'name'       => get_bloginfo( 'name' ),
			'description'=> get_bloginfo( 'description' ),
			'url'        => home_url( '/' ),
			'language'   => get_bloginfo( 'language' ),
			'version'    => get_bloginfo( 'version' ),
			'timezone'   => wp_timezone_string(),
			'tool'       => 'webo/get-site-info',
		);
	}

	/**
	 * Core tool: list WordPress posts.
	 *
	 * @param array<string, mixed> $arguments Tool arguments.
	 * @return array<string, mixed>
	 */
	public static function list_posts( array $arguments ) {
		$per_page  = isset( $arguments['per_page'] ) ? (int) $arguments['per_page'] : 10;
		$post_type = isset( $arguments['post_type'] ) ? sanitize_key( (string) $arguments['post_type'] ) : 'post';
		$search    = isset( $arguments['search'] ) ? sanitize_text_field( (string) $arguments['search'] ) : '';
		$status    = isset( $arguments['status'] ) ? sanitize_key( (string) $arguments['status'] ) : 'publish';

		$query = new \WP_Query(
			array(
				'post_type'      => $post_type,
				'posts_per_page' => max( 1, min( 100, $per_page ) ),
				'post_status'    => $status,
				's'              => $search,
				'no_found_rows'  => true,
				'fields'         => 'ids',
			)
		);

		$items = array();
		foreach ( $query->posts as $post_id ) {
			$items[] = array(
				'id'    => $post_id,
				'title' => get_the_title( $post_id ),
				'link'  => get_permalink( $post_id ),
			);
		}

		return array(
			'items' => $items,
			'total' => count( $items ),
			'tool'  => 'webo/list-posts',
		);
	}

	/**
	 * Get a single post by ID.
	 *
	 * @param array<string, mixed> $arguments Tool arguments.
	 * @return array<string, mixed>|\WP_Error
	 */
	public static function get_post( array $arguments ) {
		$post_id = isset( $arguments['post_id'] ) ? (int) $arguments['post_id'] : 0;
		$post    = get_post( $post_id );

		if ( ! $post ) {
			return new \WP_Error( 'webo_wordpress_mcp_post_not_found', 'Post not found' );
		}

		return array(
			'id'      => $post->ID,
			'title'   => get_the_title( $post ),
			'content' => $post->post_content,
			'excerpt' => $post->post_excerpt,
			'status'  => $post->post_status,
			'type'    => $post->post_type,
			'slug'    => $post->post_name,
			'link'    => get_permalink( $post ),
			'tool'    => 'webo/get-post',
		);
	}

	/**
	 * Create a post.
	 *
	 * @param array<string, mixed> $arguments Tool arguments.
	 * @return array<string, mixed>|\WP_Error
	 */
	public static function create_post( array $arguments ) {
		$post_type = isset( $arguments['post_type'] ) ? sanitize_key( (string) $arguments['post_type'] ) : 'post';
		$title     = isset( $arguments['title'] ) ? sanitize_text_field( (string) $arguments['title'] ) : '';
		$content   = isset( $arguments['content'] ) ? wp_kses_post( (string) $arguments['content'] ) : '';
		$status    = isset( $arguments['status'] ) ? sanitize_key( (string) $arguments['status'] ) : 'draft';

		$post_id = wp_insert_post(
			array(
				'post_type'    => $post_type,
				'post_title'   => $title,
				'post_content' => $content,
				'post_status'  => $status,
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		return array(
			'post_id' => (int) $post_id,
			'tool'    => 'webo/create-post',
		);
	}

	/**
	 * Update an existing post.
	 *
	 * @param array<string, mixed> $arguments Tool arguments.
	 * @return array<string, mixed>|\WP_Error
	 */
	public static function update_post( array $arguments ) {
		$post_id = isset( $arguments['post_id'] ) ? (int) $arguments['post_id'] : 0;
		if ( $post_id <= 0 || ! get_post( $post_id ) ) {
			return new \WP_Error( 'webo_wordpress_mcp_post_not_found', 'Post not found' );
		}

		$payload = array(
			'ID' => $post_id,
		);

		if ( isset( $arguments['title'] ) ) {
			$payload['post_title'] = sanitize_text_field( (string) $arguments['title'] );
		}

		if ( isset( $arguments['content'] ) ) {
			$payload['post_content'] = wp_kses_post( (string) $arguments['content'] );
		}

		if ( isset( $arguments['status'] ) ) {
			$payload['post_status'] = sanitize_key( (string) $arguments['status'] );
		}

		$result = wp_update_post( $payload, true );
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return array(
			'post_id' => (int) $result,
			'tool'    => 'webo/update-post',
		);
	}

	/**
	 * Delete one post.
	 *
	 * @param array<string, mixed> $arguments Tool arguments.
	 * @return array<string, mixed>|\WP_Error
	 */
	public static function delete_post( array $arguments ) {
		$post_id = isset( $arguments['post_id'] ) ? (int) $arguments['post_id'] : 0;
		$force   = isset( $arguments['force'] ) ? (bool) $arguments['force'] : false;

		if ( $post_id <= 0 || ! get_post( $post_id ) ) {
			return new \WP_Error( 'webo_wordpress_mcp_post_not_found', 'Post not found' );
		}

		$result = wp_delete_post( $post_id, $force );
		if ( ! $result ) {
			return new \WP_Error( 'webo_wordpress_mcp_delete_failed', 'Failed to delete post' );
		}

		return array(
			'post_id' => $post_id,
			'deleted' => true,
			'tool'    => 'webo/delete-post',
		);
	}

	/**
	 * List users.
	 *
	 * @param array<string, mixed> $arguments Tool arguments.
	 * @return array<string, mixed>
	 */
	public static function list_users( array $arguments ) {
		$per_page = isset( $arguments['per_page'] ) ? (int) $arguments['per_page'] : 20;
		$search   = isset( $arguments['search'] ) ? sanitize_text_field( (string) $arguments['search'] ) : '';

		$users = get_users(
			array(
				'number'         => max( 1, min( 100, $per_page ) ),
				'search'         => '' !== $search ? '*' . $search . '*' : '',
				'search_columns' => array( 'user_login', 'display_name', 'user_email' ),
			)
		);

		$items = array();
		foreach ( $users as $user ) {
			$items[] = array(
				'id'           => (int) $user->ID,
				'login'        => (string) $user->user_login,
				'display_name' => (string) $user->display_name,
				'email'        => (string) $user->user_email,
			);
		}

		return array(
			'items' => $items,
			'total' => count( $items ),
			'tool'  => 'webo/list-users',
		);
	}

	/**
	 * List media items.
	 *
	 * @param array<string, mixed> $arguments Tool arguments.
	 * @return array<string, mixed>
	 */
	public static function list_media( array $arguments ) {
		$per_page = isset( $arguments['per_page'] ) ? (int) $arguments['per_page'] : 20;
		$query    = new \WP_Query(
			array(
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'posts_per_page' => max( 1, min( 100, $per_page ) ),
				'no_found_rows'  => true,
				'fields'         => 'ids',
			)
		);

		$items = array();
		foreach ( $query->posts as $attachment_id ) {
			$items[] = array(
				'id'    => (int) $attachment_id,
				'title' => get_the_title( $attachment_id ),
				'url'   => wp_get_attachment_url( $attachment_id ),
			);
		}

		return array(
			'items' => $items,
			'total' => count( $items ),
			'tool'  => 'webo/list-media',
		);
	}

	/**
	 * List comments.
	 *
	 * @param array<string, mixed> $arguments Tool arguments.
	 * @return array<string, mixed>
	 */
	public static function list_comments( array $arguments ) {
		$per_page = isset( $arguments['per_page'] ) ? (int) $arguments['per_page'] : 20;
		$status   = isset( $arguments['status'] ) ? sanitize_key( (string) $arguments['status'] ) : 'approve';

		$comments = get_comments(
			array(
				'number' => max( 1, min( 100, $per_page ) ),
				'status' => $status,
			)
		);

		$items = array();
		foreach ( $comments as $comment ) {
			$items[] = array(
				'id'        => (int) $comment->comment_ID,
				'post_id'   => (int) $comment->comment_post_ID,
				'author'    => (string) $comment->comment_author,
				'content'   => (string) $comment->comment_content,
				'approved'  => (string) $comment->comment_approved,
			);
		}

		return array(
			'items' => $items,
			'total' => count( $items ),
			'tool'  => 'webo/list-comments',
		);
	}

	/**
	 * List taxonomy terms.
	 *
	 * @param array<string, mixed> $arguments Tool arguments.
	 * @return array<string, mixed>|\WP_Error
	 */
	public static function list_terms( array $arguments ) {
		$taxonomy = isset( $arguments['taxonomy'] ) ? sanitize_key( (string) $arguments['taxonomy'] ) : 'category';
		$per_page = isset( $arguments['per_page'] ) ? (int) $arguments['per_page'] : 50;

		if ( ! taxonomy_exists( $taxonomy ) ) {
			return new \WP_Error( 'webo_wordpress_mcp_taxonomy_not_found', 'Taxonomy not found' );
		}

		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'number'     => max( 1, min( 100, $per_page ) ),
			)
		);

		if ( is_wp_error( $terms ) ) {
			return $terms;
		}

		$items = array();
		foreach ( $terms as $term ) {
			$items[] = array(
				'id'          => (int) $term->term_id,
				'name'        => (string) $term->name,
				'slug'        => (string) $term->slug,
				'taxonomy'    => (string) $term->taxonomy,
				'description' => (string) $term->description,
			);
		}

		return array(
			'items' => $items,
			'total' => count( $items ),
			'tool'  => 'webo/list-terms',
		);
	}

	/**
	 * Read selected safe options.
	 *
	 * @param array<string, mixed> $arguments Tool arguments.
	 * @return array<string, mixed>|\WP_Error
	 */
	public static function get_options( array $arguments ) {
		$names = isset( $arguments['names'] ) && is_array( $arguments['names'] ) ? $arguments['names'] : array();
		if ( empty( $names ) ) {
			return new \WP_Error( 'webo_wordpress_mcp_option_names_required', 'Option names are required' );
		}

		$allowed_option_names = array(
			'blogname',
			'blogdescription',
			'siteurl',
			'home',
			'timezone_string',
			'date_format',
			'time_format',
			'start_of_week',
			'posts_per_page',
		);

		$values = array();
		foreach ( $names as $option_name ) {
			$option_name = sanitize_key( (string) $option_name );
			if ( '' === $option_name || ! in_array( $option_name, $allowed_option_names, true ) ) {
				continue;
			}

			$values[ $option_name ] = get_option( $option_name );
		}

		return array(
			'values' => $values,
			'tool'   => 'webo/get-options',
		);
	}

	/**
	 * Update selected safe options.
	 *
	 * @param array<string, mixed> $arguments Tool arguments.
	 * @return array<string, mixed>|\WP_Error
	 */
	public static function update_options( array $arguments ) {
		$options = isset( $arguments['options'] ) && is_array( $arguments['options'] ) ? $arguments['options'] : array();
		if ( empty( $options ) ) {
			return new \WP_Error( 'webo_wordpress_mcp_options_required', 'Options payload is required' );
		}

		$allowed_option_names = array(
			'blogname',
			'blogdescription',
			'timezone_string',
			'date_format',
			'time_format',
			'start_of_week',
			'posts_per_page',
		);

		$updated = array();
		foreach ( $options as $option_name => $option_value ) {
			$option_name = sanitize_key( (string) $option_name );
			if ( '' === $option_name || ! in_array( $option_name, $allowed_option_names, true ) ) {
				continue;
			}

			update_option( $option_name, $option_value );
			$updated[] = $option_name;
		}

		return array(
			'updated' => $updated,
			'tool'    => 'webo/update-options',
		);
	}
}
