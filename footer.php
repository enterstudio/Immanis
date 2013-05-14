		</div><!-- #main -->
		<footer id="colophon" class="site-footer" role="contentinfo">
			<div id="footer-wrapper">
				<?php get_sidebar(); ?>

				<div id="footer-separator"></div>

				<div class="site-info">
					<?php do_action( 'immanis_credits' ); ?>

					<div id="social">
						<span>Everywhere</span>
						<?php
						$options = get_option('immanis_social');
						$array = array(
							'twitter' => 'w',
							'facebook' => 'f',
							'instagram' => 'h',
							'pinterest' => 'p',
							'dribbble' => 'd',
							'google' => 'g',
							'vimeo' => 'v',
							'flickr' => '8',
							'rss' => 'r'
						);
						foreach ($array as $key => $value):
							if ($options[$key] != ''):
							?>
								<a href="<?php echo $options[$key]; ?>"><?php echo $value; ?></a>
							<?php
							endif;
						endforeach;
						?>
					</div>

					<div id="site-info-wrapper">
						&#169; 2013 <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></a> / Powered By <a href="<?php echo esc_url( __( 'http://wordpress.org/', 'immanis' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'immanis' ); ?>">WordPress</a>
						<br />
						Designed & Crafted by <a href="http://wpshower.com/">Wpshower</a>
					</div>

					<div class="clear"></div>
				</div><!-- .site-info -->
			</div>
		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>