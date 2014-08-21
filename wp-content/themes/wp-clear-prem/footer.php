	</div>

	<?php include (TEMPLATEPATH . '/banner728-bottom.php'); ?>

</div>

<?php /* footer widgets */ if ( is_active_sidebar('widget-5') || is_active_sidebar('widget-6') || is_active_sidebar('widget-7') || is_active_sidebar('widget-8') ) { ?>

<div id="footer-widgets" class="maincontent">
	<div class="limit clearfix">
		<div class="footer-widget1">
			<?php dynamic_sidebar('Footer Widget 1'); ?>
		</div>
		<div class="footer-widget2">
			<?php dynamic_sidebar('Footer Widget 2'); ?>
		</div>
		<div class="footer-widget3">
			<?php dynamic_sidebar('Footer Widget 3'); ?>
		</div>
		<div class="footer-widget4">
			<?php dynamic_sidebar('Footer Widget 4'); ?>
		</div>
	</div>
</div>
<?php } ?>

<?php if (has_nav_menu('footernav')) { ?>
<div id="footnav">
	<div class="limit clearfix">
		<?php wp_nav_menu( array( 'theme_location' => 'footernav' ) ); ?>
	</div>
</div>
<?php } ?>

<div id="footer">
	<div class="limit clearfix">

		<div class="footer_page terms">
					&copy; <a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a> <?php echo date('Y'); ?>. <?php _e("All rights reserved.", "solostream"); ?>

					<!--
						<?php /* Solostream Footer Credit. You can only remove this if you own the Premium License */ $link = array(
						"<a href = \"http://www.solostream.com\">WordPress Themes</a>",
						"<a href = \"http://www.solostream.com\">Premium WordPress Themes</a>",
						"<a href = \"http://www.solostream.com\">WordPress Business Themes</a>",
						"<a href = \"http://www.solostream.com\">Business WordPress Themes</a>",
						"<a href = \"http://www.wp-magazine.com\">WordPress Magazine Themes</a>"); 
						srand(time()); 
						$random = (rand()%5); 
						echo ("$link[$random]");
						/* End Solostream Footer Credit. */ ?>
								
					</div> -->
						<?php // include (TEMPLATEPATH . '/sub-icons.php'); ?>

						<a href="/terms-of-use/" target="_blank"> Terms of Use</a>
						&
						<a href="/privacy-policy/" target="_blank">Privacy Policy</a>
					</div>

					<div align="right">
						Sponsored by <a title="Sponsored by Venturit Inc." rel="external" href="http://www.venturit.com/"><img 	class="Venturit" src="<?php bloginfo('stylesheet_directory'); ?>/images/Venturit.png" alt="Sponsored by Venturit Inc." align="top" width="24" height="24" /></a> Venturit Inc.
					</div>

</div>

</div>

</div>

<?php wp_footer(); ?>

</body>

</html>