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
//content
<?php the_content();?>
<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
<?php edit_post_link('Edit', '<p>', '</p>'); ?>
</div>
<?php endwhile; ?>
<div class="content">
<h2><?php _e('Not Found'); ?></h2>
</div>
<?php endif; ?>

</div>
</div>
<div id="footer">
<?php  get_footer();?>
</div>