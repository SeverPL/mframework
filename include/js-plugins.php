<?php
/*
 * m.framework - engine - v.2.0.0.0 a
* 20 czerwca 2014 10:19
*
* Copyright by Mateusz Wiśniewski © 2014
*/
class jsplugins{
	function jquery_init() { //inicjalizacja jquery
		echo '<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>' . "\n";
	}
	
	function highslide_init(){ //inicjalizacja highslide
		?>
		<script type="text/javascript" src="js/highslide/highslide-with-gallery.js"></script>
		<link rel="stylesheet" type="text/css" href="js/highslide/highslide.css" />
		<script type="text/javascript">
		hs.graphicsDir = 'highslide/graphics/';
		hs.align = 'center';
		hs.transitions = ['expand', 'crossfade'];
		hs.wrapperClassName = 'dark borderless floating-caption';
		hs.fadeInOut = true;
		hs.dimmingOpacity = .75;
		
		// Add the controlbar
		if (hs.addSlideshow) hs.addSlideshow({
			//slideshowGroup: 'group1',
			interval: 5000,
			repeat: false,
			useControls: true,
			fixedControls: 'fit',
			overlayOptions: {
				opacity: .6,
				position: 'bottom center',
				hideOnMouseOut: true
			}
		});
		</script>
	<?php 
	}
}
?>