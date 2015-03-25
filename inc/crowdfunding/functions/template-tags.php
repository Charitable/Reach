<?php
/**
 * Custom template tags used when crowdfunding is enabled.
 *
 * @package 	Benny
 * @category 	Functions
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'benny_crowdfunding_campaign_nav' ) ) :

	/**
	 * The callback function for the campaigns navigation
	 * 
	 * @param 	bool 	$echo
	 * @return 	string
	 * @since 	1.0.0
	 */
	function benny_crowdfunding_campaign_nav( $echo = true ) {	
		$categories = get_categories( array( 'taxonomy' => 'download_category', 'orderby' => 'name', 'order' => 'ASC' ) );

		if ( empty( $categories ) )
			return;

		$html = '<ul class="menu menu-site"><li class="download_category with-icon" data-icon="&#xf02c;">'.__('Categories', 'benny');
		$html .= '<ul><li><a href="'.get_post_type_archive_link('download').'">'.__('All', 'benny').'</a></li>';

		foreach ( $categories as $category ) {
			$html .= '<li><a href="'.esc_url( get_term_link($category) ).'">'.$category->name.'</a></li>';
		}

		$html .= '</li></ul>';

		if ( $echo === false ) 
			return $html;
		
		echo $html;
	}

endif;

if ( ! function_exists( 'benny_campaign_ended_text' ) ) : 

	/**
	 * Return the text to display when a campaign has finished. 
	 *
	 * @return 	string
	 * @since 	1.0.0
	 */
	function benny_campaign_ended_text() {
		return sprintf( '<span>%s</span> %s', __( 'Campaign', 'benny' ), __( 'has ended', 'benny' ) );
	}

endif;

if ( ! function_exists( 'benny_edd_variable_pricing' ) ) : 

	/**
	 * Variable price output
	 *
	 * Outputs variable pricing options for each download or a specified downloads in a list.
	 * The output generated can be overridden by the filters provided or by removing
	 * the action and adding your own custom action.
	 *	 
	 * @param 	int 	$download_id 	Download ID
	 * @param 	array 	$args 
	 * @return 	void
	 * @since 	2.0.0
	 */
	function benny_edd_variable_pricing( $download_id = 0, $args = array() ) {		
		if ( class_exists( 'EDD_Purchase_Limit' ) ) {
			return benny_edd_variable_pricing_with_limits( $download_id, $args );
		}	

		$variable_pricing = edd_has_variable_prices( $download_id );
		$prices = apply_filters( 'edd_purchase_variable_prices', edd_get_variable_prices( $download_id ), $download_id );

		if ( ! $variable_pricing || ( false !== $args['price_id'] && isset( $prices[$args['price_id']] ) ) ) {
			return;
		}

		$prices = apply_filters( 'edd_purchase_variable_prices', edd_get_variable_prices( $download_id ), $download_id );
		$type   = edd_single_price_option_mode( $download_id ) ? 'checkbox' : 'radio';
		$mode   = edd_single_price_option_mode( $download_id ) ? 'multi' : 'single';
		$schema = edd_add_schema_microdata() ? ' itemprop="offers" itemscope itemtype="http://schema.org/Offer"' : '';

		if ( edd_item_in_cart( $download_id ) && ! edd_single_price_option_mode( $download_id ) ) {
			return;
		}

		do_action( 'edd_before_price_options', $download_id ); ?>
		<div class="edd_price_options edd_<?php echo esc_attr( $mode ); ?>_mode">
			<ul class="campaign-pledge-levels">
				<?php
				if ( $prices ) :
					$checked_key = isset( $_GET['price_option'] ) ? absint( $_GET['price_option'] ) : edd_get_default_variable_price( $download_id );
					foreach ( $prices as $price_id => $price ) : 
					?>
					<li id="edd_price_option_<?php echo $download_id ?>_<?php echo sanitize_key( $price['name'] ) ?>" class="pledge-level" <?php echo $schema ?>>
						<input type="<?php echo $type ?>" 
							<?php echo checked( apply_filters( 'edd_price_option_checked', $checked_key, $download_id, $price_id ), $price_id, false ) ?> 
							name="edd_options[price_id][]" 
							id="<?php echo esc_attr( 'edd_price_option_' . $download_id . '_' . $price_id ) ?>" 
							class="<?php echo esc_attr( 'edd_price_option_' . $download_id ) ?>" 
							value="<?php echo esc_attr( $price_id ) ?>" 
							data-price="<?php echo edd_get_price_option_amount( $download_id, $price_id ) ?>" 
							/>
						<h5 class="pledge-title">
							<?php printf( _x( 'Pledge %s', 'pledge amount', 'benny' ), '<strong>' . edd_currency_filter( edd_format_amount( $price['amount'] ) ) . '</strong>' ) ?>
						</h5>
						<label for="<?php echo esc_attr( 'edd_price_option_<?php echo $download_id ?>_' . $price_id ) ?>" class="pledge-description">
							<span class="edd_price_option_name" itemprop="description"><?php echo esc_html( $price['name'] ) ?></span>
						</label>
						<?php 
						do_action( 'edd_after_price_option', $price_id, $price, $download_id );
						?>
					</li>
								
					<?php 
					endforeach;
				endif;
				do_action( 'edd_after_price_options_list', $download_id, $prices, $type );
				?>
			</ul>
		</div><!--end .edd_price_options-->
	<?php
		do_action( 'edd_after_price_options', $download_id );
	}

