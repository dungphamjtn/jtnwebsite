<?php
/*
Template Name: JTN
*/
if($_POST[sent]){
	$error = "";
	if(!trim($_POST[your_name])){
		$error .= "<p>Please enter your name</p>";
	}
	if(!filter_var(trim($_POST[your_email]),FILTER_VALIDATE_EMAIL)){
		$error .= "<p>Please enter a valid email address</p>";
	}                        
	if(!trim($_POST[your_message])){
		$error .= "<p>Please enter a message</p>";
	}
	if(!$error){
		$email = mail(get_option("admin_email"),trim($_POST[your_name])." sent you a message from ".get_option("blogname"),stripslashes(trim($_POST[your_message])),"From: ".trim($_POST[your_name])." <".trim($_POST[your_email]).">\r\nReply-To:".trim($_POST[your_email]));
	}
}
?>
<?php get_header(); ?>
<div id="container">
	<div id="content" role="main">
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>	
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<div class="entry-content">
			<?php if($email){ ?>
				<p><strong>Message succesfully sent. I'll reply as soon as I can</strong></p>
			<?php } else { if($error) { ?>
				<p><strong>Your messange hasn't been sent</strong><p>
				<?php echo $error; ?>
			<?php } else { the_content(); } ?>
				<form action="<?php the_permalink(); ?>" id="contact_me" method="post">
					<input type="hidden" name="sent" id="sent" value="1" />
					<ul class="contactform">
						<li>
							<label for="your_name">Name</label>
							<input type="text" name="your_name" id="your_name" value="<?php echo $_POST[your_name];?>" />
						</li>
						<li>
							<label for="your_email">Email</label>
							<input type="text" name="your_email" id="your_email" value="<?php echo $_POST[your_email];?>" />
						</li>
						<li>
							<label for="your_message">Message:</label>
							<textarea name="your_message" id="your_message"><?php echo stripslashes($_POST[your_message]); ?></textarea>
						</li>
						<li>
							<input type="submit" name = "send" value = "Send email" />
						</li>
					</ul>
				</form>
				<?php } ?>
			</div><!-- .entry-content -->
		</div><!-- #post-## -->
		<?php endwhile; ?>
	</div><!-- #content -->
</div><!-- #container -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>