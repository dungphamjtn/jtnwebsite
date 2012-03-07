=== Featurific For Wordpress ===
Contributors: rinogo
Tags: slideshow, slide, show, gallery, flash, xml, dynamic, conversion, funnel, Post, posts, sidebar, images, links, photo, photos, statistics, stats, swf, plugin, admin
Requires at least: 2.3
Tested up to: 3.2.1
Stable tag: 1.6.2

An effortless but powerful interface to Featurific Free, the featured story slideshow.  (Similar to the 'featured'
widget on time.com, msn.com, walmart.com, etc.)



== Description ==

An effortless interface to Featurific Free, the featured story slideshow.

Unlike traditional slideshows, Featurific imitates the behavior seen on the home pages of sites like time.com and msn.com,
displaying summaries of featured articles on the site.  The idea is to increase conversion and user satisfaction by
funneling your readers to your strongest, most engaging content.  If you believe that big budget companies like Time, MSN,
and Walmart might be on to something, then give this plugin a shot.

Installation is automatic and easy, while advanced users can customize every element of the Flash slideshow presentation.

Selected feature list:
* No configuration required (although you can tweak nearly any aspect of the plugin if you so desire)
* User-customizable templates
* Integrates with the Wordpress.com Stats Plugin to select most popular posts
* Customizable options include number of posts to display, post selection type, screen duration, auto-excerpt length, etc.



== Installation ==

Standard Wordpress installation:

1. Extract all files from FeaturificForWordpress.zip.
1. Upload the entire `featurific-for-wordpress` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. If necessary, click on the large "Install Featurific Free" text to auto-install required libraries
1. You're done! :)

For basic configuration:
* Wordpress 2.3: visit the 'Options' menu and access the submenu named 'Featurific'.
* Wordpress 2.4+: visit the 'Settings' menu and access the submenu named 'Featurific'.



