<?php
add_action( 'wp_ajax_clear_content', 'jhl_cp_clear_content' );
add_action( 'wp_ajax_nopriv_clear_content', 'jhl_cp_clear_content' );

function jhl_cp_clear_content() {
    $post = $_POST['post_id'];
    if ( $_POST['remove_attachments'] ) {
        $children = get_children( [ 'post_parent' => $post_id, 'fields' => 'ids' ] );
        foreach( $children as $child ) {
            $result = wp_delete_attachment( $child, true );
        }
    }
    $result = wp_delete_post( $post_id, true );

    header( 'Content-Type:application/json' );
    echo json_encode( [
        'success' => ( $data !== false ),
        'data'    => $post_id,
    ] );
    die;
}

add_action( 'wp_ajax_get_post_ids', 'jhl_cp_get_post_ids' );
add_action( 'wp_ajax_nopriv_get_post_ids', 'jhl_cp_get_post_ids' );

function jhl_cp_get_post_ids() {
    $post_type = $_POST['post_type'];
    $post_ids = new WP_Query( [
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'fields' => 'ids',
        'post_status' => 'any',
    ] );

    header( 'Content-Type:application/json' );

    if ( $post_ids->found_posts ) {
        $post_ids = $post_ids->posts;
    } else {
        $post_ids = [];
    }
    echo json_encode( [
        'success' => ( $data !== false ),
        'data' => $post_ids,
    ] );
    die;
}