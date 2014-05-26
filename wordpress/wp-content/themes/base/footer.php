<footer style="background-color: red;">

	<!--Footer row -->
	<div class="container">
		<div class="span-3">
			<h5>Column 1</h5>
			<?php recentposts(); ?>
		</div>
		<div class="span-3">
			<h5>Column 2</h5>
			<form action="#" method="post">
            	<fieldset>
                	<ol>
                		<li><input name="EMAIL" placeholder="you@yourdomain.com" type="email" value=""  id="mce-EMAIL" required></li>
              		</ol>
              		<input type="submit" value="Yes">
            	</fieldset>
          	</form>
		</div>
		<div class="span-3">
			<h5>Column 3</h5>
			<p>content here</p>
		</div>
	</div>
</footer>
	<!--Copyright row -->
	<div class="container">
		<div class="span-2">
			<p class="small">&copy; 1998 - <?php echo date("Y") ?> Company Name. All Rights Reserved.</p>
		</div>
		<div class="span-2">
			<p class="small">Made with love by B Corp certified <a href="http://hypenotic.com">Hypenotic</a></p>
		</div>
	</div>


<?php wp_footer();?>
</body>
</html>