== Demo Sites ==
Once you get Featurific for Wordpress installed, let us know by [posting a link to your site](http://featurific.com/support/thread-9.html) on our [support forum](http://featurific.com/support/thread-9.html).  From time to time, we'll choose a few sites from the list to be featured here and on the Featurific.com website.

[The Hoop Doctors](http://thehoopdoctors.com/online/) |
[Endorkins](http://endorkins.com/) |
[Bigg Success](http://biggsuccess.com) |
[Life Magick](http://www.lifemagick.net/) |
[Teknoblog](http://www.teknoblog.com/) |
[The GPS Times](http://thegpstimes.com)





== Testers and Debuggers ==
Muchas gracias to the following individuals for their help with testing and debugging Featurific for Wordress:

Edward Prislac of [Fit to be Fried](http://www.semperfried.com/)

Ian Bethune of [Sox & Dawgs](http://soxanddawgs.com/)

Sax Hammer of [Ultragreek News](http://news.ultragreek.com/)



== Changelog ==


**1.6.2 (10/21/11)**
Added support for checking loop.php file before index.php and home.php (in determining where to insert Featurific) (to support Twenty Ten theme and derivatives/relatives).

**1.6.1 (Re-release) (1/27/10)**
Bugfix in get_home_template_of_theme() for changed $theme['Template Dir'] behavior.  Not releasing as a new version since anyone who is currently using the plugin mananged to get it working without this fix.

**1.6.1 (12/31/09)**
Removed FeaturificFree.swf from the download and added a download helper to facilitate obtaining/installing FeaturificFree.  Also improved the 'code-reuse' of the plugin, rewriting some functions to be less repetitive.

**1.6.0 (6/22/09)**
Added the 'Random' post selection type (sponsored by [TheChive.com](http://www.thechive.com/)).

**1.5.7 (6/15/09)**
Fixed a bug that prevented data.xml files from being generated in high-volume and image-heavy blogs.  Only one image per post is processed now (the first image, available via the '%image_1%' tag).  Before, all images were parsed which caused high server load during data.xml generation, causing blogs on shared hosts or blogs with large amounts of posts to timeout/run out of memory.

**1.5.6 (6/12/09)**
Fixed some bugs in the templates included in the archive.

**1.5.5 (6/12/09)**
Upgrading to reflect (successful) testing with WP 2.8.  Also including an updated version of FeaturificFree.swf.

**1.5.4 (4/6/09)**
Added a call to ini_get('allow_url_fopen') in featurific_get_template_library() to check if file_get_contents() will fail.  If so, null is returned.  This is only a temporary workaround - The better, long-term solution, is to use the WP_Http class in http.php instead of file_get_contents() at all (falling back on file_get_contents() if the WP_Http class isn't available (e.g. on older WP installations)).

**1.5.3 (3/30/09)**
Updated default template to be Time.com (Transparent).

**1.5.2 (3/30/09)**
Fixed some svn template issues.

**1.5.1 (3/26/09)**
Modified ordering of featurific.php's WP metadata comment and the GNU license header comment.  This restores the auto-update plugin functionality.  Also, fixed a bug that prevented the data.xml file from being generated if the template file/dir is inadvertently deleted (manually or via the auto-upgrade process).

**1.5.0 (3/26/09)**
Releasing changes to public.  Tested with WP 2.7.1.

**1.4.8 (3/24/09)**
Added improved template installation experience through 'auto installer',
greatly reducing initial download size.

**1.4.7 (2/9/09)**
Fixed a minor bug that prevented loading of images with spaces in their filenames.  Added some RTL-enabled themes, as well as implemented the related Wordpress to Featurific interface for specifying the RTL option.

**1.4.6 (1/29/09)**
Added RTL (Right To Left text) support.

**1.4.5 (1/21/09)**
Fixed a bug in which '<' and '>' were not escaped correctly during the translation from post data to screen XML.

**1.4.4 (12/30/08)**
Documentation update.

**1.4.4 (12/30/08)**
Releasing changes since 1.3.5 to public.  Tested (with no issues) in WP 2.7.

**1.4.2 (9/3/08)**
Fixed a minor bug with the category restriction's in_array() call ('Warning: in_array() [function.in-array]: Wrong datatype for second argument in...')

**1.4.1 (9/3/08)**
Added a user-configurable option for choosing whether to serve up the cached image files dynamically (via PHP) or statically (via the web server).  Also modified the related administration page slightly.

**1.4.0 (8/28/08)**
Fixed a bug that caused Featurific to behave somewhat erratically when used in conjunction with [gallery] shortcodes.  Featurific should now work fine with the built-in Wordpress gallery shortcode, as well as third-party galery plugins such as NextGen Gallery.  Also added a user-configurable option for choosing whether to serve up the data.xml dynamically (via PHP) or statically (via the web server).

**1.3.6 (8/08)**
Internal version.

**1.3.5 (7/28/08)**
Fixed a bug that caused the plugin to use incorrect URLs in slideshow generation when the blog had been moved from one location (e.g. myblog.com/wordpress) to another location (e.g. myblog.com).  (A method leveraging the_permalink() is now used rather than using $post['guid']).  Also fixed a bug that caused images referenced via a relative URL to not be processed/included correctly in the slideshow.

**1.3.4 (7/24/08)**
Fixed a bug introduced in 1.3.1 that caused the Recent Post selection type to return fewer posts than requested, even if more posts were available.  Also fixed a bug introduced by Wordpress 2.6-ish (get_theme(..)["Template Dir"] no longer contains the string 'wp-content') that caused the automatic insertion algorithm to fail when changing templates.  Finally, modified the HTML used to embed FeaturificFree.swf so that it is valid XHTML 1.0 Transitional.

**1.3.3 (7/22/08)**
Fixed a bug introduced in 1.3.1 that caused funky post selection when using category filtering, as well as a bug in Popular Post selection.

**1.3.2 (7/22/08)**
Fixed a bug introduced in 1.3.1 that prevented images contained within the post from being parsed and included in Featurific.

**1.3.1 (7/22/08)**
Added a call to do_shortcode() to convert shortcodes (e.g. [caption id="" caption="whatever"]) into their resulting HTML (which is then converted to text and displayed in Featurific)

**1.3.0 (7/21/08)**
Added support for category filtering.  Also made some minor improvements in error reporting.

**1.2.9 (7/7/08)**
Added a bunch of text in various places to enhance usability.  Should not effect program execution.

**1.2.8 (7/3/08)**
Added an auto-upgrade warning to prevent users from overwriting changes/additions made to their templates upon auto-upgrading.

**1.2.7 (6/24/08)**
Fixed a bug that generated invalid input XML when HTML was used in a manual excerpt.

**1.2.6 (6/13/08)**
Moved the image cache table name from a global variable to a function because some systems seemed unable to access the global variable.  (Weird...)

**1.2.5 (6/12/08)**
Fixed some path issues that prevented Featurific for Wordpress from working on Windows machines.

**1.2.4 (6/11/08)**
Fixed an issue in which custom fields were not properly overriding original values.  Also fixed an issue in which the characters '&ldquo; &rdquo; &lsquo; &rsquo;' were not displaying correctly.

**1.2.3 (6/10/08)**
Fixed one instance of "&lt;?" in featurific.php that should have been "&lt;?php".  In systems on which PHP was compiled with short_open_tag set to Off, this would cause the PHP block in question to not be executed.  (More information: http://www.daaq.net/old/php/index.php?page=embedding+php&parent=php+basics)

**1.2.2 (6/9/08)**
featurific\_show\_admin\_message\_once($message) implemented (and one call to it added).  This function is to be called when a minor error occurs that we wish to show to the admin user one time only.

**1.2.1 (6/5/08)**
Added plugin version printout to HTML embedding code for debugging.

**1.2 (6/4/08)**
Changelog added.  Unicode Support (UTF-8 encoding) added to FeaturificFree.swf.



== Frequently Asked Questions ==

= I can't get the plugin to install.  Can you help me? =
I'm sorry if the plugin is causing you problems.  It has only recently been released (consider yourself an early
adopter! :) ), so I still need to work through the bugs that weren't manifest in my development environment.  Please
[contact me](http://featurific.com/support) via the [Featurific.com support forum](http://featurific.com/support) and I'll be happy
to help you work through the bugs.



= Why is the plugin asking me to install the additional files in "Featurific Free"? =
The actual Featurific Free file (FeaturificFree.swf) was removed from the Featurific for Wordpress package since its license agreement conflicts with that of Wordpress.org. So, Featurific Free must be installed separately for the plugin to work.

Don't worry - The plugin should help you auto-install Featurific Free. Simply click on "Install Featurific Free" after you've activated the plugin. If the auto-installation fails, follow the directions provided to install Featurific Free manually.

Please [contact me](http://featurific.com/support) via the [Featurific.com support forum](http://featurific.com/support) if you need help installing Featurific Free and I'll be happy to help you out.

(Technical details: The *Featurific for Wordpress plugin* (GPL license) simply generates a data.xml file that can be read by *Featurific Free* (proprietary license). Featurific Free reads this data.xml file and renders the posts/images inside of it on-screen in the visual format that you see on your blog's main page)



= Something is broken, but I don't see any error messages.  How do I find the error messages? =
Wordpress sometimes has a nasty habit of suppressing errors, which is, needless to say, quite unhelpful when trying to
discover why Featurific isn't working.  However, there's a sneaky workaround we can perform to view errors that are
generated upon plugin activation.

Here are the steps:

1. Change the *Featurific* template to one that does not correctly display Featurific.  This causes the data\*.xml file to
be regenerated, but errors are unfortunately suppressed.

1. With the nonfunctional template still selected, change your *Wordpress* theme to something other than your current
theme.  This causes the data\*.xml file to be regenerated, but this time, errors are reported.

1. Finally, after you have viewed the errors (or verified that no errors are generated), you may of course revert
your Featurific template and Wordpress theme to their original state.



= Featurific works, but none of the images load.  Help! =
In most cases, this issue is related to hotlink prevention measures taken by a web host.  To make a long story short,
Flash (Actionscript 3.0) doesn't send the HTTP-REFERER HTTP header, which causes some web servers to erroneously conclude
that Featurific is attempting to [hot-link](http://en.wikipedia.org/wiki/Inline_linking) an image.  So, these servers
block Featurific's image requests and the images do not appear.  To fix this issue, try disabling 'hot link' or 'leech'
protection in your CPanel or PLESK control panels.  If that does not work, try contacting your web host and explain that
you need hotlink protection disabled.



= I can't get the "User-Defined Posts" feature to work.  Any ideas? =
**Use the correct format**
The format for this field is a comma-separated list of post id's, such as '5, 14, 8, 23' (omit the quotes).

**Ensure that the posts actually exist**
When using the "User-Defined Posts" feature, posts won't appear if they are non-existent.  You can check to see
if the posts exist by accessing them in your web browser via the following URL:

http://&lt;location of your blog&gt;/?p=&lt;insert post id here&gt;

So, if your blog were located at mysuperblog.com/blog, and if you wanted to check to see if post 54 existed,
you would access:

http://mysuperblog.com/blog/?p=54

If you can't access the post via this URL, then you're trying to use an invalid post id.



= Where can I find more templates? =
[The Featurific website](http://featurific.com/ffw) ([http://featurific.com/ffw](http://featurific.com/ffw)).  No
extra templates have been released yet - please let me know if you'd like your template to be the first featured
template!



= What are the system requirements for using Featurific? =
Featurific has been successfully tested with Wordpress 2.3 to 2.7 on PHP4 and PHP5.  If you have problems on these
(or other) configurations, feel free to [contact me](http://featurific.com/support) via the [Featurific.com support forum](http://featurific.com/support).  Support on Wordpress
2.3 seems to be limited, I'll post more information as it becomes available.



= How do I move Featurific to another location on my page? (aka How do I manually install Featurific?) =

Many Featurific templates look better in a sidebar than in the main content area.  To move Featurific to the side bar or
any other location, just edit your theme.  Featurific for Wordpress automatically inserts itself into your index.php or
home.php theme file (whichever it detects is present).  Open up the file and look for the following code:

`<?php
//Code automatically inserted by Featurific for Wordpress plugin
if(is_home())                             //If we're generating the home page (remove this line to make Featurific appear on all pages)...
 if(function_exists('insert_featurific')) //If the Featurific plugin is activated...
  insert_featurific();                    //Insert the HTML code to embed Featurific
?>`

Move this code to wherever you'd like Featurific to appear (in any of your theme files).



= How do I include an image in Featurific without also including it in the Wordpress post itself? =
There are two solutions to this requirement:

1. *Sneaky HTML/CSS*: In your Wordpress posts, embed the images like you normally embed images, but add some CSS to hide
them when the post is displayed in Wordpress.  For example, you could add 'style="display: none"' to the <img> tags.
The images won't appear in Wordpress, but Featurific will still detect them and display them (since it does not process
CSS).

1. *Custom Fields*: Add a custom field to your posts with the url of the image in it.  For example, you could use a custom
field of 'image_1' which would cause the image to show up in the default location for images in most galleries.  (image_1
is a tag used within most Featurific templates that is replaced with the first detected image from the Wordpress post.
Likewise, image_2 is the second detected image, image_3 is the third, etc.)  If you want even more control, you could
use a custom field and tag with your own name, such as 'my_image'.  Then, you need to edit the template.xml file for
your template, adding in the 'my_image' tag where you want the image to appear.



= How do I make Featurific appear on pages other than the main page? =

**Theme file**
If you've got a *theme file that corresponds to the pages on which you want Featurific to appear*, just follow the
instructions under the FAQ entry, "How do I move Featurific to another location on my page?".  Move the Featurific code to
the desired location in the appropriate theme file.

**All pages**
If you want Featurific to appear on *all* pages and not just on the home page, find the following code in your theme's
index.php or home.php file:

`<?php
//Code automatically inserted by Featurific for Wordpress plugin
if(is_home())                             //If we're generating the home page (remove this line to make Featurific appear on all pages)...
 if(function_exists('insert_featurific')) //If the Featurific plugin is activated...
  insert_featurific();                    //Insert the HTML code to embed Featurific
?>`

Comment out the line that begins with `if(is_home())` by inserting '//' at the beginning of the line as follows:

`<?php
//Code automatically inserted by Featurific for Wordpress plugin
 //if(is_home())                             //If we're generating the home page (remove this line to make Featurific appear on all pages)...
 if(function_exists('insert_featurific')) //If the Featurific plugin is activated...
  insert_featurific();                    //Insert the HTML code to embed Featurific
?>`

**Specific pages**
If you want Featurific to appear on specific pages, you could try using `$_SERVER['REQUEST_URI'])` and either an if
statement or the `preg()`/`pregi()` functions (regular expressions).


= How do I use Featurific on a static page? / I activated Featurific, but nothing happened - what's up? =
Featurific can only insert itself into your template if you are using the traditional format of a Wordpress blog -
that is, a main page generated by Wordpress with a loop showing the most recent x posts.  If you are using a static
main page or have heavily customized your Wordpress theme, you will likely have to manually insert Featurific into your site's
code.

**Customized Theme**
If you have customized your theme, read the following sections of this FAQ for information on inserting Featurific:
* "How do I move Featurific to another location on my page?"
* "How do I make Featurific appear on pages other than the main page?"

**Static Page**
If the page you'd like Featurific to appear on is a static page, there's a bit more work involved.  This process will
likely be simplified in the future, but for the time being, the following log (provided by
[Shireen Jeejeebhoy](http://jeejeebhoy.ca)) will help you get Featurific working on a static page.

> Hi Rich,
> 
> I finally got a chance to download Exec-PHP and try it out with this code copied into my front static page:
> 
> <?php
>       if(function_exists('insert_featurific')) //If the Featurific plugin is activated...
>        insert_featurific();                    //Insert the HTML code to embed Featurific
> ?>
> 
> I discovered how true the following warning from Exec-PHP is:
> 
> "To successfully write PHP code in the content of an article, the WYSIWYG editor needs to be turned off through the ‘Users > Your Profile’ menu. It is not enough to simply keep the WYSIWYG editor on, switch to the ‘Code’ tab of the editor in the ‘Write’ menu and save the article. This will render all contained PHP code permanently unuseful." (http://bluesome.net/post/2005/08/18/50/#execute_php)
> 
> So I tried their suggestion of using another plugin that deactivates the visual editor just for the page I'm putting the (Featurific) php code on. It worked! (I don't like writing in the Code page and much prefer using the WYSIWYG editor, but for those who don't like the WYSIWYG editor, this would not be an issue.) The plugin says it's good up to 2.3.3, but I'm using 2.5.1 and it's working as far as I can see:
> 
> http://wordpress.org/extend/plugins/deactive-visual-editor/
> 
> I think there was another plugin I used a long time ago that required Exec-PHP but then the author got around it somehow and no longer required the use of this plugin. It would be great if in the future, you were able to develop the code to no longer require these two additional plugins. But for now it works. I've also left the code in the index.php so Featurific appears on my main page and my blog page. Pretty cool!


= Featurific for Wordpress works fine in browser X but I can't get it to work right in browser Y.  What's up? =
Featurific has been extensively tested to be compatible with a long list of web browsers.  Sometimes, on a given computer, it will work fine in one browser (e.g. Firefox), but not in another browser (e.g. Internet Explorer).  In most cases, the problem is with the Flash player and not the browser itself.  It is possible to have different versions of Flash installed in different browsers, even on the same computer.
To verify that you're using Flash 9, [visit the Flash Version Test Page](http://kb.adobe.com/selfservice/viewContent.do?externalId=tn_15507) in the browser that is causing problems.  If you are not using Flash 9 in the problem browser (e.g. Internet Explorer in our example), [upgrade to the most recent Flash Player](http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=shockwaveFlash), and Featurific should work fine.


= How do I modify the post excerpt, post time, images, or any other fields for a given post? =
When generating the data\*.xml file, Featurific for Wordpress automatically replaces tags from the template (e.g. %post_excerpt%) with the corresponding value from the post. However, sometimes the automatically generated value is not what is desired. For example, if your post has an image with a caption at the beginning of the post, the caption will likely be included in the %post_excerpt%. Also, the first-appearing image in the post will be parsed and used as %image_1% in the Featurific template. If the automatically generated %post_excerpt%, %image_1%, or any other tag is not populated as you desire, you can use custom fields to override the default values. If, for example, you wish to provide a manually created post excerpt, simply create a custom field with the name of the tag ('post_excerpt' (note that the '%' marks are not used here)). Enter your custom post excerpt as the value for this field. When the data\*.xml is generated, your manual post_excerpt field overrides the automatically generated value, and the resulting slideshow displays as desired. (Note that you can also use custom fields to define a custom tag, (e.g. my_tag (no '%' marks)). The value of the custom field will then be substituted into your template.xml file wherever the tag (e.g. %my_tag% (with '%' marks)) is present.)


= I edited my template.xml file, but nothing happened.  What did I do wrong? =
Featurific for Wordpress generates the data\*.xml file every x minutes (e.g. 10 minutes by default).  The next time the data\*.xml file is generated, your changes will be visible.  However, if you wish to see your changes more immediately, access the Featurific Settings page (Settings->Featurific), and hit the "Update Options" button at the bottom without changing any of your settings.  Doing so forces a regeneration of the data\*.xml file, so you can see the result of your modifications immediately.


= I have purchased Featurific Pro.  How do I upgrade the plugin without reverting back to Featurific Free? =
Wordpress' plugin auto-updater is great, but it could still use a bit of work.  When it auto-upgrades a plugin, it simply deletes the old plugin's directory reather than trying to merge the old and new directories into one.  This causes problems when we want to keep some of our old files and upgrade the rest.  In order to retain Featurific Pro through the upgrade, first copy Featurific Pro (FeaturificFree.swf in the featurific-for-wordpress directory) to a safe location that is either outside of the plugin directory or on your local system.  Next, auto-upgrade (or manually upgrade) the plugin.  Finally, restore your version of Featurific Pro (FeaturificFree.swf) to the featurific-for-wordpress directory.  At this point, the upgrade is complete and Featurific Pro should be installed.


= I hate the logo that appears in the corner all the time.  How can I get rid of it? =
To get rid of the "Powered by Featurific" logo, you're going to have to splurge for the commercial version of Featurific.
[Visit the Featurific website](http://featurific.com) for details ([http://featurific.com](http://featurific.com)).


= I can't get this thing to work!  What's wrong? =
[Drop me a line](http://featurific.com/support) on the [Featurific.com support forum](http://featurific.com/support) and I'll try to help you out.



== Screenshots ==

1. The options page for the Featurific for Wordpress plugin.
2. An example of the plugin in action.
3. An example of the plugin in action.
4. An example of the plugin in action.
