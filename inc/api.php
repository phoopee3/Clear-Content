<?php
add_action( 'wp_ajax_clear_content', 'jhl_cp_clear_content' );
add_action( 'wp_ajax_nopriv_clear_content', 'jhl_cp_clear_content' );

function jhl_cp_clear_content() {
    $post_id = sanitize_text_field( $_POST['post_id'] );

    header( 'Content-Type:application/json' );

    if ( $post_id && is_numeric( $post_id ) ) {
        if ( $_POST['remove_attachments'] ) {
            $children = get_children( [ 'post_parent' => $post_id, 'fields' => 'ids' ] );
            foreach( $children as $child ) {
                $result = wp_delete_attachment( $child, true );
            }
        }
        $result = wp_delete_post( $post_id, true );

        echo json_encode( [
            'success' => ( $data !== false ),
            'data'    => $post_id,
        ] );
    } else {
        echo json_encode( [
            'success' => false,
            'data'    => 'Invalid post id',
        ] );
    }
    die;
}

add_action( 'wp_ajax_get_post_ids', 'jhl_cp_get_post_ids' );
add_action( 'wp_ajax_nopriv_get_post_ids', 'jhl_cp_get_post_ids' );

function jhl_cp_get_post_ids() {
    $post_type = sanitize_text_field( $_POST['post_type'] );
    header( 'Content-Type:application/json' );
    if ( $post_type ) {
        $post_ids = new WP_Query( [
            'post_type' => $post_type,
            'posts_per_page' => -1,
            'fields' => 'ids',
            'post_status' => 'any',
        ] );

        if ( $post_ids->found_posts ) {
            $post_ids = $post_ids->posts;
        } else {
            $post_ids = [];
        }
        echo json_encode( [
            'success' => ( $data !== false ),
            'data' => $post_ids,
        ] );
    } else {
        echo json_encode( [
            'success' => false,
            'data' => 'Invalid post type',
        ] );
    }
    die;
}