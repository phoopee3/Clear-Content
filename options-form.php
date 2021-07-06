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

<style>
    form fieldset {
        border: 1px solid #aaa;
        display: inline-block;
    }
    form fieldset legend {
        margin-left: 10px;
        font-weight: bold;
        padding: 0 5px;
    }
    form fieldset > div {
        margin: 5px;
    }
    form fieldset table td {
        vertical-align: top;
    }
    form.jhl-cc > div {
        padding-bottom:15px;
    }
    form.jhl-cc label {
        display: inline-block;
        width: 230px;
    }
    .columns {
        display: flex;
    }
    .columns button {
        white-space: nowrap;
        margin-right: 5px;
    }
    .progress-wrapper {
        background-color: #ccc;
        border: 1px solid #999;
        height: 20px;
        width: 100%;
    }
    .progress-bar {
        width: 0%;
        background-color: #999;
        height:20px;
    }
</style>

<form action="" data-form="jhl-cc" class="jhl-cc" method="POST">
    <div>
        <fieldset>
            <legend>Post types</legend>
            <div>
                <table>
                    <tr>
                        <td>
                            <label for="jhl_cc_post_type"><strong>Choose the post type to clear</strong></label>
                        </td>
                        <td>
                            <select name="post_type" id="jhl_cc_post_type">
                                <option value="">Select post type</option>
                                <?php foreach( $post_types as $post_type ) { ?>
                                    <option value="<?php echo $post_type; ?>"><?php echo $post_type; ?></option>
                                <?php } ?>
                            </select>
                            <br>
                            <label for="remove_attachments">
                                <input type="checkbox" id="remove_attachments" name="remove_attachments"> Also remove attachments
                            </label>
                        </td>
                    </tr>
                </table>
                <hr>
                <div class="columns">
                    <button disabled="disabled">Clear posts!</button>
                    <div class="progress-wrapper">
                        <div class="progress-bar" data-widget="progress-bar"></div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</form>

<script>
var post_ids = [];
var total_ids = 0;

jQuery( document ).ready( function() {
    jQuery( '[data-form="jhl-cc"] select' ).on( 'change', function( e ) {
        post_ids = [];
        var post_type = jQuery( this ).val();
        if ( post_type ) {
            jQuery.ajax( {
                type : "POST",
                data : {
                    action: "get_post_ids",
                    post_type: post_type,
                },
                url : ajaxurl,
                success: function( data ) {
                    console.log( data );
                    if ( data.success == true && data.data.length ) {
                        post_ids = data.data;
                        total_ids = data.data.length;
                        initProgressBar();
                        jQuery( '[data-form="jhl-cc"] button' ).attr( 'disabled', false );
                    } else {
                        jQuery( '[data-form="jhl-cc"] button' ).attr( 'disabled', true );
                        return;
                    }
                }
            } );
        }
    } );

    jQuery( '[data-form="jhl-cc"] button' ).on( 'click', function( e ) {
        e.preventDefault();
        var post_id = post_ids.shift();
        var remove_attachments = jQuery( '[data-form="jhl-cc"] input[name="remove_attachments"]' ).is( ':checked' );
        console.log( remove_attachments );
        processPost( post_id, remove_attachments );
    })
} );

var initProgressBar = function() {
    jQuery( '[data-widget="progress-bar"]' ).css( 'width', '0%' );
}

var processPost = function( post_id, remove_attachments ) {
    if ( post_id == null ) {
        return;
    }
    jQuery.ajax( {
        type: "POST",
        data: {
            action: "clear_content",
            post_id: post_id,
            remove_attachments: remove_attachments
        },
        url: ajaxurl,
        success: function( data ) {
            console.log( data );
            updateProgressBar();
            if ( data.success == true ) {
                processPost( post_ids.shift(), remove_attachments );
            } else {
                return;
            }
        }
    } );
}

var updateProgressBar = function() {
    var width = ( ( total_ids - post_ids.length ) / total_ids ) * 100;
    console.log( width );
    jQuery( '[data-widget="progress-bar"]' ).css( 'width', width + '%' );
}
</script>
