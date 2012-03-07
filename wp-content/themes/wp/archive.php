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
<div class="the_excerpt">
<?php if(is_home()) {
query_posts( $query_string . '&cat=4' );
}?>
<?php if (have_posts()) : ?>
     <?php while (have_posts()) : the_post();
	  ?>
          <div class="entry">
          <h2  id="post-<?php the_ID(); ?>"> <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
           Author: <?php the_author(); ?>
           Date: <?php the_date(); ?>
           <?php the_content('Read more...'); ?>
           <?php the_excerpt(); ?>
           Category: <?php the_category(', '); ?>
           <?php the_tags(); ?></div>
      <?php endwhile; ?>
<?php endif;  ?> 
</div>
<?php if(is_home()){echo 'home';}
elseif (is_page()){ echo 'page';}
elseif (is_single()){echo 'single';}
elseif (is_category()){echo 'category';}
elseif (is_front()){echo 'front';}
else echo 'a';
?>
<?php get_footer();?>

