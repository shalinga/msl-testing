$slideshow = {
	context: false,
	tabs: false,
	timeout: 7000,      // time before next slide appears (in ms)
	slideSpeed: 1000,   // time it takes to slide in each slide (in ms)
	tabSpeed: 1000,      // time it takes to slide in each slide (in ms) when clicking through tabs
	fx: 'fade',   // the slide effect to use
    
    init: function() {
        // set the context to help speed up selectors/improve performance
        this.context = jQuery('#slideshow');
        
        // set tabs to current hard coded navigation items
        this.tabs = jQuery('ul.slides-nav li', this.context);
        
        // remove hard coded navigation items from DOM 
        // because they aren't hooked up to jQuery cycle
        this.tabs.remove();
        
        // prepare slideshow and jQuery cycle tabs
        this.prepareSlideshow();
    },
    
    prepareSlideshow: function() {
        // initialise the jquery cycle plugin -
        // for information on the options set below go to: 
        // http://malsup.com/jquery/cycle/options.html
        jQuery('div.slides > ul', $slideshow.context).cycle({
            fx: $slideshow.fx,
            timeout: $slideshow.timeout,
            speed: $slideshow.slideSpeed,
            fastOnEvent: $slideshow.tabSpeed,
            pager: jQuery('ul.slides-nav', $slideshow.context),
            pagerAnchorBuilder: $slideshow.prepareTabs,
            before: $slideshow.activateTab,
            pauseOnPagerHover: true,
            pause: true
        });            
    },
    
    prepareTabs: function(i, slide) {
        // return markup from hardcoded tabs for use as jQuery cycle tabs
        // (attaches necessary jQuery cycle events to tabs)
        return $slideshow.tabs.eq(i);
    },

    activateTab: function(currentSlide, nextSlide) {
        // get the active tab
        var activeTab = jQuery('a[href="#' + nextSlide.id + '"]', $slideshow.context);
        
        // if there is an active tab
        if(activeTab.length) {
            // remove active styling from all other tabs
            $slideshow.tabs.removeClass('on');
            
            // add active styling to active button
            activeTab.parent().addClass('on');
        }            
    }            
};

jQuery(function() {
    // add a 'js' class to the body
    jQuery('body').addClass('js');
    
    // initialise the slideshow when the DOM is ready
    $slideshow.init();
});

$slideshowpages = {
	context: false,
	tabs: false,
	timeout: 7000,      // time before next slide appears (in ms)
	slideSpeed: 1000,   // time it takes to slide in each slide (in ms)
	tabSpeed: 1000,      // time it takes to slide in each slide (in ms) when clicking through tabs
	fx: 'fade',   // the slide effect to use
    
    init: function() {
        // set the context to help speed up selectors/improve performance
        this.context = jQuery('#slideshowpages');
        
        // set tabs to current hard coded navigation items
        this.tabs = jQuery('ul.slides-nav li', this.context);
        
        // remove hard coded navigation items from DOM 
        // because they aren't hooked up to jQuery cycle
        this.tabs.remove();
        
        // prepare slideshow and jQuery cycle tabs
        this.prepareSlideshowpages();
    },
    
    prepareSlideshowpages: function() {
        // initialise the jquery cycle plugin -
        // for information on the options set below go to: 
        // http://malsup.com/jquery/cycle/options.html
        jQuery('div.slides > ul', $slideshowpages.context).cycle({
            fx: $slideshowpages.fx,
            timeout: $slideshowpages.timeout,
            speed: $slideshowpages.slideSpeed,
            fastOnEvent: $slideshowpages.tabSpeed,
            pager: jQuery('ul.slides-nav', $slideshowpages.context),
            pagerAnchorBuilder: $slideshowpages.prepareTabs,
            before: $slideshowpages.activateTab,
            pauseOnPagerHover: true,
            pause: true
        });            
    },
    
    prepareTabs: function(i, slide) {
        // return markup from hardcoded tabs for use as jQuery cycle tabs
        // (attaches necessary jQuery cycle events to tabs)
        return $slideshowpages.tabs.eq(i);
    },

    activateTab: function(currentSlide, nextSlide) {
        // get the active tab
        var activeTab = jQuery('a[href="#' + nextSlide.id + '"]', $slideshowpages.context);
        
        // if there is an active tab
        if(activeTab.length) {
            // remove active styling from all other tabs
            $slideshowpages.tabs.removeClass('on');
            
            // add active styling to active button
            activeTab.parent().addClass('on');
        }            
    }            
};

