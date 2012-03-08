<div class="header">
<?php get_header(); ?>
</div>
<div class="container">
<div class="sidebar">
<?php wp_list_pages( 'depth=3&title_li=<h2>Pages</h2>'); ?>
<ul>
<li><h2><?php _e('Categories'); ?></h2>
<ul>
<li id="calendar"><h2><?php _e('Calendar'); ?></h2>
<?php get_calendar(); ?>
</li>
<li><h2><?php _e('Meta'); ?></h2>
<ul>
<?php wp_register(); ?>
<li><?php wp_loginout(); ?></li>
<?php wp_meta(); ?>
</ul>
</li>
<ul>
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar() ) : else : ?>

<li id="search">
<?php include(TEMPLATEPATH . '/searchform.php'); ?>
</li>
<?php wp_list_cats('sort_column=name&optioncount=1&hierarchical=0'); ?>
<?php endif; ?>
</ul>
</li>
<li><h2><?php _e('Archives'); ?></h2>
<ul>
<?php wp_get_archives('type=monthly'); ?>
<?php get_links_list(); ?>
</ul>
</li>
</ul>
</div>
<div class="content"  id="post-<?php the_ID(); ?>">
<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
<h2> <a href="<?php the_permalink(); ?>"><?php the_title(); ?> </a></h2>
<div class="entry">
<?php the_excerpt();?>
<p class="postmetadata">
<?php _e('Filed under&#58;'); ?> <?php the_category(', ') ?> <?php _e('by'); ?> <?php  the_author(); ?><br />
<?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?> <?php edit_post_link('Edit', ' &#124; ', ''); ?>
				</p>
</div>
<?php endwhile; ?>
<div class="navigation">
<?php posts_nav_link('in between','before','after'); ?>
<?php else : ?>
<div class="content">
<h2><?php _e('Not Found'); ?></h2>
</div>
<?php endif; ?>

</div>
</div>
<div id="footer">
<?php  get_footer();?>
</div>