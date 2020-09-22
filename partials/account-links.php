<?php
/**
 * Account links
 *
 * @package Reach
 */

if ( ! reach_has_charitable() ) {
	return;
}

$profile_page = esc_url( charitable_get_permalink( 'profile_page' ) );
$submit_page  = esc_url( charitable_get_permalink( 'campaign_submission_page' ) );
$login_page   = esc_url( charitable_get_permalink( 'login_page' ) );

?>
<div class="account-links">
	<?php if ( $submit_page ) : ?>

		<a class="user-campaign button with-icon button-alt button-small" href="<?php echo $submit_page; ?>" data-icon="&#xf055;"><?php _e( 'Create a campaign', 'reach' ); ?></a>

	<?php endif ?>

	<?php if ( is_user_logged_in() ) : ?>

		<?php if ( $profile_page ) : ?>
			<a class="user-account with-icon button button-alt button-small" href="<?php echo $profile_page; ?>" data-icon="&#xf007;"><?php _e( 'Profile', 'reach' ); ?></a>
		<?php endif ?>

		<a class="logout with-icon" href="<?php echo wp_logout_url( get_permalink() ); ?>" data-icon="&#xf08b;"><?php _e( 'Log out', 'reach' ); ?></a>

	<?php else : ?>

		<a class="user-login button with-icon button-alt button-small" href="<?php echo $login_page; ?>" data-icon="&#xf007;"><?php _e( 'Login / Register', 'reach' ); ?></a>

	<?php endif ?>
</div><!-- .account-links -->