jQuery(function() {
    // add a 'js' class to the body
    jQuery('body').addClass('js');
    
    // initialise the slideshow when the DOM is ready
    $slideshowpages.init();
});  

$slideshowfeaturevids = {
	context: false,
	tabs: false,
	timeout: 0,      // time before next slide appears (in ms)
	slideSpeed: 0,   // time it takes to slide in each slide (in ms)
	tabSpeed: 0,      // time it takes to slide in each slide (in ms) when clicking through tabs
	fx: 'fade',   // the slide effect to use
    
    init: function() {
        // set the context to help speed up selectors/improve performance
        this.context = jQuery('#slideshowfeaturevids');
        
        // set tabs to current hard coded navigation items
        this.tabs = jQuery('ul.slides-nav li', this.context);
        
        // remove hard coded navigation items from DOM 
        // because they aren't hooked up to jQuery cycle
        this.tabs.remove();
        
        // prepare slideshowfeaturevids and jQuery cycle tabs
        this.prepareSlideshowfeaturevids();
    },
    
    prepareSlideshowfeaturevids: function() {
        // initialise the jquery cycle plugin -
        // for information on the options set below go to: 
        // http://malsup.com/jquery/cycle/options.html
        jQuery('div.slides > ul', $slideshowfeaturevids.context).cycle({
            fx: $slideshowfeaturevids.fx,
            timeout: $slideshowfeaturevids.timeout,
            speed: $slideshowfeaturevids.slideSpeed,
            fastOnEvent: $slideshowfeaturevids.tabSpeed,
            pager: jQuery('ul.slides-nav', $slideshowfeaturevids.context),
            pagerAnchorBuilder: $slideshowfeaturevids.prepareTabs,
            before: $slideshowfeaturevids.activateTab,
            pauseOnPagerHover: true,
            pause: true
        });            
    },
    
    prepareTabs: function(i, slide) {
        // return markup from hardcoded tabs for use as jQuery cycle tabs
        // (attaches necessary jQuery cycle events to tabs)
        return $slideshowfeaturevids.tabs.eq(i);
    },

    activateTab: function(currentSlide, nextSlide) {
        // get the active tab
        var activeTab = jQuery('a[href="#' + nextSlide.id + '"]', $slideshowfeaturevids.context);
        
        // if there is an active tab
        if(activeTab.length) {
            // remove active styling from all other tabs
            $slideshowfeaturevids.tabs.removeClass('on');
            
            // add active styling to active button
            activeTab.parent().addClass('on');
        }            
    }            
};

jQuery(function() {
    // add a 'js' class to the body
    jQuery('body').addClass('js');
    
    // initialise the slideshow when the DOM is ready
    $slideshowfeaturevids.init();
});  

$slideshowvids = {
	context: false,
	tabs: false,
	timeout: 0,      // time before next slide appears (in ms)
	slideSpeed: 0,   // time it takes to slide in each slide (in ms)
	tabSpeed: 0,      // time it takes to slide in each slide (in ms) when clicking through tabs
	fx: 'fade',   // the slide effect to use
    
    init: function() {
        // set the context to help speed up selectors/improve performance
        this.context = jQuery('#slideshowvids');
        
        // set tabs to current hard coded navigation items
        this.tabs = jQuery('ul.slides-nav li', this.context);
        
        // remove hard coded navigation items from DOM 
        // because they aren't hooked up to jQuery cycle
        this.tabs.remove();
        
        // prepare slideshowvids and jQuery cycle tabs
        this.prepareslideshowvids();
    },
    
    prepareslideshowvids: function() {
        // initialise the jquery cycle plugin -
        // for information on the options set below go to: 
        // http://malsup.com/jquery/cycle/options.html
        jQuery('div.slides > ul', $slideshowvids.context).cycle({
            fx: $slideshowvids.fx,
            timeout: $slideshowvids.timeout,
            speed: $slideshowvids.slideSpeed,
            fastOnEvent: $slideshowvids.tabSpeed,
            pager: jQuery('ul.slides-nav', $slideshowvids.context),
            pagerAnchorBuilder: $slideshowvids.prepareTabs,
            before: $slideshowvids.activateTab,
            pauseOnPagerHover: true,
            pause: true
        });            
    },
    
    prepareTabs: function(i, slide) {
        // return markup from hardcoded tabs for use as jQuery cycle tabs
        // (attaches necessary jQuery cycle events to tabs)
        return $slideshowvids.tabs.eq(i);
    },

    activateTab: function(currentSlide, nextSlide) {
        // get the active tab
        var activeTab = jQuery('a[href="#' + nextSlide.id + '"]', $slideshowvids.context);
        
        // if there is an active tab
        if(activeTab.length) {
            // remove active styling from all other tabs
            $slideshowvids.tabs.removeClass('on');
            
            // add active styling to active button
            activeTab.parent().addClass('on');
        }            
    }            
};

