<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Home | Viriditas</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/ico" href="favicon.ico"/>
    <link rel="stylesheet" href="style.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <script src="js/html5shiv.min.js"></script>

    <script>
        (function(d) {
        var config = {
        kitId: 'hzl3mkb',
        scriptTimeout: 3000
        },
        h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='//use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
        })(document);
    </script>

</head>
<body>

    <section role="banner">
        <header>
            <div class="logo">
                <a href="#"><img src="http://placehold.it/350x100&text=Viriditas" alt=""></a>
            </div>
            <nav>
                <ul>
                    <li><a href="#">Order</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Classes</a></li>
                    <li><a href="#">Appointment Booking & Forms</a></li>
                    <li><a href="#">Resources</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
        </header>
    </section>


    <div class="fullscreen background" style="background-image:url('/images/lemon-balm.jpg');" data-img-width="1400" data-img-height="800">
        <div class="content-a">
            <div class="content-b">
                <h1>Herbal Solutions</h1>
                <p>We teach, cure, and make herbal solutions with a holistic approach</p>
                <button>Order Products</button>
                <button>Appointment Bookings and Forms</button>
                <button>Courses and Registration</button>
            </div>
        </div>
    </div>
    <div class="down-arrow">
        <a id="down-link" href="#content" class="target"><i class="fa fa-chevron-down"></i></a>
    </div>

    <footer>
    <div class="footer-inner">
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
    <div class="footer-inner">
        <p class="small">&copy; 2014 Viriditas Inc. All Rights Reserved.</p>
    </div>
</footer>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="js/app.min.js"></script>

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