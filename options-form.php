<?php
wp_localize_script( 'my_ajax_script', 'myAjax', [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ] );
wp_enqueue_script( 'my_ajax_script' );

$post_types = get_post_types();

$post_types = array_filter( $post_types, function( $elm ) {
    $exclude = [
        'revision',
        'nav_menu_item',
        'custom_css',
        'customize_changeset',
        'oembed_cache',
        'user_request',
    ];
    return !in_array( $elm, $exclude );
} );

if ( count( $post_types ) ) {
    $post_types = array_keys( $post_types );
}
?>

<h1>Cear Content</h1>

