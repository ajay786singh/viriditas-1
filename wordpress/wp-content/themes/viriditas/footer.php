</div>
<!-- Wrapper Ends Here -->
<?php 
	$privacy_policy_page_id=1096;
	$terms_conditions_page_id=1098;
	$resources_page_id=1100;
?>
    <footer>
		<div class="container">
			<div class="secondary footer-logo">
				<img src="<?php bloginfo('template_url');?>/dist/images/footer-logo.png">
				<div class="newsletter-box">
					<h5>Viriditas</h5>
					<p>Echo Park ugh umami pug messenger bag.</p>
					<form action="#" method="post">
						<input type="hidden" value="2" name="group[2][2]" id="mce-group[2]-2-1">
						<fieldset>
							<ol>
								<li><input name="EMAIL" placeholder="you@yourdomain.com" type="email" value=""  id="mce-EMAIL" required></li>
								<li><input type="submit" value="Submit"></li>
							</ol>
						</fieldset>
					</form>
					<div class="copyright">
						<p>copyright Viriditas <?php echo date('Y');?></p>
						<p><a href="<?php echo get_permalink($privacy_policy_page_id);?>">Privacy policy</a> | <a href="<?php echo get_permalink($terms_conditions_page_id);?>">Terms of condition</a></p>
					</div>
					
				</div>
			</div>
			<div class="secondary">
				<div class="secondary">
					<?php 
						query_posts('post_type=page&p='.$resources_page_id);
						if(have_posts()):while(have_posts()):the_post();
					?>
					<?php 
						the_title("<h6>","</h6>");
						the_content();
					?>
					<?php endwhile;endif;wp_reset_query();?>
				</div>
				<div class="secondary">
					<h6>Contact Us</h6>
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
    </footer>
<?php wp_footer(); ?>
</body>
</html>