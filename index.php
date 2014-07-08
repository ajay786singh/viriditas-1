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
    <script src="js/html5shiv.min.js"></script>
</head>
<body>

    <section role="banner">
        <header>
            <div class="logo">
                <a href="#"><img src="http://placehold.it/350x100&text=logo" alt=""></a>
            </div>
            <nav>
                <ul>
                    <li><a href="#">Item 1</a></li>
                    <li><a href="#">Item 2</a></li>
                </ul>
            </nav>
        </header>
    </section>

    <section role="slider">
        <header>
            <hgroup>
                <h1>Headline</h1>
                <h2>text description</h2>    
            </hgroup>
        </header>
    </section>
    
    <section role="main">
        <article>
                <img src="http://placehold.it/300x300&text=image" alt="">
            <div class="content">
                <h3>Title for headline</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque eget adipiscing arcu. Quisque lobortis fringilla nulla, sit amet ullamcorper nibh scelerisque id. </p>
            </div>
        </article>

         <article>
                <img src="http://placehold.it/300x300&text=image" alt="">
            <div class="content">
                <h3>Title for headline</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque eget adipiscing arcu. Quisque lobortis fringilla nulla, sit amet ullamcorper nibh scelerisque id. </p>
            </div>
        </article>
    </section>

    <footer>
        <div class="footer-inner">
            <div class="content">
                <h3>About</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque eget adipiscing arcu. Quisque lobortis fringilla nulla, sit amet ullamcorper nibh scelerisque id.
                <br><b><a class="read-more" href="#">Read more...</a></b></p>
            </div>
            <div class="content">
                <h3>Tell a friend</h3>
                <ul class="social-share">
                    <li><a href="<?php echo $twitter_url;?>" target="_blank"><span class='symbol'>circletwitterbird</span></a></li>
                    <li><a href="<?php echo $fb_url;?>" target="_blank"><span class='symbol'>circlefacebook</span></a></li>
                    <li><a href="<?php echo $gplus_url;?>" target="_blank"><span class='symbol'>circlegoogleplus</span></a></li>
                </ul>
            </div>
            <div class="content">
                <h3>Address</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque eget adipiscing arcu.</p>
            </div>      
        </div>
    </footer>

    <section role="contentinfo">
        <div class="inner">
        <div class="contact">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque eget adipiscing arcu.</p>
        </div>
        <div class="copyright">
            <p>Terms & Conditions. Copyrights &copy; 2014. | Made with purpose by B Corp certified <a href="http://hypenotic.com" target="_blank">Hypenotic</a></p>
        </div>
    </div>
    </section>

<script src="bower/jquery/dist/jquery.min.js"></script>
<script src="js/app.min.js"></script>
</body>
</html>