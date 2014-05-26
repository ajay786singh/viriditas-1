
    var previousScroll = 0, // previous scroll position
        menuOffset = 54, // height of menu (once scroll passed it, menu is hidden)
        detachPoint = 650, // point of detach (after scroll passed it, menu is fixed)
        hideShowOffset = 6; // scrolling value after which triggers hide/show menu

    // on scroll hide/show menu
    jQuery(window).scroll(function() {
      if (!jQuery('nav').hasClass('expanded')) {
        var currentScroll = jQuery(this).scrollTop(), // gets current scroll position
            scrollDifference = Math.abs(currentScroll - previousScroll); // calculates how fast user is scrolling

        // if scrolled past menu
        if (currentScroll > menuOffset) {
          // if scrolled past detach point add class to fix menu
          if (currentScroll > detachPoint) {
            if (!jQuery('nav').hasClass('detached'))
              jQuery('nav').addClass('detached');
          }

          // if scrolling faster than hideShowOffset hide/show menu
          if (scrollDifference >= hideShowOffset) {
            if (currentScroll > previousScroll) {
              // scrolling down; hide menu
              if (!jQuery('nav').hasClass('invisible'))
                jQuery('nav').addClass('invisible');
            } else {
              // scrolling up; show menu
              if (jQuery('nav').hasClass('invisible'))
                jQuery('nav').removeClass('invisible');
            }
          }
        } else {
          // only remove “detached” class if user is at the top of document (menu jump fix)
          if (currentScroll <= 0){
            jQuery('nav').removeClass();
          }
        }

        // if user is at the bottom of document show menu
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
          jQuery('nav').removeClass('invisible');
        }

        // replace previous scroll position with new one
        previousScroll = currentScroll;
      }
    });

    // shows/hides navigation’s popover if class "expanded"
    //jQuery('nav').on('click touchstart', function(event) {
    //  showHideNav();
    //  event.preventDefault();
    //});


  // shows the navigation’s popover
    function showNav() {
      jQuery('nav').removeClass('invisible').addClass('expanded');
      jQuery('#container').addClass('blurred');
      window.setTimeout(function(){jQuery('body').addClass('no_scroll');}, 200); // Firefox hack. Hides scrollbar as soon as menu animation is done
      jQuery('#navigation a').attr('tabindex', ''); // links inside navigation should be TAB selectable
    }

    // hides the navigation’s popover
    function hideNav() {
      jQuery('#container').removeClass('blurred');
      window.setTimeout(function(){jQuery('body').removeClass();}, 10); // allow animations to start before removing class (Firefox)
      jQuery('nav').removeClass('expanded');
      jQuery('#navigation a').attr('tabindex', '-1'); // links inside hidden navigation should not be TAB selectable
      jQuery('.icon').blur(); // deselect icon when navigation is hidden
    }

    // checks if navigation’s popover is shown
    function showHideNav() {
      if (jQuery('nav').hasClass('expanded')) {
        hideNav();
      } else {
        showNav();
      }
    }

    // keyboard shortcuts
    jQuery('body').keydown(function(e) {
      // menu accessible via TAB as well
      if (jQuery("nav .icon").is(":focus")) {
        // if ENTER/SPACE show/hide menu
        if (e.keyCode === 13 || e.keyCode === 32) {
          showHideNav();
          e.preventDefault();
        }
      }

      // if ESC show/hide menu
      if (e.keyCode === 27 || e.keyCode === 77) {
        showHideNav();
        e.preventDefault();
      }
    });