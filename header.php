<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Omari
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<header>
			<section class="search">
				<div class="container">
					<div class="text-center d-md-flex align-items-center">
						<?php get_search_form(); ?>
					</div>
				</div>
			</section>
			<section class="top-bar">
				<div class="container">
					<div class="row">
						<div class="brand col-md-3 col-12 col-lg-2 text-center text-md-left">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
								<?php if( has_custom_logo() ): ?>
									<?php the_custom_logo(); ?>
								<?php else: ?>
									<p class="site-title"><?php bloginfo( 'title' ); ?></p>
									<span><?php bloginfo( 'description' ); ?></span>
								<?php endif; ?>
							</a>
						</div>
						<div class="second-column col-md-9 col-12 col-lg-10">
							<div class="row">
								<?php if( class_exists( 'WooCommerce' ) ): ?>
								<div class="account col-12">
									<div class="navbar-expand">
										<ul class="navbar-nav" float-start>
											<?php if( is_user_logged_in() ) : ?>
											<li>
												<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) ?>" class="nav-link"><?php esc_html_e( 'Omatilini', 'omari' ); ?></a>
											</li>
											<li>
												<a href="<?php echo esc_url( wp_logout_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) ) ?>" class="nav-link"><?php esc_html_e( 'Kirjaudu ulos', 'omari' ); ?></a>
											</li>
											<?php else: ?>
											<li>
												<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) ?>" class="nav-link"><?php esc_html_e( 'Login / Register', 'omari' ); ?></a>
											</li>												
											<?php endif; ?>
										</ul>
									</div>
									<div class="cart float-end">
										<a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><span class="cart-icon"></span></a>
										<span class="items"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
									</div>
								</div>
								<?php endif; ?>
								<div class="col-12">
									<nav class="navbar navbar-expand-md navbar-light bg-light">
											<div class="container-fluid">
												<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
													<span class="navbar-toggler-icon"></span>
												</button>
												
												<div class="collapse navbar-collapse" id="main-menu">
													<?php
													wp_nav_menu(array(
														'theme_location' => 'omari_main_menu',
														'container' => false,
														'menu_class' => '',
														'fallback_cb' => '__return_false',
														'items_wrap' => '<ul id="%1$s" class="navbar-nav me-auto mb-2 mb-md-0 %2$s">%3$s</ul>',
														'depth' => 2,
														'walker' => new bootstrap_5_wp_nav_menu_walker()
													));
													?>
											</div>
										</div>
									</nav>								
								</div>
							</div>
						</div>						
					</div>
				</div>
			</section>
		</header>		