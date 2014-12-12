</div>
<!-- Wrapper Ends Here -->
    <footer>
    <div class="container">
        <div class="content">
            <h5>Get important updates</h5>
            <p>We try and minimize our email time as much as you. Rest assured we'll only send you essential news: new products, office hour changes, class announcements, etc.</p>
            <form action="#" method="post">
            <input type="hidden" value="2" name="group[2][2]" id="mce-group[2]-2-1">
            <fieldset>
                <ol>
                <li><input name="EMAIL" placeholder="you@yourdomain.com" type="email" value=""  id="mce-EMAIL" required></li>
                <li><input type="submit" value="Yes"></li>
              </ol>
            </fieldset>
          </form>
        </div>
        <div class="content">
            <h5>Resources</h5>
            <p>A hollistic approach starts with the mind. Our writings, and DIY herbal recipes and therapeutics are open source because we encourage continued learning. <br><a href="#">Learn more</a></p>
        </div>
        
        <div class="content">
            <h5>Contact</h5>
            <p><b>Viriditas</b><br>
                2775 Dundas West Street<br>
                Toronto, Ontario, M6P 1Y4<br>
                <i class="fa fa-map-marker"></i> <a href="https://goo.gl/maps/d9dEl">Map</a></p>

            <p><i class="fa fa-phone"></i> 416-767-3428<br>
                <i class="fa fa-fax"></i> 416-767-1215<br>
                <i class="fa fa-envelope"></i> <a href="mailto: redden@viriditasherbalproducts.com">Email us</a>
            
        </div>  
    </div>
    <div class="container">
        <p class="small">&copy; 2014 Viriditas Inc. All Rights Reserved.</p>
    </div>
</footer>

<?php /*<script src="<?php bloginfo('template_url' ); ?>/bower_components/jquery/dist/jquery.min.js"></script> 
<script src="<?php bloginfo('template_url' ); ?>/js/app.min.js"></script>*/?>
<?php wp_footer(); ?>

<script>
    

    /*window.viewportUnitsBuggyfill.init();*/


    /* fix vertical when not overflow
call fullscreenFix() if .fullscreen content changes */
function fullscreenFix(){
    var h = jQuery('body').height();
    // set .fullscreen height
    jQuery(".content-b").each(function(i){
        if(jQuery(this).innerHeight() <= h){
            jQuery(this).closest(".fullscreen").addClass("not-overflow");
        }
    });
}
jQuery(window).resize(fullscreenFix);
fullscreenFix();

/* resize background images */
function backgroundResize(){
    var windowH = jQuery(window).height();
    jQuery(".background").each(function(i){
        var path = jQuery(this);
        // variables
        var contW = path.width();
        var contH = path.height();
        var imgW = path.attr("data-img-width");
        var imgH = path.attr("data-img-height");
        var ratio = imgW / imgH;
        // overflowing difference
        var diff = parseFloat(path.attr("data-diff"));
        diff = diff ? diff : 0;
        // remaining height to have fullscreen image only on parallax
        var remainingH = 0;
        if(path.hasClass("parallax")){
            var maxH = contH > windowH ? contH : windowH;
            remainingH = windowH - contH;
        }
        // set img values depending on cont
        imgH = contH + remainingH + diff;
        imgW = imgH * ratio;
        // fix when too large
        if(contW > imgW){
            imgW = contW;
            imgH = imgW / ratio;
        }
        //
        path.data("resized-imgW", imgW);
        path.data("resized-imgH", imgH);
        path.css("background-size", imgW + "px " + imgH + "px");
    });
}
jQuery(window).resize(backgroundResize);
jQuery(window).focus(backgroundResize);
backgroundResize();

</script>
</body>
</html>