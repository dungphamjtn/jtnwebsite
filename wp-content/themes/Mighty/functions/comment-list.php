<?php
function rm_comments($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
         <?php echo get_avatar($comment,$size='80'); ?>
		 
		 <div class="content">
 
		 <span class="author"><?php comment_author_link()?></span>
		 <span class="date"><?php printf(__('%s'), get_comment_date()) ?></span>
		 <span class="time"><?php printf(__('%s'), get_comment_time()) ?></span>
		 
        <span class="reply"> <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></span>
		
		<br /><br /><!-- BreakLine -->


      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.') ?></em>
         <br />
		<?php endif; ?>
 
      <div class="content_text"><?php comment_text(); ?></div>
		</div> 
     </div>
<?php
        }
	?>