/*
* Fade and parallax for header.
*/
var isMobile;

// Identify if visitor on mobile with lame sniffing to remove parallaxing title
if( navigator.userAgent.match(/Android/i) ||
    navigator.userAgent.match(/webOS/i) ||
    navigator.userAgent.match(/iPhone/i) ||
    navigator.userAgent.match(/iPod/i) ||
    navigator.userAgent.match(/iPad/i) ||
    navigator.userAgent.match(/BlackBerry/)
){
  isMobile = true;
}

jQuery(document).ready(function($) {

  // Global vars
  var $artHeaderInner = $('.bannertext');
  var $artHeader = $('.bannerimage');
  var $nav = $('.nav');
  var windowScroll;


	// Custom Codes
    $(".columns-1").fitVids();
    $(".front-headline").fitText(1.7);
    $(".headline").fitText();
    $(".subhead").fitText(1.4);
    $(".subhead").widowFix();
    $('h1').widowFix();
    $('p').widowFix({linkFix: true});

  // Identify if visitor has a large enough viewport for parallaxing title
  function isLargeViewport() {
    if($nav.css('position') === "relative") {
      return false;
    } else {
      return true;
    }
  }

  // If large viewport and not mobile, parallax the title
  if(!isMobile) {
    $(window).scroll(function() {
      if(isLargeViewport()) {
        slidingTitle();
      }
    });
  }

  // Window gets large enough, need to recalc all parallaxing title values
  $(window).resize(function() {
    if(isLargeViewport()) {
      slidingTitle();
    }
  });

  // Functional parallaxing calculations
  function slidingTitle() {
    //Get scroll position of window
    windowScroll = $(this).scrollTop();

    //Slow scroll of .bannertext scroll and fade it out
    $artHeaderInner.css({
      'margin-top' : -(windowScroll/-2)+"px",
      'opacity' : 1-(windowScroll/450)
    });

    //Slowly parallax the background of .bannerimage
    $artHeader.css({
      'background-position' : 'center ' + (-windowScroll/8)+"px"
    });

    //Fade the .nav out
    $nav.css({
      'opacity' : 1-(windowScroll/275)
    });
  }
});