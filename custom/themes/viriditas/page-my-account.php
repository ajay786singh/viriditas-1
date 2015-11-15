<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section role="content">
    <div class="container">
		<article class="secondary">
			<?php 
				if ( !is_user_logged_in() )  {
					if(is_page('login')) { 
						the_title("<h1>","</h1>");
					} else if(is_page('register'))  {
						echo "<h1>Account Setup</h1>";
					}
					the_content();
				} else {
					echo "<h1>Welcome</h1>";
			?>
				<p>You are already registered with us.</p>
				<p>Please wait, you will be redirecting to profile page in <span id="countdown">5</span> seconds .</p>
				<script type="text/javascript">
					(function () {
						var timeLeft = 5,
							cinterval;

						var timeDec = function (){
							timeLeft--;
							document.getElementById('countdown').innerHTML = timeLeft;
							if(timeLeft === 0){
								clearInterval(cinterval);
								window.location="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>";
							}
						};
						cinterval = setInterval(timeDec, 1000);
					})();
				</script>
			<?php		
				}
			?>
			
		</article>	
	</div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>