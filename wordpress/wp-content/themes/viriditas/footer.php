</div>
<!-- Wrapper Ends Here -->
    <footer>
		<div class="container">
			<div class="span-11 center">
				<div class="span-6 footer-logo">
					<img src="<?php bloginfo('template_url');?>/dist/images/logo@2x.png">
					<div class="newsletter-box">
						<h6>Viriditas</h6>
						<p>Echo Park ugh umami pug messenger bag.</p>
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
				</div>
				<div class="span-6">
					<div class="span-6">
						<h6>Resources</h6>
						<p>Austin 3 wolf moon disrupt church-key stumptown butcher swag. XOXO health goth  Austin, messenger bag photo booth migas plaid post-ironic church-key. Direct trade Carles health goth four dollar toast.</p>
					</div>
					<div class="span-6">
						<h6>Contact</h6>
						<p>
							2775 Dundas St. West<br>
							Toronto, ON, M6P 1Y4<br>
							Tel: 416-767-3428<br>
							Fax: 416-767-1215<br>
							<a href="mailto: redden@viriditasherbalproducts.com">redden@viriditasherbalproducts.com</a>
						</p>
					</div>
				</div>
			</div>
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