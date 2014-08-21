<?php
	global $solostream_options;
	if ( $solostream_options['solostream_layout'] !== "Full-Width" ) {
?>

		<div id="contentright">

			<div id="sidebar" class="clearfix">
<!--
<div id="facebook" class="widget facebook">
<div class="fb-like-box" data-href="http://www.facebook.com/pages/Mother-Sri-Lanka/189915701072498" data-width="318" data-show-faces="true" data-stream="false" data-header="false"></div>
</div>

<div id="twtr" class="widget twtr">
						<h3 class="widgettitle">
							<span>Twitter Updates</span>
						</h3>
	
					<script src="http://widgets.twimg.com/j/2/widget.js"></script>
					<script>
					new TWTR.Widget({
					  version: 2,
					  type: 'profile',
					  rpp: 3,
					  interval: 30000,
					  width: 318,
					  height: 200,
					  theme: {
					    shell: {
					      background: '#fcfcfc',
					      color: '#ffffff'
					    },
					    tweets: {
					      background: '#ffffff',
					      color: '#ffffff',
					      links: '#000000'
					    }
					  },
					  features: {
					    scrollbar: false,
					    loop: false,
					    live: false,
					    behavior: 'all'
					  }
					}).render().setUser('mother_srilanka').start();
					</script>
		</div> -->

				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar-Wide - Top') ) : ?>
				<div class="widget">
					<h3 class="widgettitle">Text Widget</h3>
					<div class="textwidget">
						This is a widget area. Visit the Widget page in your WordPress control panel to add some content here
					</div>
				</div>

				<div class="widget">
					<h3 class="widgettitle">Text Widget</h3>
					<div class="textwidget">
						This is a widget area. Visit the Widget page in your WordPress control panel to add some content here
					</div>
				</div>
				<?php endif; ?>
			</div>

			<div id="sidebar-bottom" class="clearfix">

				<div id="sidebar-bottom-left">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar-Wide - Bottom Left') ) : ?>
					<?php endif; ?>
				</div>

				<div id="sidebar-bottom-right">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar-Wide - Bottom Right') ) : ?>
					<?php endif; ?>
				</div>

			</div>

		</div>

<?php } ?>