jQuery(function() {
    // add a 'js' class to the body
    jQuery('body').addClass('js');
    
    // initialise the slideshow when the DOM is ready
    $slideshowvids.init();
});

(function($){
	$.fn.navslide = function(options){
		var defaults = { 
			start: 1, // where should the carousel start?
			display: 1, // how many blocks do you want to move at 1 time?
			axis: 'x', // vertical or horizontal scroller? ( x || y ).
			controls: true, // show left and right navigation buttons.
			pager: false, // is there a page number navigation present?
			interval: false, // move to another block on intervals.
			intervaltime: 0, // interval time in milliseconds.
			rewind: false, // If interval is true and rewind is true it will play in reverse if the last slide is reached.
			animation: true, // false is instant, true is animate.
			duration: 500, // how fast must the animation move in ms?
			callback: null // function that executes after every move
		};
		var options = $.extend(defaults, options);  

		var oSlider = $(this);
		var oViewport = $('.slideport:first', oSlider);
		var oContent = $('.slideview:first', oSlider);
		var oPages = oContent.children();
		var oBtnNext = $('.next:first', oSlider);
		var oBtnPrev = $('.prev:first', oSlider);
		var oPager = $('.pager:first', oSlider);
		var iPageSize, iSteps, iCurrent, oTimer, bPause, bForward = true, bAxis = options.axis == 'x';

		return this.each(function(){
			initialize();
		});
		function initialize(){
			iPageSize = bAxis ? $(oPages[0]).outerWidth(true) : $(oPages[0]).outerHeight(true);
			var iLeftover = Math.ceil(((bAxis ? oViewport.outerWidth() : oViewport.outerHeight()) / (iPageSize * options.display)) -1);
			iSteps = Math.max(1, Math.ceil(oPages.length / options.display) - iLeftover);
			iCurrent = Math.min(iSteps, Math.max(1, options.start)) -2;
			oContent.css(bAxis ? 'width' : 'height', (iPageSize * oPages.length));
			move(1);
			setEvents();
		}
		function setEvents(){
			if(options.controls && oBtnPrev.length > 0 && oBtnNext.length > 0){
				oBtnPrev.click(function(){move(-1); return false;});
				oBtnNext.click(function(){move( 1); return false;});
			}
			if(options.interval){
				oSlider.hover(function(){clearTimeout(oTimer); bPause = true},function(){bPause = false; setTimer();});
			}
			if(options.pager && oPager.length > 0){
				$('a',oPager).click(setPager);
			}
		}
		function setButtons(){
			if(options.controls){
				oBtnPrev.toggleClass('disable', !(iCurrent > 0));
				oBtnNext.toggleClass('disable', !(iCurrent +1 < iSteps));
			}
			if(options.pager){
				var oNumbers = $('.pagenum', oPager);
				oNumbers.removeClass('active');
				$(oNumbers[iCurrent]).addClass('active');
			}			
		}		
		function setPager(oEvent){
			if($(this).hasClass('pagenum')){
				iCurrent = parseInt(this.rel) -1;
				move(1);
			}
			return false;
		}
		function setTimer(){
			if(options.interval && !bPause){
				clearTimeout(oTimer);
				oTimer = setTimeout(function(){
					iCurrent = !options.rewind && (iCurrent +1 == iSteps) ? -1 : iCurrent;
					bForward = iCurrent +1 == iSteps ? false : iCurrent == 0 ? true : bForward;
					move((options.rewind ? (bForward ? 1 : -1) : 1));
				}, options.intervaltime);
			}
		}
		function move(iDirection){
			if(iCurrent + iDirection > -1 && iCurrent + iDirection < iSteps){
				iCurrent += iDirection;
				var oPosition = {};
				oPosition[bAxis ? 'left' : 'top'] = -(iCurrent * (iPageSize * options.display));	
				oContent.animate(oPosition,{
					queue: false,
					duration: options.animation ? options.duration : 0,
					complete: function(){
						if(typeof options.callback == 'function')
						options.callback.call(this, oPages[iCurrent], iCurrent);
					}
				});
				setButtons();
				setTimer();
			}
		}
	};
})(jQuery);