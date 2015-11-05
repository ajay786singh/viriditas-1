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
					<p>Be the first to learn about changes to our clinic schedule, products and class info.</p>
					<div class="newsletter newsletter-subscription">
						<?php dynamic_sidebar('sidebar-3');?>
					</div>
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
						2775 Dundas St. West, Suite 101<br>
						Toronto, ON, M6P 1Y4<br>
						Tel: 416-767-3428<br>
						Fax: 416-767-1215<br>
						<a href="mailto: info@viriditasherbalproducts.com">info@viriditasherbalproducts.com</a>
					</p>
				</div>
			</div>
		</div>
		<div class="container">
			<p class="hypenotic-slug">Made with Purpose by B Corp Certified <a href="http://www.hypenotic.com/" target="_blank">Hypenotic</a></p>
		</div>
    </footer>
<?php wp_footer(); ?>
<script type="text/javascript">
//<![CDATA[
	if (typeof newsletter_check !== "function") {
		window.newsletter_check = function (f) {
			var re = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-]{1,})+\.)+([a-zA-Z0-9]{2,})+$/;
			if (!re.test(f.elements["ne"].value)) {
				alert("The email is not correct");
				return false;
			}
			for (var i=1; i<20; i++) {
			if (f.elements["np" + i] && f.elements["np" + i].required && f.elements["np" + i].value == "") {
				alert("");
				return false;
			}
			}
			if (f.elements["ny"] && !f.elements["ny"].checked) {
				alert("You must accept the privacy statement");
				return false;
			}
			return true;
		}
	}
//]]>
</script>
</body>
</html>
