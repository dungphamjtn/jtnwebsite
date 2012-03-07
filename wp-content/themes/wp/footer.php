<div class="footer">
    	<?php /*?><!--<div class="footer_01">
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer 1") ) : ?>
        <h2>FOOTER COLUMN 1</h2><p>Footer 1</p>
		<?php endif; ?>
        </div>
        <div class="footer_02">
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer 2") ) : ?>
        <h2>FOOTER COLUMN 2</h2>
        <p>Footer 2</p>
        <?php endif;?>
        </div>
        <div class="footer_03">
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer 3") ) : ?>
        <h2>FOOTER COLUMN 3</h2>
        <p>Footer 3</p>
        <?php endif;?>
        </div>
        <div class="footer_04">
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer 4") ) : ?>
        <h2>FOOTER COLUMN 4</h2>
        <p>Footer 4</p>
        <?php endif;?>
        </div>
        <br class="clear" />
    </div>--><?php */?>
    <?php recent_posts('limit=4'); ?>
    <?php wp_footer();?>
</div>
<!-- End wraper -->
</div>
</div>
</body>
</html>