endif;

if ( ! function_exists( 'benny_edd_variable_pricing_with_limits' ) ) : 

	/**
	 * Variable price output when Purchase Limits is installed.
	 *
	 * Outputs variable pricing options for each download or a specified downloads in a list.
	 * The output generated can be overridden by the filters provided or by removing
	 * the action and adding your own custom action.
	 *	 
	 * @param 	int 	$download_id 	Download ID
	 * @param 	array 	$args 
	 * @return 	void
	 * @since 	2.0.0
	 */
	function benny_edd_variable_pricing_with_limits( $download_id = 0, $args = array() ) {		
		if ( ! class_exists( 'EDD_Purchase_Limit' ) ) {
			return benny_edd_variable_pricing( $download_id, $args );
		}

		$variable_pricing = edd_has_variable_prices( $download_id );
		$prices = apply_filters( 'edd_purchase_variable_prices', edd_get_variable_prices( $download_id ), $download_id );

		if ( ! $variable_pricing || ( false !== $args['price_id'] && isset( $prices[$args['price_id']] ) ) ) {
			return;
		}		

		$prices 		= apply_filters( 'edd_purchase_variable_prices', edd_get_variable_prices( $download_id ), $download_id );
		$type   		= edd_single_price_option_mode( $download_id ) ? 'checkbox' : 'radio';
		$mode   		= edd_single_price_option_mode( $download_id ) ? 'multi' : 'single';
		$schema 		= edd_add_schema_microdata() ? ' itemprop="offers" itemscope itemtype="http://schema.org/Offer"' : '';

		// Purchase Limits settings
		$sold_out_label = edd_get_option( 'edd_purchase_limit_sold_out_label', __( 'Sold Out', 'benny' ) );
        $scope 			= edd_get_option( 'edd_purchase_limit_scope', 'site-wide' );
        $sold_out 		= array();

		if ( edd_item_in_cart( $download_id ) && ! edd_single_price_option_mode( $download_id ) ) {
			return;
		}

		do_action( 'edd_before_price_options', $download_id ); ?>
		<div class="edd_price_options edd_<?php echo esc_attr( $mode ); ?>_mode">
			<ul class="campaign-pledge-levels">
				<?php
				if ( $prices ) :
					$disable_all    = get_post_meta( $download_id, '_edd_purchase_limit_variable_disable', true );
		            $disabled       = false;

		            if ( $disable_all ) :
		                foreach ( $prices as $price_id => $price_data ) :
		                    if ( edd_pl_is_item_sold_out( $download_id, $price_id ) ) :
		                        $disabled = true;
		                        break;
		                    endif;
		                endforeach;
		            endif;

					$checked_key = isset( $_GET['price_option'] ) ? absint( $_GET['price_option'] ) : edd_get_default_variable_price( $download_id );
					foreach ( $prices as $price_id => $price ) : ?>
						
						<li id="edd_price_option_<?php echo $download_id ?>_<?php echo sanitize_key( $price['name'] ) ?>" class="pledge-level" <?php echo $schema ?>>
						<?php

						/* The item is sold out or disabled */
	                	if ( edd_pl_is_item_sold_out( $download_id, $price_id ) || $disabled ) :
		                    
		                    $sold_out[] = $price_id;

		                	?>		                	
							<input type="<?php echo $type ?>" 
								<?php echo checked( apply_filters( 'edd_price_option_checked', $checked_key, $download_id, $price_id ), $price_id, false ) ?> 
								name="edd_options[price_id][]" 
								id="<?php echo esc_attr( 'edd_price_option_' . $download_id . '_' . $price_id ) ?>" 
								class="<?php echo esc_attr( 'edd_price_option_' . $download_id ) ?>" 
								value="<?php echo esc_attr( $price_id ) ?>" 
								data-price="<?php echo edd_get_price_option_amount( $download_id, $price_id ) ?>" 
								disabled
								/>
							<h5 class="pledge-title">
								<?php printf( _x( 'Pledge %s', 'pledge amount', 'benny' ), '<strong>' . edd_currency_filter( edd_format_amount( $price['amount'] ) ) . '</strong>' ) ?>
							</h5>
							<span class="pledge-limit"><?php echo $sold_out_label ?></span>
							<label for="<?php echo esc_attr( 'edd_price_option_<?php echo $download_id ?>_' . $price_id ) ?>" class="pledge-description" disabled>
								<span class="edd_price_option_name" itemprop="description"><?php echo esc_html( $price['name'] ) ?></span>
							</label>							
		                <?php

		                else : 

		                	$max_purchases 	= edd_pl_get_file_purchase_limit( $download_id, null, $price_id );
		                    $purchases 		= edd_pl_get_file_purchases( $download_id, $price_id );
		                    $remaining 		= $max_purchases - $purchases;
		                    $show_remaining = edd_get_option( 'edd_purchase_limit_show_counts', false );

		                    if ( $show_remaining ) : 
		                    	$remaining_txt = edd_get_option( 'edd_purchase_limit_remaining_label', __( 'Remaining', 'benny' ) );
		                    	$remaining_msg 	= $remaining > 0 ? sprintf( __( '%d of %d %s', 'benny' ), $remaining, $max_purchases, $remaining_txt ) : __( 'Limitless', 'benny' );
		                    endif;	

							?>						
							<input type="<?php echo $type ?>" 
								<?php echo checked( apply_filters( 'edd_price_option_checked', $checked_key, $download_id, $price_id ), $price_id, false ) ?> 
								name="edd_options[price_id][]" 
								id="<?php echo esc_attr( 'edd_price_option_' . $download_id . '_' . $price_id ) ?>" 
								class="<?php echo esc_attr( 'edd_price_option_' . $download_id ) ?>" 
								value="<?php echo esc_attr( $price_id ) ?>" 
								data-price="<?php echo edd_get_price_option_amount( $download_id, $price_id ) ?>" 
								/>
							<h5 class="pledge-title">
								<?php printf( _x( 'Pledge %s', 'pledge amount', 'benny' ), '<strong>' . edd_currency_filter( edd_format_amount( $price['amount'] ) ) . '</strong>' ) ?>
							</h5>
							<?php if ( $show_remaining ) : ?>								
								<span class="pledge-limit"><?php echo $remaining_msg ?></span>							
							<?php endif ?>
							<label for="<?php echo esc_attr( 'edd_price_option_<?php echo $download_id ?>_' . $price_id ) ?>" class="pledge-description">
								<span class="edd_price_option_name" itemprop="description"><?php echo esc_html( $price['name'] ) ?></span>
							</label>
						<?php

						endif;

						do_action( 'edd_after_price_option', $price_id, $price, $download_id );

						?>					
						</li>
					<?php 
					endforeach;			
				endif;

				do_action( 'edd_after_price_options_list', $download_id, $prices, $type );
				
				?>
			</ul>
		</div><!--end .edd_price_options-->
	<?php
		do_action( 'edd_after_price_options', $download_id );
	}

endif;

