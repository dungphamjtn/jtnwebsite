<div class="wrap">
    <div class="icon32" id="<?php echo $this->PARENT_MENU_ICON[ $this->_pluginAdminMenuParentMenu ]; ?>"><br/></div>
	<?php
	if( $_REQUEST['plugin_options_update'] ) {
        // Update the plugin's options.
		$this->_UpdatePluginOptions( $_REQUEST );
	} else if( $_REQUEST['plugin_options_reset'] ) {
        // Reset the plugin's options.
        $this->_ResetPluginOptions();
	} else if( $_REQUEST['plugin_options_uninstall'] ) {
        // Uninstall the plugin by removing the plugin options from the Wordpress database.
        $this->_UnregisterPluginOptions();
    }

    if( $this->_IsPluginInstalled() ) {
    ?>
    <h2><?php echo( $this->_pluginTitle ); ?></h2>
    <form id="org_myplugins_mpf" action="<?php echo( $_pluginAdminMenuParentMenu ); ?>?page=<?php echo( $this->_pluginAdminMenuPageSlug ) ?>" method="post">
    	<?php wp_nonce_field('org-myplugins-projectdonation-update-options'); ?>
        <div id="poststuff" class="metabox-holder has-right-sidebar">
            <!-- Plugin Sidebar -->
            <div id="side-info-column" class="inner-sidebar">
                <div id="side-sortables" class="meta-box-sortables ui-sortable" style="position:relative;">
                    <div class="postbox" id="submitdiv">
                        <div title="<?php _e( 'Click to toggle', TXTDOMAIN ); ?>" class="handlediv"><br/></div>
                        <h3 class="hndle"><span><?php _e( 'Save Changes', TXTDOMAIN ); ?></span></h3>
                        <div class="inside">
                            <div class="submitbox" id="submitpost">
                                <div id="minor-publishing">
                                    <div id="minor-publishing-actions">
                                        <div id="save-action">
                                            <input class="button" id="save-post" type="submit" name="plugin_options_reset" value="<?php _e( 'Restore Settings', TXTDOMAIN ); ?>" onclick='return( confirm( <?php _e( "Do you really want to restore the default settings?\nAll of your changes will be lost.", TXTDOMAIN ); ?> ) );' />
                                        </div>
                                        <div id="preview-action">
                                            <input class="button" type="submit" name="plugin_options_uninstall" value="<?php _e( 'Uninstall', TXTDOMAIN ); ?>" onclick='return( confirm( <?php _e( "Do you really want to uninstall this plugin?\nAll of your changes will be permanently removed from the database.", TXTDOMAIN ); ?> ) );' />
                                        </div>
                                        <br class="clear" />
                                    </div>
                                <div id="misc-publishing-actions">
                                    <?php if ($this->_pluginHome) { ?>
                                    <div class="misc-pub-section">
                                        <span id="plugin-home" class="icon">
                                            <strong><a href="<?php echo $this->_pluginHome; ?>" style="text-decoration: none;"><?php _e( 'Plugin Home', TXTDOMAIN ); ?></a></strong>
                                        </span>
                                    </div>
                                    <?php } if ($this->_pluginComments) { ?>
                                    <div class="misc-pub-section">
                                        <span id="plugin-comments" class="icon">
                                            <a href="<?php echo $this->_pluginComments; ?>" style="text-decoration: none;"><?php _e( 'Plugin Comments', TXTDOMAIN ); ?></a>
                                        </span>
                                    </div>
                                    <?php } if ($this->_pluginRate) { ?>
                                    <div class="misc-pub-section">
                                        <span id="rate-plugin" class="icon">
                                            <a href="<?php echo $this->_pluginRate; ?>" style="text-decoration: none;"><?php _e( 'Rate Plugin', TXTDOMAIN ); ?></a>
                                        </span>
                                    </div>
                                    <?php } if ($this->_pluginMyPlugins) { ?>
                                    <div class="misc-pub-section">
                                        <span id="my-plugins" class="icon">
                                            <a href="<?php echo $this->_pluginMyPlugins; ?>" style="text-decoration: none;"><?php _e( 'My Plugins', TXTDOMAIN ); ?></a>
                                        </span>
                                    </div>
                                    <?php } if ($this->_pluginContact) { ?>
                                    <div class="misc-pub-section curtime misc-pub-section-last">
                                        <span id="contact-me" class="icon">
                                            <a href="<?php echo $this->_pluginContact; ?>" style="text-decoration: none;"><?php _e( 'Contact Me', TXTDOMAIN ); ?></a>
                                        </span>
                                    </div>
                                    <?php } else { ?>
                                    <div class="misc-pub-section curtime misc-pub-section-last">
                                        <span id="contact-me" class="icon">
                                            <strong><?php _e( 'No Contact', TXTDOMAIN ); ?></strong>
                                        </span>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="major-publishing-actions">
                        <div id="publishing-action">
                            <input class="button-primary" type="submit" name="plugin_options_update" value="<?php _e( 'Save Changes', TXTDOMAIN ); ?>" />
                        </div>
                        <br class="clear" />
                    </div>
                </div>
                <?php
                // Load the Sidebar blocks first...
                $this->_DisplayAdministrationPageBlocks( $this->CONTENT_BLOCK_TYPE_SIDEBAR );
                ?>
            </div>
        </div>
        <!-- Plugin Main Content -->
        <div id="post-body">
            <div id="post-body-content" class="has-sidebar-content">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable" style="position: relative;">
                    <?php
                    // Then load the main content blocks...
                    $this->_DisplayAdministrationPageBlocks( $this->CONTENT_BLOCK_TYPE_MAIN );
                    ?>
                </div>
            </div>
        </div>
        <br class="clear" />
    </form>
    <?php
    } else {
        // Update the URL to perform deactivation of the specified plugin.
        $deactivateUrl = 'plugins.php?action=deactivate&amp;plugin=' . $this->_pluginSubfolderName . '/' . $this->_pluginFileName . '.php';
        if( function_exists( 'wp_nonce_url' ) ) {
            $actionName = 'deactivate-plugin_' . $this->_pluginSubfolderName . '/' . $this->_pluginFileName . '.php';
            $deactivateUrl = wp_nonce_url( $deactivateUrl, $actionName );
        }
        // Remind the user to deactivate the plugin.
        $uninstalledMessage = sprintf( __( '<p>All of the %s plugin options have been deleted from the database.</p>', TXTDOMAIN ), $this->_pluginTitle );
        $uninstalledMessage .= sprintf( __( '<p><strong><a href="%1$s">Click here</a></strong> to finish the uninstallation and deactivate the %2$s plugin.</p>', TXTDOMAIN ), $deactivateUrl, $this->_pluginTitle );
        $this->_DisplayFadingMessageBox( $uninstalledMessage, 'error' );
    }
    ?>
</div>