<?php
/* Function to add social sharing buttons on right side of any template or page

add this line:- if(social_share()) { social_share();}
*/

function social_share() {
?>

<div class="social-share">
            	<?php
				$post_title=get_the_title();
				$post_link= urlencode(get_permalink());
				$post_description= get_the_excerpt();
				$post_img = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
				if($post_img==''){
					$post_img=get_bloginfo('template_url').'/images/logo.png';	
				}
				$twitter_url ='http://twitter.com/home?status='.$post_link.'+'.$post_title;
				$fb_url = 'https://www.facebook.com/sharer.php?s=100&amp;p[title]='.$post_title.'&amp;p[summary]='.$post_description.'&amp;p[url]='.$post_link.'&amp;p[images][0]='.$post_img;
				$pintrest_url='http://pinterest.com/pin/create/bookmarklet/?media='.$post_img.'&amp;url='.$post_link.'&amp;is_video=false&amp;description='.$post_description;
				$gplus_url='https://plus.google.com/share?url='.$post_link;
				$linkedin_url='http://www.linkedin.com/shareArticle?mini=true&amp;url='.$post_link.'&amp;title='.$post_title.'&amp;source='.$post_link;
				//$mail_url='mailto:?subject='.$post_title.'&amp;body='.$post_description .'-'.$post_link;
				$mail_url='http://www.sharethis.com/share?url='.$post_link.'&title='.$post_title.'&summary='.$post_description.'&img='.$post_img;
				?>
            	<ul>
                	<li><a id="facebook" href="<?php echo $fb_url;?>" rel="nofollow" target="_blank">Facebook</a></li>
                	<li><a id="twitter"  href="<?php echo $twitter_url;?>" rel="nofollow" target="_blank">Twitter</a></li>
					<li><a id="mail" href="<?php echo $mail_url;?>" rel="nofollow" target="_blank">Email</a></li>
                	<li><a id="pinterest" href="<?php echo $pintrest_url;?>" rel="nofollow" target="_blank">Pinterest</a></li>
                </ul>
            </div>
<?php 

}

 ?>