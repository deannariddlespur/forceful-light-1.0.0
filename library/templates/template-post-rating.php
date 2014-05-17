<?php 
$kopa_editor_post_rating = get_post_meta( get_the_ID(), 'kopa_editor_post_rating_' . kopa_get_domain(), true );
$kopa_editor_total_rating = get_post_meta( get_the_ID(), 'kopa_editor_total_rating_' . kopa_get_domain(), true );
$kopa_user_post_rating = get_post_meta( get_the_ID(), 'kopa_user_post_rating_' . kopa_get_domain(), true );
$kopa_user_total_rating = get_post_meta( get_the_ID(), 'kopa_user_total_rating_' . kopa_get_domain(), true );
$kopa_user_total_all_rating = get_post_meta( get_the_ID(), 'kopa_user_total_all_rating_' . kopa_get_domain(), true );
?>

<?php if ( ! empty( $kopa_editor_post_rating ) ) { ?>
    <div class="row-fluid kopa-rating-container">
        <div class="span6">
            <ul class="kopa-rating-box kopa-editor-rating-box">
                <li><?php _e( 'Editor Rating', kopa_get_domain() ); ?></li>
                
                <?php foreach( $kopa_editor_post_rating as $rating ) { ?>
                    
                    <li class="clearfix">
                        <span><?php echo $rating['name']; ?></span>
                        
                        <ul class="kopa-rating clearfix" title="<?php printf( __( 'Rated %s out of 5', kopa_get_domain() ), $rating['value'] ); ?>">

                            <?php for( $i = 0; $i < $rating['value']; $i++ ) { ?>
                                <li><?php echo KopaIcon::getIcon('star','span'); ?></li>
                            <?php } // endfor ?>

                            <?php for( $i = 0; $i < 5 - $rating['value']; $i++ ) { ?>
                                <li><?php echo KopaIcon::getIcon('star2','span'); ?></li>
                            <?php } // endfor ?>

                        </ul>

                    </li>

                <?php } // endforeach ?>

                <li class="total-score clearfix">
                    <span><?php _e( 'Total score', kopa_get_domain() ); ?></span>
                    <ul class="kopa-rating clearfix" title="<?php printf( __( 'Rated %.2f out of 5', kopa_get_domain() ), $kopa_editor_total_rating ); ?>">
                        <?php $kopa_editor_total_rating = round( $kopa_editor_total_rating ); ?>
                        <?php for( $i = 0; $i < $kopa_editor_total_rating; $i++ ) { ?>
                            <li><?php echo KopaIcon::getIcon('star','span'); ?></li>
                        <?php } // endfor ?>

                        <?php for( $i = 0; $i < 5 - $kopa_editor_total_rating; $i++ ) { ?>
                            <li><?php echo KopaIcon::getIcon('star2','span'); ?></li>
                        <?php } // endfor ?>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="span6">
            <ul class="kopa-rating-box kopa-user-rating-box">
                <li><?php _e( 'User Rating', kopa_get_domain() ); ?></li>
                <?php 
                foreach ( $kopa_editor_post_rating as $rating_index => $rating ) { 
                    if ( isset( $kopa_user_total_rating[ $rating_index ] ) ) {
                        $current_total_rating = round( $kopa_user_total_rating[ $rating_index ] );
                    } else {
                        $current_total_rating = 0;
                    }
                ?>
                <li class="clearfix">
                    <span><?php echo $rating['name']; ?></span>
                    <ul class="kopa-user-rating kopa-rating clearfix" data-current-rating="<?php echo $current_total_rating; ?>" data-rating-index="<?php echo $rating_index;?>">
                        
                        <?php for ( $i = 1; $i <= 5; $i++ ) { ?>
                        <li><span class="fa <?php echo $i <= $current_total_rating ? 'fa-star' : 'fa-star-o'; ?>" href="javascript:void(0)" ></span></li>
                        <?php } ?>
                        
                    </ul>
                </li>
                <?php } // endforeach ?>
                <li class="total-score clearfix">
                    <span><?php _e( 'Total score', kopa_get_domain() ); ?></span>
                        <?php if ( empty ( $kopa_user_total_all_rating ) ) {
                            $kopa_user_total_all_rating = 0;
                        } 

                        if ( 0 != $kopa_user_total_all_rating ) {
                            $all_rating_title = sprintf( __('Rated %.2f out of 5', kopa_get_domain()), $kopa_user_total_all_rating );
                        } else {
                            $all_rating_title = '';
                        }

                        $kopa_user_total_all_rating = round( $kopa_user_total_all_rating );
                        ?>
                    <ul id="kopa-user-total-rating" class="kopa-rating clearfix" title="<?php echo $all_rating_title; ?>">

                        <?php for ($i = 0; $i < $kopa_user_total_all_rating; $i++) { ?>
                            <li><?php echo KopaIcon::getIcon('star','span'); ?></li>
                        <?php } // endfor ?>

                        <?php for ($i = 0; $i < 5 - $kopa_user_total_all_rating; $i++) { ?>
                            <li><?php echo KopaIcon::getIcon('star2','span'); ?></li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
<?php } // endif ?>