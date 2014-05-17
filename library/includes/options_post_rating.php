<?php
/**
 * Editor Post Rating Metabox
 * @subpackage Forceful
 * @since Forceful 1.0
 */

add_action('add_meta_boxes', 'kopa_post_rating_meta_box_add');

function kopa_post_rating_meta_box_add() {
    add_meta_box('kopa-post-rating-edit', __( 'Editor Rating', kopa_get_domain() ), 'kopa_meta_box_post_rating_cb', 'post', 'normal', 'high');
}

function kopa_meta_box_post_rating_cb($post) {
    $kopa_editor_post_rating = get_post_meta( $post->ID, 'kopa_editor_post_rating_' . kopa_get_domain(), true ); 
    
    wp_nonce_field( 'kopa_post_rating_meta_box_nonce', 'kopa_post_rating_meta_box_nonce' );
    
    ?>
    
    <div id="kopa-rating-wrapper">
        <!-- Dynamic content area for post rating fields -->
        <?php if ( $kopa_editor_post_rating ) { 
            foreach ( $kopa_editor_post_rating as $index => $rating ) { ?>
                <p id="kopa-rating-field-<?php echo $index; ?>" class="kopa-field-wrapper" data-id="<?php echo $index; ?>">
                    <label for=""><?php _e('Rating Name:', kopa_get_domain()); ?> </label>
                    <input name="kopa_editor_post_rating[<?php echo $index; ?>][name]" type="text" value="<?php echo $rating['name']; ?>">
                    <select name="kopa_editor_post_rating[<?php echo $index; ?>][value]">
                        <?php $kopa_rating_options = array( 1, 2, 3, 4, 5 );
                        foreach ( $kopa_rating_options as $value ) { ?>

                            <option value="<?php echo $value; ?>" <?php selected( $value, $rating['value'] ); ?>>
                                <?php echo $value . __( ' Star(s)', kopa_get_domain() ); ?>
                            </option>

                        <?php } ?>
                    </select>
                    <button class="button kopa-remove-rating"><?php _e( 'Remove', kopa_get_domain() ); ?></button>
                </p> <!-- .kopa-field-wrapper -->
            <?php } // endforeach
        } // endif ?>
    </div> <!-- #kopa-rating-wrapper -->
    
    <p class="meta-options">
        <button id="kopa-rating-add" class="button button-primary"><?php _e( 'Add', kopa_get_domain() ); ?></button>
        <button id="kopa-rating-remove-all" class="button"><?php _e( 'Remove All', kopa_get_domain() ); ?></button>
    </p>

    <?php
}

add_action('save_post', 'kopa_save_post_rating_data');

