<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php bloginfo( 'name' ); ?></title>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head();?>
</head>
<body>

<?php get_header();?>
<div class="content">
<?php if (have_posts()) : ?>
     <?php while (have_posts()) : the_post();
	  ?>
          <div class="entry-post">
        	 <center> <h2  id="post-<?php the_ID(); ?>"> <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
           Author: <?php the_author(); ?>
           Date: <?php the_date(); ?></center>
            Category: <?php the_category(', '); ?>
           <?php the_content('Read more...'); ?>
           <?php the_excerpt(); ?>
           <?php the_tags(); ?>
          </div>
      <?php endwhile; ?>
<?php endif;  ?> 
</div>
<?php get_footer();?>

