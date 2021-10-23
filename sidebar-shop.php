<?php
/**
 * The template for the sidebar containing the shop widget area
 *
 * @package Omari
 */
?>

<?php if( is_active_sidebar( 'omari-sidebar-shop' ) ): ?>
	<?php dynamic_sidebar( 'omari-sidebar-shop' ); ?>
<?php endif;