function kopa_save_post_rating_data( $post_id ) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!isset($_POST['kopa_post_rating_meta_box_nonce']) || !wp_verify_nonce($_POST['kopa_post_rating_meta_box_nonce'], 'kopa_post_rating_meta_box_nonce')) {
        return;
    }
    if ( isset( $_POST['kopa_editor_post_rating'] ) ) {
        $kopa_editor_post_rating = $_POST['kopa_editor_post_rating'];
        foreach ( $kopa_editor_post_rating as $index => $rating ) {
            if ( empty( $rating['name'] ) ) {
                unset( $kopa_editor_post_rating[ $index ] );
            }
        }
        
        if ( empty( $kopa_editor_post_rating ) ) { 
            delete_post_meta( $post_id, 'kopa_editor_post_rating_' . kopa_get_domain() );
            delete_post_meta( $post_id, 'kopa_editor_total_rating_' . kopa_get_domain()  );
            delete_post_meta( $post_id, 'kopa_user_post_rating_' . kopa_get_domain()  );
            delete_post_meta( $post_id, 'kopa_user_total_rating_' . kopa_get_domain()  );
            delete_post_meta( $post_id, 'kopa_user_total_all_rating_' . kopa_get_domain()  );
        } else {
            $kopa_editor_total_rating = 0;
            foreach ( $kopa_editor_post_rating as $rating ) {
                $kopa_editor_total_rating += $rating['value'];
            }
            $kopa_editor_total_rating = $kopa_editor_total_rating / count( $kopa_editor_post_rating );

            // get user post rating and unset rating indexes that are not in editor rating indexes
            $kopa_user_post_rating = get_post_meta( $post_id, 'kopa_user_post_rating_' . kopa_get_domain() , true );
            $kopa_user_total_rating = get_post_meta( $post_id, 'kopa_user_total_rating_' . kopa_get_domain() , true );

            if ( ! empty( $kopa_user_post_rating ) && ! empty( $kopa_user_total_rating ) ) {
                foreach ( $kopa_user_post_rating as $rating_index => $rating ) {
                    if ( ! isset( $kopa_editor_post_rating[ $rating_index ] ) ) {
                        unset($kopa_user_post_rating[ $rating_index ]);
                    }
                }
                foreach ( $kopa_user_total_rating as $rating_index => $value ) {
                    if ( ! isset( $kopa_user_post_rating[ $rating_index ] ) ) {
                        unset($kopa_user_total_rating[ $rating_index ]);
                    }
                }
            } // endif

            // recalculate total all rating indexes of all users
            $total_user_all_rating = 0;
            if ( ! empty( $kopa_user_total_rating ) ) {
                foreach( $kopa_user_total_rating as $value ) {
                    $total_user_all_rating += $value;
                }
                $total_user_all_rating = $total_user_all_rating / count( $kopa_user_total_rating );
            }

            // calculate editor and users ratings
            $kopa_editor_user_total_all_rating = $kopa_editor_total_rating;
            if ( ! empty( $total_user_all_rating ) ) {
                $kopa_editor_user_total_all_rating = ( $kopa_editor_user_total_all_rating + $total_user_all_rating ) / 2;
            }

            update_post_meta( $post_id, 'kopa_editor_post_rating_' . kopa_get_domain() , $kopa_editor_post_rating );
            update_post_meta( $post_id, 'kopa_editor_total_rating_' . kopa_get_domain() , $kopa_editor_total_rating );

            if ( ! empty( $kopa_user_post_rating ) && ! empty( $kopa_user_total_rating ) && ! empty( $total_user_all_rating ) ) {
                update_post_meta( $post_id, 'kopa_user_post_rating_' . kopa_get_domain() , $kopa_user_post_rating );
                update_post_meta( $post_id, 'kopa_user_total_rating_' . kopa_get_domain() , $kopa_user_total_rating );
                update_post_meta( $post_id, 'kopa_user_total_all_rating_' . kopa_get_domain() , $total_user_all_rating );
            } 
            // delete if empty
            // case 1: no users rated (in the beginning of time)
            // case 2: rated but editor(admin) deletes 1 or more rating indexes -> make it empty
            else { 
                delete_post_meta( $post_id, 'kopa_user_post_rating_' . kopa_get_domain() );
                delete_post_meta( $post_id, 'kopa_user_total_rating_' . kopa_get_domain() );
                delete_post_meta( $post_id, 'kopa_user_total_all_rating_' . kopa_get_domain() );
            }

            update_post_meta( $post_id, 'kopa_editor_user_total_all_rating_' . kopa_get_domain() , $kopa_editor_user_total_all_rating );
        }
    } else {
        delete_post_meta( $post_id, 'kopa_editor_post_rating_' . kopa_get_domain() );
        delete_post_meta( $post_id, 'kopa_editor_total_rating_' . kopa_get_domain() );
        delete_post_meta( $post_id, 'kopa_user_post_rating_' . kopa_get_domain() );
        delete_post_meta( $post_id, 'kopa_user_total_rating_' . kopa_get_domain() );
        delete_post_meta( $post_id, 'kopa_user_total_all_rating_' . kopa_get_domain() );
    }
}