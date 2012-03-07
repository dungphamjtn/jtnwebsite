<?php
/*
Plugin Name: Featurific for Wordpress
Plugin URI: http://featurific.com/ffw
Description: This plugin provides an effortless interface to Featurific, the
featured story slideshow.  Unlike traditional slideshows, Featurific imitates
the behavior seen on the home pages of sites like time.com and msn.com,
displaying summaries of featured articles on the site.  Installation is
automatic and easy, while advanced users can customize every element of the
Flash slideshow presentation.
Author: Rich Christiansen
Version: 1.6.2
Author URI: http://endorkins.com/
*/

/*
  This file is part of Featurific For Wordpress.

  Copyright 2008  Rich Christiansen  (rich at <please don't spam me> byu period net)

  Featurific For Wordpress is free software: you can redistribute it and/or modify
  it under the terms of the GNU Lesser General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Featurific For Wordpress is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Lesser General Public License for more details.

  You should have received a copy of the GNU Lesser General Public License
  along with Featurific For Wordpress.  If not, see <http://www.gnu.org/licenses/>.

	Featurific (Free, Pro, etc) is not released under the GNU Lesser General Public
	License.  It is released under the license contained in license.txt.  For details
	on licensing of Featurific (Free, Pro, etc), please contact Breeze Computer
	Consulting at support@featurific.com.
*/

//Constants
define('FEATURIFIC_VERSION', '1.6.1');
define('FEATURIFIC_MAX_INT', defined('PHP_INT_MAX') ? PHP_INT_MAX : 32767);
define('FEATURIFIC_STORE_UNDEFINED', false);
define('FEATURIFIC_STORE_IN_DB', 1);
define('FEATURIFIC_STORE_ON_FILESYSTEM', 2);
define('FEATURIFIC_SWF_URL', 'http://featurific.com/autoInstall/09_03_26/FeaturificFree.swf');
define('FEATURIFIC_SWF_MANUAL_URL', 'http://featurific.com/autoInstall/09_03_26/manual/download.php');
define('FEATURIFIC_TEMPLATES_URL', 'http://featurific.com/files/templates');
define('FEATURIFIC_TEMPLATES_LIBRARY_FILENAME', 'library.xml');

//Libraries
include_once('featurific_db.php');

if(class_exists("HtmlParser")===false)
	include ("htmlparser.inc");

if(class_exists("XMLParser")===false) {
	if(version_compare(PHP_VERSION, '5.0', '<'))
		include 'parser_php4.phps';
	else
		include 'parser_php5.phps';
}


//Hooks
register_activation_hook( __FILE__, 'featurific_activate' );
register_deactivation_hook( __FILE__, 'featurific_deactivate' );


//Actions
add_action('switch_theme', 'featurific_activate');
add_action('admin_menu', 'featurific_add_pages');
add_action('admin_notices', 'featurific_show_admin_messages');
add_action('after_plugin_row', 'featurific_show_upgrade_notice');



if(!get_option('featurific_current_template_configured')) {
	function featurific_template_configuration_warning() {
		$theme_editor_url = get_option('siteurl').'/wp-admin/theme-editor.php';
		echo "
			<div id='featurific-for-wordpress-warning' class='updated fade'><p><strong>Featurific for Wordpress is almost ready</strong>.  To complete installation, Featurific for Wordpress needs access to your main template file.<br/>
				<ul>
					<li>
						<strong>Temporarily modify your theme's file permissions and reinstall</strong>
						<ol>
							<li>
								Change the file permissions on your current theme's files to be world-writable (chmod 666 should suffice)
								<ul>
									<li><a href='http://codex.wordpress.org/Changing_File_Permissions#Using_an_FTP_Client'>Using an FTP client (easy)</a></li>
									<li><a href='http://codex.wordpress.org/Changing_File_Permissions#Using_the_Command_Line'>Using the command line (harder, requires shell access)</a></li>
								</ul>
							</li>
							<li>Deactivate and re-activate Featurific for Wordpress, and installation will complete successfully if the main template file is in fact world-writable.</li>
							<li><strong>Optional</strong>: If desired, revert your permissions back to normal. (chmod 644 for files, chmod 755 for directories)</li>
						</ol>
					</li>
					<li>
						<strong>Manually insert the Featurific code</strong>
						Instead of modifying your permissions so that Featurific can install itself into your theme automatically, you have the option of installing Featurific manually.  For instructions on how to do so, <a href='http://featurific.com/ffw#movelocation'>visit the Featurific website</a>.
					</li>
				</ul>
				<small>To get rid of this status message, either complete the steps listed above or disable the Featurific for Wordpress plugin.</small>
			</p></div>";

// 		echo "
// 			<div id='featurific-for-wordpress-warning' class='updated fade'><p><strong>Featurific for Wordpress is almost ready</strong>.  To complete installation, you must do <strong>one of the following</strong>:<br/>
// 				<ul>
// 					<li>
// 						<strong>Easy</strong> - Manually insert Featurific into your theme
// 						<ol>
// 							<li>Edit the index.php or home.php file of your theme in the <a href='$theme_editor_url'>Wordpress Theme Editor</a> (<a href='http://codex.wordpress.org/Editing_Files#Using_the_Theme_Editor'>More info on the Theme Editor</a>)</li>
// 							<li>
// 								Insert the Featurific for Wordpress code.<br/>
// <pre>&lt;?php
//  //Code automatically inserted by Featurific for Wordpress plugin
//  if(is_home())                             //If we're generating the home page (remove this line to make Featurific appear on all pages)...
//   if(function_exists('insert_featurific')) //If the Featurific plugin is activated...
//    insert_featurific();                    //Insert the HTML code to embed Featurific
// ?&gt;</pre>
// 								The code needs to go in a specific location.  Here's how to find where to put it:
// 								<ol type='a'>
// 									<li>Find the first occurrence of the text 'have_posts()' in the template.</li>
// 									<li>Find the first occurrence of '&lt;?' that <em>precedes</em> the text found in step 1.</li>
// 									<li>Insert the code <em>just before</em> the '&lt;?' found in step 2.</li>
// 								</ol>
// 							</li>
// 						</ol>
// 					</li>
// 					<li>
// 						<strong>Harder</strong> - Temporarily modify your theme's file permissions and reinstall
// 						<ol>
// 							<li><a href='http://www.google.com/search?q=chmod+777'>Change the file permissions</a> on your current theme files (the containing directory and all files) to be world-writable (chmod 777 should suffice)</li>
// 							<li>Disable and re-enable Featurific for Wordpress, and installation will complete successfully if the theme is in fact world-writable.</li>
// 							<li>Revert your permissions back to normal. (chmod 644)</li>
// 						</ol>
// 					</li>
// 				</ul>
// 			</p></div>";


	}
	add_action('admin_notices', 'featurific_template_configuration_warning');
	//return;
}


/**
 * Every time a page is loaded in the admin area (but not on the FeaturificFree.swf auto install page), check if FeaturificFree.swf is present.
 * If it is not, display the warning (with instructions of how to obtain FeaturificFree.swf)
 */
if(strpos($_SERVER['REQUEST_URI'], '/wp-admin/')!==false && strpos($_SERVER['REQUEST_URI'], '&action=installFeaturific')===false) {
	featurific_test_featurific_swf_present();
	if(!get_option('featurific_swf_installed')) {
		function featurific_install_swf_warning() {
			// $action_url = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
			$install_url = featurific_get_wordpress_web_root().'wp-admin/options-general.php?page=featurificoptions&action=installFeaturific';
			echo "
				<div id='featurific-for-wordpress-warning' class='updated fade'><p><strong>Featurific for Wordpress is almost ready</strong>.  To complete installation, we need to download and install the <strong>latest version of Featurific Free</strong>. Featurific Free is the (free) software that is used to display posts on your main page. (<a href='http://featurific.com/ffw#additionalfiles'>More info</a>)<br/>
				
					<div style='float:left; padding:30px'>
						<a href='$install_url' style='margin-top:200px'><font size='+2'><strong>Auto-Install Featurific Free</strong></font></a>
					</div>
					
					<div style='float:left'>
						<center>
							By clicking 'Auto-Install Featurific Free', you<br/>accept the following Terms &amp; Conditions:<br/>
							<textarea rows='3' cols='50' name='' id=''  readonly='' class='form-textarea resizable' style='font-size:0.75em'>IMPORTANT:   THIS AGREEMENT (or &quot;EULA&quot;) IS A LEGAL AGREEMENT BETWEEN THE PERSON, COMPANY, OR ORGANIZATION THAT HAS LICENSED THIS SOFTWARE (&quot;YOU&quot; OR &quot;CUSTOMER&quot;) AND BREEZE COMPUTER CONSULTING, LLC (HEREAFTER SIMPLY &quot;BCC&quot;).   BY INSTALLING AND USING THE SOFTWARE, CUSTOMER ACCEPTS THE SOFTWARE AND AGREES TO THE TERMS OF THIS AGREEMENT.     READ IT CAREFULLY BEFORE COMPLETING THE INSTALLATION PROCESS AND USING THE SOFTWARE.   BY INSTALLING AND/OR USING THE SOFTWARE, YOU ARE CONFIRMING YOUR ACCEPTANCE OF THE SOFTWARE AND AGREEING TO BECOME BOUND BY THE TERMS OF THIS AGREEMENT.   IF YOU DO NOT AGREE TO BE BOUND BY THESE TERMS, OR DO NOT HAVE AUTHORITY TO BIND CUSTOMER TO THESE TERMS, THEN DO NOT INSTALL AND/OR USE THE SOFTWARE AND RETURN THE SOFTWARE TO YOUR PLACE OF PURCHASE FOR A FULL REFUND IN ACCORDANCE WITH ITS REFUND POLICIES.
	THIS EULA SHALL APPLY ONLY TO THE SOFTWARE SUPPLIED BY BCC HEREWITH REGARDLESS OF WHETHER OTHER SOFTWARE IS REFERRED TO OR DESCRIBED HEREIN.
	1. License Grants
	The licenses granted in this Section 1 are subject to the terms and conditions set forth in this EULA:
	(a)   You may install and use the Software on any number of pages on a single website.  Every page containing the Software must reside on the same domain and, if applicable, within the same path to the the customer&#039;s home directory.  A license for the Software may not be shared, installed or used concurrently on different websites.   A license for the Software may not be accessed and used via a server or network storage device - the Software must be installed and used locally on the server and website.  To use the Software on multiple websites, you must purchase (except in the case of the FREE version, which is provided free of charge) and download a new copy of the Software for each website.
	(b)   You may make one copy of the Software in machine-readable form solely for backup purposes. You must reproduce on any such copy all copyright notices and any other proprietary legends on the original copy of the Software.   You may not sell or transfer any copy of the Software made for backup purposes.    
	(c)   You agree that BCC may audit your use of the Software for compliance with these terms at any time, upon reasonable notice. In the event that such audit reveals any use of the Software by you other than in full compliance with the terms of this Agreement, you shall reimburse BCC for all reasonable expenses related to such audit in addition to any other liabilities you may incur as a result of such non-compliance.
	(d)   Unless otherwise set forth in the documentation relating to such code and/or the Software or in a separate agreement between you and BCC, you may modify the source code form of those portions of such software programs that are identified as sample code, sample application code, or components (each, &quot;Sample Application Code&quot;) in the accompanying documentation solely for the purposes of designing, developing and testing   websites and website applications developed using BCC software programs; provided, however, you are permitted to copy and distribute the Sample Application Code (modified or unmodified) only if all of the following conditions are met: (1) you distribute the compiled object Sample Application Code with your application; (2) you do not include the Sample Application Code in any product or application designed for website development; and (3) you do not use BCC&#039;s name, logos or other BCC trademarks to market your application.   You agree to indemnify, hold harmless and defend BCC from and against any loss, damage, claims or lawsuits, including attorney&#039;s fees, that arise or result from the use or distribution of your application.
	(g)    Your license rights under this EULA are non-exclusive.   
	(h)    You agree to grant BCC the right to actively track usage of the Software, which may involve the transmission of data via the Software to BCC.  Perceived piracy may result in an audit in accordance with Section 1(c).
	(i)    For the Enterprise version of the Software, you may modify the provided source code and re-compile the Software, provided that the following conditions are met: (1) you do not redistribute the original or modified source code, (2) the source code retains all original trademarks, copyrights, and notices, (3) you adhere to the stipulations set forth in this agreement (including, but not limited to, the grant to use the Software on one website only).
	2. License Restrictions
	(a)   Other than as set forth in Section 1, you may not make or distribute copies of the Software, or electronically transfer the Software from one computer to another or over a network.
	(b)   Other than as set forth in Section 1, you may not alter, merge, modify, adapt or translate the Software, or decompile, reverse engineer, disassemble, or otherwise reduce the Software to a human-perceivable form.
	(c)   Unless otherwise provided herein, you may not sell, rent, lease, or sublicense the Software.
	(d)   Unless otherwise provided herein, you may not modify the Software or create derivative works based upon the Software.
	(e)    You may not export the Software into any country prohibited by the United States Export Administration Act and the regulations thereunder.
	(f)     You may receive the Software in more than one medium but you shall only install or use one medium.   Regardless of the number of media you receive, you may use only the medium that is appropriate for the server or computer on which the Software is to be installed.
	(g)    You may receive the Software in more than one platform but you shall only install or use one platform.
	(h)    You shall not use the Software to develop any product having the same primary function as the Software.
	(i)    In the case of the Enterprise version of the Software, the source code is provided (according to Section 1(i)).  Since the source code is ultimately what controls how the product works, you agree to indemnify, hold harmless and defend BCC from and against any loss, damage, claims or lawsuits, including attorney&#039;s fees, that arise or result from the use or distribution of your application.  Any warrantees set forth by this agreement shall apply only to unmodified portions of the source code that have not been programmatically or declaratively affected by your source code modifications or the environment which contains the Software.
	(j)    In the event that you fail to comply with this EULA, BCC may terminate the license and you must destroy all copies of the Software (with all other rights of both parties and all other provisions of this EULA surviving any such termination).
	3. Ownership
	The foregoing license gives you limited license to use the Software. BCC and its suppliers retain all right, title and interest, including all copyright and intellectual property rights, in and to, the Software (as an independent work and as an underlying work serving as a basis for any application you may develop),   and all copies thereof. All rights not specifically granted in this EULA, including Federal and International Copyrights, are reserved by BCC and its suppliers.
	4. LIMITED WARRANTY AND DISCLAIMER
	(a)    Except with respect to any Sample Application Code and the FREE Version of the Software, BCC warrants that, for a period of ninety (90) days from the date of delivery (as evidenced by a copy of your receipt): (i) when used with a recommended hardware configuration, the Software will perform in substantial conformance with the documentation supplied with the Software; and (ii) the physical media on which the Software is furnished will be free from defects in materials and workmanship under normal use.   
	(b)    BCC PROVIDES NO REMEDIES OR WARRANTIES, WHETHER EXPRESS OR IMPLIED, FOR ANY SAMPLE APPLICATION CODE AND THE FREE VERSION OF THE SOFTWARE.   ANY SAMPLE APPLICATION CODE AND THE FREE VERSION OF THE SOFTWARE ARE PROVIDED &quot;AS IS&quot;.
	(c)    EXCEPT AS SET FORTH IN THE FOREGOING LIMITED WARRANTY WITH RESPECT TO SOFTWARE OTHER THAN ANY SAMPLE APPLICATION CODE AND FREE VERSION, BCC AND ITS SUPPLIERS DISCLAIM ALL OTHER WARRANTIES AND REPRESENTATIONS, WHETHER EXPRESS, IMPLIED, OR OTHERWISE, INCLUDING THE WARRANTIES OF MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE. ALSO, THERE IS NO WARRANTY OF NON-INFRINGEMENT AND TITLE OR QUIET ENJOYMENT. BCC DOES NOT WARRANT THAT THE SOFTWARE IS ERROR-FREE OR WILL OPERATE WITHOUT INTERRUPTION. NO RIGHTS OR REMEDIES REFERRED TO IN ARTICLE 2A OF THE UCC WILL BE CONFERRED ON YOU UNLESS EXPRESSLY GRANTED HEREIN. THE SOFTWARE IS NOT DESIGNED, INTENDED OR LICENSED FOR USE IN HAZARDOUS ENVIRONMENTS REQUIRING FAIL-SAFE CONTROLS, INCLUDING WITHOUT LIMITATION, THE DESIGN, CONSTRUCTION, MAINTENANCE OR OPERATION OF NUCLEAR FACILITIES, AIRCRAFT NAVIGATION OR COMMUNICATION SYSTEMS, AIR TRAFFIC CONTROL, AND LIFE SUPPORT OR WEAPONS SYSTEMS. BCC SPECIFICALLY DISCLAIMS ANY EXPRESS OR IMPLIED WARRANTY OF FITNESS FOR SUCH PURPOSES.
	(d)    IF APPLICABLE LAW REQUIRES ANY WARRANTIES WITH RESPECT TO THE SOFTWARE, ALL SUCH WARRANTIES ARE LIMITED IN DURATION TO NINETY (90) DAYS FROM THE DATE OF DELIVERY.
	(e)   NO ORAL OR WRITTEN INFORMATION OR ADVICE GIVEN BY BCC, ITS DEALERS, DISTRIBUTORS, AGENTS OR EMPLOYEES SHALL CREATE A WARRANTY OR IN ANY WAY INCREASE THE SCOPE OF ANY WARRANTY PROVIDED HEREIN.  
	(f)     ( USA only) SOME STATES DO NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES, SO THE ABOVE EXCLUSION MAY NOT APPLY TO YOU. THIS WARRANTY GIVES YOU SPECIFIC LEGAL RIGHTS AND YOU MAY ALSO HAVE OTHER LEGAL RIGHTS THAT VARY FROM STATE TO STATE.
	5. Exclusive Remedy
	Your exclusive remedy under the preceding is to return the Software to the place you acquired it, with a copy of your receipt and a description of the problem.   Provided that any non-compliance with the above warranty is reported in writing to BCC no more than ninety (90) days following delivery to you, BCC will use reasonable commercial efforts to supply you with a replacement copy of the Software that substantially conforms to the documentation, provide a replacement for defective media, or refund to you your purchase price for the Software, at its option. BCC shall have no responsibility if the Software has been altered in any way (except as set forth in Section 2(i)), if the media has been damaged by misuse, accident, abuse, modification or misapplication, or if the failure arises out of use of the Software with other than a recommended hardware configuration.   Any such misuse, accident, abuse, modification or misapplication of the Software will void the warranty above.   THIS REMEDY IS THE SOLE AND EXCLUSIVE REMEDY AVAILABLE TO YOU FOR BREACH OF EXPRESS OR IMPLIED WARRANTIES WITH RESPECT TO THE SOFTWARE AND RELATED DOCUMENTATION.
	6. LIMITATION OF LIABILITY
	(a)    NEITHER BCC NOR ITS SUPPLIERS SHALL BE LIABLE TO YOU OR ANY THIRD PARTY FOR ANY INDIRECT, SPECIAL, INCIDENTAL, PUNITIVE, COVER OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, DAMAGES FOR THE INABILITY TO USE EQUIPMENT OR ACCESS DATA, LOSS OF BUSINESS, LOSS OF PROFITS, BUSINESS INTERRUPTION OR THE LIKE), ARISING OUT OF THE USE OF, OR INABILITY TO USE, THE SOFTWARE AND BASED ON ANY THEORY OF LIABILITY INCLUDING BREACH OF CONTRACT, BREACH OF WARRANTY, TORT (INCLUDING NEGLIGENCE), PRODUCT LIABILITY OR OTHERWISE, EVEN IF BCC OR ITS REPRESENTATIVES HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES AND EVEN IF A REMEDY SET FORTH HEREIN IS FOUND TO HAVE FAILED OF ITS ESSENTIAL PURPOSE.
	(b)    BCC&#039;S TOTAL LIABILITY TO YOU FOR ACTUAL DAMAGES FOR ANY CAUSE WHATSOEVER WILL BE LIMITED TO THE GREATER OF $500 OR THE AMOUNT PAID BY YOU FOR THE SOFTWARE THAT CAUSED SUCH DAMAGE.
	(c)    (USA only) SOME STATES DO NOT ALLOW THE LIMITATION OR EXCLUSION OF LIABILITY FOR INCIDENTAL OR CONSEQUENTIAL DAMAGES, SO THE ABOVE LIMITATION OR EXCLUSION MAY NOT APPLY TO YOU AND YOU MAY ALSO HAVE OTHER LEGAL RIGHTS THAT VARY FROM STATE TO STATE.
	(d)    THE FOREGOING LIMITATIONS ON LIABILITY ARE INTENDED TO APPLY TO THE WARRANTIES AND DISCLAIMERS ABOVE AND ALL OTHER ASPECTS OF THIS EULA.
	7. Basis of Bargain
	The Limited Warranty and Disclaimer, Exclusive Remedies and Limited Liability set forth above are fundamental elements of the basis of the agreement between BCC and you. BCC would not be able to provide the Software on an economic basis without such limitations.   Such Limited Warranty and Disclaimer, Exclusive Remedies and Limited Liability inure to the benefit of BCC&#039;s licensors.
	8. U.S. GOVERNMENT RESTRICTED RIGHTS LEGEND
	This Software and the documentation are provided with &quot;RESTRICTED RIGHTS&quot; applicable to private and public licenses alike.   Without limiting the foregoing, use, duplication, or disclosure by the U.S. Government is subject to restrictions as set forth in this EULA and as provided in DFARS 227.7202-1(a) and 227.7202-3(a) (1995), DFARS 252.227-7013 (c)(1)(ii)(OCT 1988), FAR 12.212(a)(1995), FAR 52.227-19, or FAR 52.227-14, as applicable.   Manufacturer: Breeze Computer Consulting, LLC, 2227 S 2200 E, SLC, UT 84109.
	9. (Outside of the USA ) Consumer End Users Only
	The limitations or exclusions of warranties and liability contained in this EULA do not affect or prejudice the statutory rights of a consumer, i.e., a person acquiring goods otherwise than in the course of a business.
	The limitations or exclusions of warranties, remedies or liability contained in this EULA shall apply to you only to the extent such limitations or exclusions are permitted under the laws of the jurisdiction where you are located.  
	11. General
	BCC reserves the right to modify this agreement at any time.  Continued use of the Software requires acceptance of the most recent version of the agreement, accessible at Featurific.com, or by contacting BCC at: Breeze Computer Consulting, LLC, 2227 S 2200 E, SLC, UT 84109.
	This EULA shall be governed by the internal laws of the State of Utah , without giving effect to principles of conflict of laws.   You hereby consent to the exclusive jurisdiction and venue of the state courts sitting in Utah County , Utah or the federal courts in Utah to resolve any disputes arising under this EULA.   In each case this EULA shall be construed and enforced without regard to the United Nations Convention on the International Sale of Goods.
	This EULA contains the complete agreement between the parties with respect to the subject matter hereof, and supersedes all prior or contemporaneous agreements or understandings, whether oral or written. You agree that any varying or additional terms contained in any purchase order or other written notification or document issued by you in relation to the Software licensed hereunder shall be of no effect. The failure or delay of BCC to exercise any of its rights under this EULA or upon any breach of this EULA shall not be deemed a waiver of those rights or of the breach.
	No BCC dealer, agent or employee is authorized to make any amendment to this EULA unless such amendment is in writing and signed by a duly authorized representative of BCC.  
	If any provision of this EULA shall be held by a court of competent jurisdiction to be contrary to law, that provision will be enforced to the maximum extent permissible, and the remaining provisions of this EULA will remain in full force and effect.
	All questions concerning this EULA shall be directed to: Breeze Computer Consulting, LLC, 2227 S 2200 E, SLC, UT 84109.
	Breeze Computer Consulting, LLC and other trademarks contained in the Software are trademarks or registered trademarks of Breeze Computer Consulting, LLC in the United States and/or other countries. Third party trademarks, trade names, product names and logos may be the trademarks or registered trademarks of their respective owners.   You may not remove or alter any trademark, trade names, product names, logo, copyright or other proprietary notices, legends, symbols or labels in the Software.   This EULA does not authorize you to use BCC&#039;s or its licensors&#039; names or any of their respective trademarks.
							</textarea>
						</center>
					</div>
					<div style='clear:both'></div>
				
					</p>
				</div>";
		}
		add_action('admin_notices', 'featurific_install_swf_warning');
		//return;
	}
}






function featurific_show_admin_message_once($m) {
	$messages = get_option('featurific_admin_messages_to_show_once');
	
	//Only add the message $m if it's not already in $messages.
	if(array_search($m, $messages)===false) {
		if($messages==null)
			$messages = array();
		
		$messages[] = $m;
		update_option('featurific_admin_messages_to_show_once', $messages);
	}
}


function featurific_show_admin_messages() {
	$messages = get_option('featurific_admin_messages_to_show_once');
	
	if($messages==null || sizeof($messages)<1)
		return;
	
	foreach ($messages as $m) {
		echo "
			<div id='featurific-for-wordpress-warning' class='updated fade'><p><strong>Featurific for Wordpress needs your attention</strong>.<br/>\n
			<br/>\n
			$m\n
			</div>\n";
	}
	
	update_option('featurific_admin_messages_to_show_once', array());
}


function featurific_show_upgrade_notice($plugin_path) {
	//The next few lines were copied and modified from update.php's wp_plugin_update_row()
	$current = get_option('update_plugins');
	if(!isset($current->response[$plugin_path]) ||									//If an update for the plugin is not available
			$plugin_path!='featurific-for-wordpress/featurific.php') {	//or we currently processing a plugin other than the Featurific for Wordpress plugin...			//TODO: Don't hard-code this path, generate it dynamically (there's no guarantee that the files will have these names - the user might move the plugin to another directory, for example, and this test would (inappropriately) fail.)
		return;
	}
	
	//$upgrade_url = wp_nonce_url("update.php?action=upgrade-plugin&plugin=$plugin_path", 'upgrade-plugin_' . $plugin_path); //auto-upgrade url

	echo "
	<tr>
		<td colspan='5' class='plugin-update'>
			<h3>New Version Available - Important Upgrade Notice</h3>
			<div align='left'>
				Auto-upgrading Featurific for Wordpress to the most recent version works flawlessly.  However, <strong>any changes
				to your template files will be lost</strong> if you have:
				<ul>
					<li>Modified existing templates</li>
					<li>Created new templates</li>
					<li>Installed new templates</li>
				</ul>
				Essentially, if you have done anything custom to your templates and you auto-upgrade, your customized templates will
				be lost.  <strong>Before upgrading, copy your	customized templates to a safe location (e.g. outside of the
				featurific-for-wordpress directory), upgrade the plugin, and then copy your custom templates back into Featurific's
				templates directory.</strong>  This will prevent your customized templates from being lost.
			</div>
			<h3>Upgrade Options:</h3>
		</td>
	</tr>
	";
}


/**
 * Activate featurific.
 *
 * Attempt to automatically add a call to insert_featurific() in the user's
 * home template, as well as performing other activation tasks.
 */
function featurific_activate($template)
{
	//echo('Activating Featurific<br/>');
		
	featurific_set_default_options();
	featurific_test_environment();
	
	featurific_create_tables();
	
	//$template is non-null (contains the name of the new theme) when featurific_activate() is called by the switch_theme action.
	if($template) {
		$template_path = get_home_template_of_theme(get_theme($template));
	}
	//$template is null when featurific_activate() is called by register_activation_hook.  In this case, just use the current theme's home template.
	else {
		$template_path = get_home_template();
	}
		
	//Force XML generation
	featurific_do_cron(true);

	if(featurific_configure_template($template_path)) {
		update_option('featurific_current_template_configured', true);
	}
	else {
		update_option('featurific_current_template_configured', false);
	}
}


/**
 * Perform actions on plugin deactivation.
 */
function featurific_deactivate()
{
	//echo('Deactivating Featurific');
	
	//featurific_delete_options(); //Only used for debugging
	
	//TODO: Delete SQL tables (e.g. featurific_image_cache)
}


/**
 * Test the configuration of Wordpress/the webserver to determine to what
 * degree it is compatible with certain features required by the plugin.
 */
function featurific_test_environment() {
	featurific_test_plugin_root_write_access();
	featurific_test_image_cache_write_access();
	featurific_test_templates_write_access();
}


/**
 *
 */
function featurific_test_read_access($path) {
	//echo "Testing $path<br/>";
	$f = @fopen($path, 'r');
	
	//Success
	if($f) {
		//echo 'success<br/>';
		fclose($f);
		return true;
	}
	
	return false;
}


/**
 *
 */
function featurific_test_write_access($path) {
	//echo "Testing $path<br/>";
	$f = @fopen($path, 'w');
	
	//Success
	if($f) {
		//echo 'success<br/>';
		fclose($f);
		unlink($path);
		return true;
	}
	
	return false;
}


function featurific_test_featurific_swf_present() {
	$path = featurific_get_plugin_root().'FeaturificFree.swf';
	
	if(featurific_test_read_access($path)) {
		update_option('featurific_swf_installed', true);
		return;
	}
	
	update_option('featurific_swf_installed', false);
}


/**
 *
 */
function featurific_test_plugin_root_write_access() {
	$path = featurific_get_plugin_root().'FeaturificTestPluginRootWriteAccess'.rand(999999999, 9999999999); //Create an (essentially) guaranteed unique filename
	
	if(featurific_test_write_access($path))
		update_option('featurific_root_write_access', true);
	else {
		update_option('featurific_root_write_access', false);
		update_option('featurific_store_data_xml', FEATURIFIC_STORE_IN_DB); //If we don't have root write access, force the data.xml to be stored in the DB.
	}
}


/**
 *
 */
function featurific_test_image_cache_write_access() {
	$path = featurific_get_plugin_root().'image_cache/'.'FeaturificTestImageCacheWriteAccess'.rand(999999999, 9999999999); //Create an (essentially) guaranteed unique filename
	
	if(featurific_test_write_access($path))
		update_option('featurific_image_cache_write_access', true);
	else {
		update_option('featurific_image_cache_write_access', false);
		update_option('featurific_store_cached_images', FEATURIFIC_STORE_IN_DB); //If we don't have image cache write access, force the the images to be stored in the DB.
	}
}


/**
 *
 */
function featurific_test_templates_write_access() {
	$path = featurific_get_plugin_root().'templates/'.'FeaturificTestTemplatesWriteAccess'.rand(999999999, 9999999999); //Create an (essentially) guaranteed unique filename
	
	if(featurific_test_write_access($path))
		update_option('featurific_templates_write_access', true);
	else
		update_option('featurific_templates_write_access', false);
}


/**
 * Attempt to automatically insert the call to insert_featurific() in the user's active template.
 *
 * The algorithm used to do so is as follows:
 *  1. Find the first instance of 'have_posts()' in the template.
 *  2. Find the first instance of '<?' that *precedes* the text found in step 1.
 *  3. Find the first instance of "\n" (newline character) that *precedes* the text found in step 2.
 *  4. Insert our call to insert_featurific() just before the newline character found in step 3.
 *
 * Note that we do lots of error checking because we want to avoid trashing the template at all costs.
 *
 * Returns true if insertion was successful or if the template already contains a call to insert_featurific.
 */
function featurific_configure_template($template_path)
{
	$f = @fopen($template_path, 'r'); //NOTE: The '@' suppresses warning messages.  We need to be certain to check the return value.
	if(!$f) return featurific_configure_template_error("Template file ($template_path) could not be opened for reading.");

	//Read file into a buffer.  Hard on memory, easy on the programmer (string manipulation is much easier than file pointer manipulation).  Since this function is only run on activation, this isn't a problem.
	while(!feof($f))
		$fb .= fgets($f, 4096);
	fclose($f);

	if($fb==null || strlen($fb)<10) return featurific_configure_template_error("Template file ($template_path) could either not be read or is oddly short."); //If the template is less than 10 chars long, something is up.
	//echo "fb len: ".strlen($fb)."<br/>";


	//If the template already contains the call to insert_featurific(), just return (true).
	if(strpos($fb, 'insert_featurific')!==false) {
		return true;
	}



	//1. Find the first instance of 'have_posts()' in the template.
	$pos = strpos($fb, 'have_posts()');
	if($pos===false) return featurific_configure_template_error("Template file ($template_path) does not contain 'have_posts()'.");
	//echo "pos: $pos<br/>";
	//echo "\n\n\n\n<pre>".substr($fb, 0, $pos)."</pre>\n\n\n\n";


	// 2. Find the first instance of '<?' that *precedes* the text found in step 1.
	//We can't use strrpos() because PHP 4 only supports using a single character needle; we need to use a string ('<?')

	//tp: temp position, ltp: last temp position
	$needle = '<?';
	$needle_len = strlen($needle);
	$tp = 0-$needle_len; //Negative needle_len so that the first strpos() search starts at index 0.
	do {
		$ltp = $tp;
		//echo "ltp: $ltp";
		$tp = strpos($fb, $needle, $ltp+$needle_len); //Find the first '<?' after $ltp (+$needle_len because the needle is $needle_len chars long)
	} while($tp!==false && $tp<$pos); //Continue looping while '<?' can be found and precedes the instance of have_posts() found in step 1.
	$pos = $ltp;
	
	if($pos===false) return featurific_configure_template_error("Could not find the preceding '<?' in the template file ($template_path).");
	//echo "pos: $pos<br/>";	
	//echo "\n\n\n\n<pre>".substr($fb, 0, $pos)."</pre>\n\n\n\n";


	// 3. Find the first instance of "\n" (newline character) that *precedes* the text found in step 2.
	//We can use strrpos() here because we're using a needle of length 1.
	$pos = strrpos(substr($fb, 0, $pos), "\n"); //The offset param of strrpos() seems to have no effect, so we have to use substr() to truncate the buffer for searching.
	if($pos===false) return featurific_configure_template_error("Could not find the preceding \"\\n\" in the template file ($template_path).");
	//echo "pos: $pos<br/>";
	//echo "\n\n\n\n<pre>".substr($fb, 0, $pos)."</pre>\n\n\n\n";


	// 4. Insert our call to insert_featurific() just before the newline character found in step 3.
	//Save a back up copy of the template in case something unexpected happens.
	copy($template_path, $template_path.'.original');

	$f = @fopen($template_path, 'w'); //NOTE: The '@' suppresses warning messages.  We need to be certain to check the return value.
	if(!$f) return featurific_configure_template_error("Template file ($template_path) could not be opened for writing.");

	//Write the file from the buffer.  If an error occurs on any of the writes, restore the original file and return.
	if(	fwrite($f, substr($fb, 0, $pos))===false ||
			fwrite($f, featurific_get_template_html())===false ||
			fwrite($f, substr($fb, $pos, strlen($fb)-$pos))===false) {
		fclose($f);
		copy($template_path.'.original', $template_path); //Restore the file to its original state (from the backup copy) just in case our call to fwrite() somehow trashed the template file.
		return featurific_configure_template_error("Could not write to the template file ($template_path).");
	}
	
	return true;
}


/**
 * Write out an error message and return.
 */
function featurific_configure_template_error($error) {
	//echo "Featurific for Wordpress error: $error  For Featurific for Wordpress to function correctly, you need to manually add '&lt;?php insert_featurific(); ?&gt;' to your template file at the desired location.<br/>";
	featurific_show_admin_message_once($error);
	return false;
}


/**
 * Return the HTML needed for calling featurific from the template.
 */
function featurific_get_template_html()
{
	return <<<HTML
<?php
 //Code automatically inserted by Featurific for Wordpress plugin
 if(is_home())                             //If we're generating the home page (remove this line to make Featurific appear on all pages)...
  if(function_exists('insert_featurific')) //If the Featurific plugin is activated...
   insert_featurific();                    //Insert the HTML code to embed Featurific
?>
HTML;
}


/**
 * Alias for insert_featurific().
 */
function featurific_insert() {
	return insert_featurific();
}


/**
 * Output the HTML needed for embedding Featurific (if we're generating the
 * home page).  Regenerate the XML file as necessary.
 *
 * Note that this function uses a somewhat backwards naming scheme.
 * insert_featurific() is more English-like and is less likely to confuse
 * non-programmers when dealing with this call in their templates, so that's
 * what we use here.
 */
function insert_featurific() {
	//Plugin is active and we're on the home page - prepare and insert the HTML.
	featurific_do_cron();
	$web_root = featurific_get_plugin_web_root();
	$width = get_option('featurific_width');
	$height = get_option('featurific_height');
	
	$data_xml_override = get_option('featurific_data_xml_override');
	//Use the data.xml override
	if($data_xml_override!=null && $data_xml_override!='')
		$data_xml_filename = $data_xml_override;
	//Don't use the data.xml override
	else {
		switch(get_option('featurific_store_data_xml')) {
			case FEATURIFIC_STORE_ON_FILESYSTEM:
				//Serve up the XML via the web server from a previously generated flat file
				$data_xml_filename = get_option('featurific_data_xml_filename');
				break;
				
			case FEATURIFIC_STORE_IN_DB:
			default:
				//Serve up the XML dynamically from the DB
				$data_xml_filename = 'data_xml.php';
				break;
		}
	}
	
	$version = FEATURIFIC_VERSION;
	
	$html = <<<HTML
		<center><!-- Begin Featurific Flash Gallery (version {$version}) - http://featurific.com -->
		<script type="text/javascript" src="{$web_root}featurific.js"></script><div id="swfDiv">
		<script type="text/javascript">
		// <![CDATA[
		fo = new SWFObject("{$web_root}FeaturificFree.swf?&lzproxied=false", "lzapp", "$width", "$height", "6", "#FF6600");
		fo.addParam("swLiveConnect", "true");
		fo.addParam("name", "lzapp");
		fo.addParam("wmode", "transparent");
		fo.addParam("allowScriptAccess", "always");
		fo.addVariable("xml_location", "{$web_root}{$data_xml_filename}");
		fo.write("swfDiv");
		// End JS for http://featurific.com
		// ]]>
		</script></div>
		<!-- End Featurific --></center>
HTML;

// 	$html .= <<<HTML
// 		<br/>
// 		<br/>
// 		<br/>
// 		<br/>
// 		<br/>
// 		<br/>
// 		<br/>
// 		<br/>
// 		<br/>
// 		<br/>
// 		<center><!-- Begin Featurific Flash Gallery - featurific.com -->
// 		<script type="text/javascript" src="{$web_root}swfobject.js"></script><div id="swfDiv2" name="swfDiv2" wmode="transparent">
// 		<script type="text/javascript">
// 		// <![CDATA[
// 		fo = new SWFObject("{$web_root}FeaturificProDebug.swf?&lzproxied=false", "lzapp", "$width", "$height", "6", "#FF6600");
// 		fo.addParam("swLiveConnect", "true");
// 		fo.addParam("name", "lzapp");
// 		fo.addParam("wmode", "transparent");
// 		fo.addParam("allowScriptAccess", "always");
// 		fo.addVariable("xml_location", "{$web_root}{$data_xml_filename}");
// 		fo.write("swfDiv2");
// 		// ]]>
// 		</script></div>
// 		<!-- End Featurific --></center>
// HTML;

	echo $html;
}


/**
 * Get the home template of a specified theme.
 *
 * Adapted from Wordpress' get_home_template() in theme.php.
 *
 * @theme: A theme array, as returned by get_theme().
 */
function get_home_template_of_theme($theme) {
	$template = '';

	//Wordpress 2.6ish changed the format of $theme['Template Dir'], removing the preceding 'wp-content' from the values typically returned in the field.  So, we need to detect to see if 'wp-content' is present, and if not, add it to our $template.
	if(strpos($theme['Template Dir'], 'wp-content')!==0)
		$wp_content = 'wp-content';
	else
		$wp_content = '';
		
	//Put home.php if statement before the index.php statement - if both index.php and home.php exist, we want to use home.php.  (From experience, it seems that if both files exist, home.php is more likely to be the actual main template.)
	//TODO: A better solution might be to just insert Featurific into *both* index.php and home.php.
	//Added support for checking loop.php file first (to support Twenty Ten theme and derivatives/relatives) - 10/21/11
	if(file_exists(ABSPATH . $wp_content . $theme['Template Dir'] . "/loop.php"))
		$template = ABSPATH . $wp_content . $theme['Template Dir'] . "/loop.php";
	elseif(file_exists($theme['Template Dir'] . "/loop.php")) //Wordpress 2.9ish (at least that's when I noticed it) seems to have changed $theme['Template Dir'] to be an absolute path... Detect and use that scenario here.
		$template = $theme['Template Dir'] . "/loop.php";
	elseif(file_exists(ABSPATH . $wp_content . $theme['Template Dir'] . "/home.php"))
		$template = ABSPATH . $wp_content . $theme['Template Dir'] . "/home.php";
	elseif(file_exists($theme['Template Dir'] . "/home.php")) //Wordpress 2.9ish (at least that's when I noticed it) seems to have changed $theme['Template Dir'] to be an absolute path... Detect and use that scenario here.
		$template = $theme['Template Dir'] . "/home.php";
	elseif(file_exists(ABSPATH . $wp_content . $theme['Template Dir'] . "/index.php"))
		$template = ABSPATH . $wp_content . $theme['Template Dir'] . "/index.php";
	elseif(file_exists($theme['Template Dir'] . "/index.php")) //Wordpress 2.9ish (at least that's when I noticed it) seems to have changed $theme['Template Dir'] to be an absolute path... Detect and use that scenario here.
		$template = $theme['Template Dir'] . "/index.php";

	return apply_filters('home_template', $template);
}


/**
 * Get the root directory of the Featurific for Wordpress plugin relative to
 * the filesystem root.
 */
function featurific_get_plugin_root() {
	//return substr(__FILE__, 0, strrpos(__FILE__, '/')+1); //Works, but only on POSIX systems (not windows).  And should use DIRECTORY_SEPARATOR instead of '/' anyway...
	return dirname(__FILE__).'/'; //Should work on all systems
}


/**
 * Get the root directory of the Featurific for Wordpress plugin relative to
 * the web root.
 */
function featurific_get_plugin_web_root() {
	$plugin_root = featurific_get_plugin_root();
	//PHP 5 only
	//$plugin_dir_name = substr($plugin_root, strrpos($plugin_root, '/', -2)+1); //-2 to skip the trailing '/' on $plugin_root
	//PHP 4 workaround
	$plugin_dir_name = substr($plugin_root, strrpos(substr($plugin_root, 0, strlen($plugin_root)-2), DIRECTORY_SEPARATOR)+1); //-2 to skip the trailing '/' on $plugin_root

	$web_root = featurific_get_wordpress_web_root() . 'wp-content/plugins/' . $plugin_dir_name;
	
	return $web_root;
}


/**
 * Get the root directory of Wordpress relative to the web root.
 */
function featurific_get_wordpress_web_root() {
	$site_url = get_option('siteurl');
	
	//Test URLs
	//$site_url = 'http://nacl.ir';
	//$site_url = 'http://nacl.ir/';
	//$site_url = 'http://nacl.ir/a-dir/whatever/wordpress';
	//$site_url = 'http://nacl.ir/a-dir/whatever/wordpress/';
	$pos = featurific_strpos_nth(3, $site_url, '/');
	
	if($pos===false)
		$web_root = substr($site_url, strlen($site_url));
	else
		$web_root = '/' . substr($site_url, $pos);
	
	if($web_root[strlen($web_root)-1]!='/')
		$web_root .= '/';

	return $web_root;
}


/**
 * Find the position of the $n-th occurence of $needle in $haystack, starting
 * at $offset.  Not fully tested.
 */
function featurific_strpos_nth($n, $haystack, $needle, $offset=0)
{
	$needle_len = strlen($needle);
	$hits = 0;
	while($hits!=$n) {
		$offset = strpos($haystack, $needle, $offset);
		
		if($offset===false)
			return false;
		
		$offset += $needle_len;
		$hits++;
	}
	
	return $offset;
}


function featurific_parse_images_from_html($html) {
	$images = array();
	$parser = new HtmlParser($html);

	//echo "Working on ***$html***<br/>";
	while ($parser->parse()) {
		if($parser->iNodeType==NODE_TYPE_ELEMENT && strtolower($parser->iNodeName)=='img') {
			$src = $parser->iNodeAttributes['src'];
			
			if($src!=null && $src!='') {
				//echo "Found '$src'<br/>";
				$images[] = $src;
				//$thumbpath = image_resize( $file, $max_side, $max_side );
			}
		}
	}
	
	return $images;
}


/**
 * Convert a Wordpess date into a human-friendly date.
 */
function featurific_date_to_human_date($date) {
  return date('F j, Y', $date);
}


/**
 * Convert a Wordpess date into a long human-friendly date.
 */
function featurific_date_to_long_human_date($date) {
  return date('l jS \of F Y', $date);
}


/**
 * Convert a Wordpess date into a slash-separated date.
 */
function featurific_date_to_slashed_date($date) {
  return date('m/d/y', $date);
}


/**
 * Convert a Wordpess date into a period-separated date.
 */
function featurific_date_to_dotted_date($date) {
  return date('m.d.y', $date);
}


/**
 * Convert a Wordpess date into a human-friendly time.
 */
function featurific_date_to_human_time($date) {
  return date('g:i a', $date);
}


/**
 * Convert a Wordpess date into a long human-friendly time.
 */
function featurific_date_to_long_human_time($date) {
  return date('g:i:s a', $date);
}


/**
 * Convert a Wordpess date into a military time.
 */
function featurific_date_to_military_time($date) {
  return date('H:i:s', $date);
}


/**
 * Helper function for our date/time formatters.
 *
 * Adapted from Wordpress' get_gmt_from_date() in formatting.php.
 */
function featurific_parse_date($string) {
  preg_match('#([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#', $string, $matches);
  return mktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1]);
}


/**
 * Add the Featurific options page to the Settings menu.
 */
function featurific_add_pages() {
	// Add a new submenu under Options:
	add_options_page('Featurific', 'Featurific', 8, 'featurificoptions', 'featurific_options_page');
}


/**
 * Delete Featurific Options
 */
function featurific_delete_options() {
	//User-specified options
	delete_option('featurific_screen_assignment');
	delete_option('featurific_width');
	delete_option('featurific_height');
	delete_option('featurific_type');
	delete_option('featurific_category_filter');
	delete_option('featurific_user_specified_posts');
	delete_option('featurific_generation_frequency');
	delete_option('featurific_data_xml_override');
	delete_option('featurific_template');
	delete_option('featurific_num_posts');
	delete_option('featurific_popular_days');
	delete_option('featurific_auto_excerpt_length');
	delete_option('featurific_screen_duration');
	delete_option('featurific_store_cached_images');
	delete_option('featurific_use_rtl_text');
	delete_option('featurific_rtl_sentence_separators');
	delete_option('featurific_store_data_xml');
	
	//Internal options
	delete_option('featurific_last_generation_time');
	delete_option('featurific_data_xml_filename');
	delete_option('featurific_data_xml_filename_old');
	delete_option('featurific_data_xml_filename_to_delete');
}


/**
 * Set options according to their defaults, but only if the option is undefined.
 * This allows user-specified options to persist if the user disables the
 * plugin for a period of time and then re-enables it later.
 */
function featurific_set_default_options() {
	//User-specified options
	if(get_option('featurific_screen_assignment')===false)				add_option('featurific_screen_assignment', 'random');
	if(get_option('featurific_width')===false)										add_option('featurific_width', 0);
	if(get_option('featurific_height')===false)										add_option('featurific_height', 0);
	if(get_option('featurific_type')===false)											add_option('featurific_type', 'commented');
	if(get_option('featurific_category_filter')===false)					add_option('featurific_category_filter', array());
	if(get_option('featurific_user_specified_posts')===false)			add_option('featurific_user_specified_posts', '');
	if(get_option('featurific_generation_frequency')===false)			add_option('featurific_generation_frequency', 10);
	if(get_option('featurific_data_xml_override')===false)				add_option('featurific_data_xml_override', '');
	if(get_option('featurific_template')===false)									add_option('featurific_template', 'Time.com (Transparent)/template.xml');
	if(get_option('featurific_num_posts')===false)								add_option('featurific_num_posts', 5);
	if(get_option('featurific_popular_days')===false)							add_option('featurific_popular_days', 90);
	if(get_option('featurific_auto_excerpt_length')===false)			add_option('featurific_auto_excerpt_length', 150);
	if(get_option('featurific_screen_duration')===false)					add_option('featurific_screen_duration', 7000);
	if(get_option('featurific_use_rtl_text')===false)							add_option('featurific_use_rtl_text', false);
	if(get_option('featurific_rtl_sentence_separators')===false)	add_option('featurific_rtl_sentence_separators', '.!?-');
	if(get_option('featurific_store_cached_images')===false)			add_option('featurific_store_cached_images', FEATURIFIC_STORE_IN_DB); //We can't use true/false to indicate whether or not to store the data.xml in the database, because false also means that the option is undefined.  So, we use an int (and constants defined at the beginning of this file).
	if(get_option('featurific_store_data_xml')===false)						add_option('featurific_store_data_xml', FEATURIFIC_STORE_IN_DB); //We can't use true/false to indicate whether or not to store the data.xml in the database, because false also means that the option is undefined.  So, we use an int (and constants defined at the beginning of this file).

	//Internal options
	if(get_option('featurific_last_generation_time')===false)					add_option('featurific_last_generation_time', 0);
	if(get_option('featurific_data_xml_filename')===false)						add_option('featurific_data_xml_filename', '');
	if(get_option('featurific_data_xml_filename_old')===false)				add_option('featurific_data_xml_filename_old', '');
	if(get_option('featurific_data_xml_filename_to_delete')===false)	add_option('featurific_data_xml_filename_to_delete', '');
	if(get_option('featurific_admin_messages_to_show_once')===false)	add_option('featurific_admin_messages_to_show_once', array());
}


/**
 * Get all of the available Featurific templates (usually in
 * plugins/featurific/templates)
 *
 * Adapted from Wordpress' get_themes() function in theme.php.  Note that this
 * function is not fully tested.
 */
function featurific_get_templates() {
	$template_root = featurific_get_plugin_root().'templates/';
	$templates = array();
	
	$templates_dir = @ opendir($template_root);
	if ( !$templates_dir )
		return false;

	while ( ($template_dir = readdir($templates_dir)) !== false ) {
		if ( is_dir($template_root . '/' . $template_dir) && is_readable($template_root . '/' . $template_dir) ) {
			if ( $template_dir{0} == '.' || $template_dir == '..' || $template_dir == 'CVS' )
				continue;
			$stylish_dir = @ opendir($template_root . '/' . $template_dir);
			//$found_stylesheet = false;
			while ( ($template_file = readdir($stylish_dir)) !== false ) {
					if ( $template_file == 'template.xml' ) {
						$templates[$template_dir] = $template_dir . '/' . $template_file;
					break;
				}
			}
			@closedir($stylish_dir);
		}
	}
	
	return $templates;
}


/**
 * Display the page content for the Featurific admin submenu, and save
 * the values resulting from form submission of this admin page.
 *
 * Adapted from http://codex.wordpress.org/Adding_Administration_Menus
 */
function featurific_options_page() {
	switch($_GET['action']) {
		case 'installTemplates':
			featurific_install_templates();
			return;
			
		case 'installFeaturific':
			featurific_install_featurific();
			return;
			
		default:
			break;
	}
	
	if($_GET['installed']=='1')
		echo "<div id='featurific-for-wordpress-warning' class='updated fade'><p><a href='options-general.php?page=featurificoptions'>Featurific for Wordpress</a> notice: <strong>FeaturificFree.swf was successfully downloaded and installed</strong>.</p></div>";

	$hidden_field_name = 'featurific_submit_hidden';
	
	//Set up names
	$screen_assignment_opt_name = 'featurific_screen_assignment';
	$width_opt_name = 'featurific_width';
	$height_opt_name = 'featurific_height';
	$type_opt_name = 'featurific_type';
	$category_filter_opt_name = 'featurific_category_filter';
	$user_specified_posts_opt_name = 'featurific_user_specified_posts';
	$frequency_opt_name = 'featurific_generation_frequency';
	$data_xml_override_opt_name = 'featurific_data_xml_override';
	$template_opt_name = 'featurific_template';
	$num_posts_opt_name = 'featurific_num_posts';
	$popular_days_opt_name = 'featurific_popular_days';
	$auto_excerpt_length_opt_name = 'featurific_auto_excerpt_length';
	$screen_duration_opt_name = 'featurific_screen_duration';
	$store_cached_images_opt_name = 'featurific_store_cached_images';
	$use_rtl_text_opt_name = 'featurific_use_rtl_text';
	$rtl_sentence_separators_opt_name = 'featurific_rtl_sentence_separators';
	$store_data_xml_opt_name = 'featurific_store_data_xml';
	//$_opt_name = 'featurific_';

	//Read in existing option values from database
	$screen_assignment_opt_val = get_option($screen_assignment_opt_name);
	$width_opt_val = get_option($width_opt_name);
	$height_opt_val = get_option($height_opt_name);
	$type_opt_val = get_option($type_opt_name);
	$category_filter_val = get_option($category_filter_opt_name);
	$user_specified_posts_opt_val = get_option($user_specified_posts_opt_name);
	$frequency_opt_val = get_option($frequency_opt_name);
	$data_xml_override_opt_val = get_option($data_xml_override_opt_name);
	$template_opt_val = get_option($template_opt_name);
	$num_posts_opt_val = get_option($num_posts_opt_name);
	$popular_days_opt_val = get_option($popular_days_opt_name);
	$auto_excerpt_length_opt_val = get_option($auto_excerpt_length_opt_name);
	$screen_duration_opt_val = get_option($screen_duration_opt_name);
	$store_cached_images_opt_val = get_option($store_cached_images_opt_name);
	$use_rtl_text_opt_val = get_option($use_rtl_text_opt_name);
	$rtl_sentence_separators_opt_val = get_option($rtl_sentence_separators_opt_name);
	$store_data_xml_opt_val = get_option($store_data_xml_opt_name);
	//$_opt_val = get_option($_opt_name);

	// See if the user has posted us some information
	// If they did, this hidden field will be set to 'Y'
	if( $_POST[ $hidden_field_name ] == 'Y' ) {
		//Read the posted values
		$screen_assignment_opt_val = $_POST[$screen_assignment_opt_name];
		//$width_opt_val = $_POST[$width_opt_name];
		//$height_opt_val = $_POST[$height_opt_name];
		$type_opt_val = $_POST[$type_opt_name];
		$category_filter_val = $_POST[$category_filter_opt_name];
		$user_specified_posts_opt_val = $_POST[$user_specified_posts_opt_name];

		//Make sure there's a valid value in the frequency field.  If not, we just insert our own valid value.
		$frequency_opt_val = $_POST[$frequency_opt_name];
		if($_POST[$frequency_opt_name]==null || $_POST[$frequency_opt_name]=='' || $_POST[$frequency_opt_name]<1)
			$frequency_opt_val = 10;

		$data_xml_override_opt_val = $_POST[$data_xml_override_opt_name];
		$template_opt_val = $_POST[$template_opt_name];
		$num_posts_opt_val = $_POST[$num_posts_opt_name];
		$popular_days_opt_val = $_POST[$popular_days_opt_name];
		$auto_excerpt_length_opt_val = $_POST[$auto_excerpt_length_opt_name];
		$screen_duration_opt_val = $_POST[$screen_duration_opt_name];
		$use_rtl_text_opt_val = $_POST[$use_rtl_text_opt_name];
		$rtl_sentence_separators_opt_val = $_POST[$rtl_sentence_separators_opt_name];

		if($_POST[$store_data_xml_opt_name]!=null) $store_data_xml_opt_val = $_POST[$store_data_xml_opt_name]; //Only change the displayed value for this field to the new POSTed value if a value was actually POSTed (if the field was disabled, no value will be submitted)
		if($_POST[$store_cached_images_opt_name]!=null) $store_cached_images_opt_val = $_POST[$store_cached_images_opt_name];
		//$_opt_val = $_POST[$_opt_name];
		//$_opt_val = $_POST[$_opt_name];
		
		//If 'popular' post selection was chosen but the user has not installed Wordpress.com stats correctly, report an error and fall back to another post selection type.
		if($type_opt_val=='popular' && !function_exists('stats_get_csv')) {
			echo "<div id='featurific-for-wordpress-warning' class='updated fade'><p><a href='options-general.php?page=featurificoptions'>Featurific for Wordpress</a> needs attention: please install the <a href='http://wordpress.org/extend/plugins/stats/'>Wordpress.com Stats</a> plugin to use the 'Most popular' post selection type.  Until the plugin is installed, consider using the 'Most commented' post selection type instead.</p></div>";
			$type_opt_val = 'commented'; //'commented' is the best approximation of 'popular' that we have
		}
		
		//If we're using a manually created data.xml override, get the dimensions from the data.xml file.  Otherwise, the dimensions will be set according to the template file when the data.xml file is automatically generated.
		if($data_xml_override_opt_val!=null && $data_xml_override_opt_val!='') {
			$in = file_get_contents(featurific_get_plugin_root() . $data_xml_override_opt_val);
			$xml = new XMLParser($in);
			$xml->Parse();

			$dimensions = featurific_get_dimensions_from_xml($xml);
			$width_opt_val = $dimensions['width'];
			$height_opt_val = $dimensions['height'];
		}
		
		//Save the posted values in the database
		update_option($screen_assignment_opt_name, $screen_assignment_opt_val);
		update_option($width_opt_name, $width_opt_val);
		update_option($height_opt_name, $height_opt_val);
		update_option($type_opt_name, $type_opt_val);
		update_option($category_filter_opt_name, $category_filter_val);
		update_option($user_specified_posts_opt_name, $user_specified_posts_opt_val);
		update_option($frequency_opt_name, $frequency_opt_val);
		update_option($data_xml_override_opt_name, $data_xml_override_opt_val);
		update_option($template_opt_name, $template_opt_val);
		update_option($num_posts_opt_name, $num_posts_opt_val);
		update_option($popular_days_opt_name, $popular_days_opt_val);
		update_option($auto_excerpt_length_opt_name, $auto_excerpt_length_opt_val);
		update_option($screen_duration_opt_name, $screen_duration_opt_val);
		update_option($use_rtl_text_opt_name, $use_rtl_text_opt_val);
		update_option($rtl_sentence_separators_opt_name, $rtl_sentence_separators_opt_val);

		if($store_data_xml_opt_val!=null) update_option($store_data_xml_opt_name, $store_data_xml_opt_val); //Only update the option if it is present (if the field is disabled, the value of this input element is not even included in the POST data.)
		if($store_cached_images_opt_val!=null) update_option($store_cached_images_opt_name, $store_cached_images_opt_val);
		//update_option($_opt_name, $_opt_val);
		
		// Output a status message.
		echo '<div class="updated"><p><strong>Options saved.</strong></p></div>';

	
		//Force XML generation
		featurific_do_cron(true);
	}

	//Prepare the template (or data.xml override) notes.
	$template_opt_val = get_option('featurific_template');
	$in = @file_get_contents(featurific_get_plugin_root() . 'templates/'. $template_opt_val);
	
	//If we couldn't open the template the user has selected, just use the first template we *can* open.
	if($in==null) {
		echo '<strong>Error: Your template (templates/'.$template_opt_val.') could not be loaded.  Please select a new template or restore the missing template.  (Some templates have been moved to the template library.  You might be able to restore the template by installing new/missing templates via the \'New Templates Available!\' link below.)</strong><br/>';
		$templates_tmp = featurific_get_templates();
		foreach ($templates_tmp as $name_tmp => $path_tmp) {
			$in = file_get_contents(featurific_get_plugin_root() . 'templates/'. $path_tmp);
			break;
		}
	}
	
	$xml = new XMLParser($in);
	$xml->Parse();
	$notes_html = featurific_get_notes_from_xml($xml);

	//Prepare other assorted values
	$action_url = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
	$stats_installed_str = function_exists('stats_get_csv')?'<font color="#00cc00">is installed</font>':'<font color="#ff0000">is not installed</font>';
	$plugin_directory = featurific_get_plugin_root();

	//Prepare auto-download
	$num_new_templates = featurific_count_new_templates();
	
	//There are new templates
	if($num_new_templates>0) {
		$new_templates_html = "<div align='right'><a href='$action_url&action=installTemplates'><font size='+2'><strong>New templates available!</strong></font><br/>Auto-download $num_new_templates new templates now</a></div>";
	}
	//Couldn't get library.xml file, so just send user directly to a quick 'n dirty file listing.
	elseif($num_new_templates<0) {
		$new_templates_html = "<div align='right'><a href='".FEATURIFIC_TEMPLATES_URL."/index.html'><font size='+2'><strong>New templates available!</strong></font><br/>Download new templates now</a></div>";
	}
	//No new templates
	else {
		$new_templates_html = "<div align='right'>Template library is up to date.</div>";
	}

	// Display the options editing screen
	?>
<div class="wrap">
<h2>Featurific for Wordpress</h2>

<form name="form1" method="post" action="<?php echo $action_url ?>">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<table class="form-table">


 <tr valign="top">
  <th scope="row">Template:</th>
  <td>
   <?php echo $new_templates_html ?>
   <select name="<?php echo $template_opt_name; ?>">
    <?php
			$templates = featurific_get_templates();
			asort($templates);
			// echo '<!--';
			// print_r($templates);
			// echo '-->';
			foreach($templates as $template => $path) {
				$selected = $path==$template_opt_val?"selected='selected'":'';
				echo "<option value='$path' $selected>$template</option>\n";
				
				if($path==$template_opt_val)
					$current_template_name = $template;
			}
		?>
   </select>
		<?php
			if($notes_html!=null && $notes_html!='') {
		?>
		<div style="background-color:#f9f9ff; padding-top: 1px; padding-bottom: 10px; padding-left: 10px; padding-right: 10px; ">
		<h3>Notes for <font color="#0000cc"><?php echo $current_template_name; ?></font></h3>
		<?php
				echo $notes_html;
				echo '</div>';
			}
		?>
  </td>
 </tr>

 <!--
 <tr valign="top">
  <th scope="row">Gallery Size:</th>
  <td>
   Width <input type="text" name="<?php echo $width_opt_name; ?>" value="<?php echo $width_opt_val; ?>" size="5">
   Height <input type="text" name="<?php echo $height_opt_name; ?>" value="<?php echo $height_opt_val; ?>" size="5">
  </td>
 </tr>
 -->

 <tr valign="top">
  <th scope="row">Post Selection:</th>
  <td>
   <input type="radio" name="<?php echo $type_opt_name; ?>" value='popular' <?php if($type_opt_val=='popular') { echo 'checked'; } ?>> Most popular posts over the last <input type="text" name="<?php echo $popular_days_opt_name; ?>" value="<?php echo $popular_days_opt_val; ?>" size="2"> days (<a href='http://wordpress.org/extend/plugins/stats/'>Wordpress.com Stats Plugin</a> <?php echo $stats_installed_str; ?>)<br/>
   <input type="radio" name="<?php echo $type_opt_name; ?>" value='commented' <?php if($type_opt_val=='commented') { echo 'checked'; } ?>> Most commented posts<br/>
   <input type="radio" name="<?php echo $type_opt_name; ?>" value='recent' <?php if($type_opt_val=='recent') { echo 'checked'; } ?>> Most recent posts<br/>
   <input type="radio" name="<?php echo $type_opt_name; ?>" value='random' <?php if($type_opt_val=='random') { echo 'checked'; } ?>> Random posts<br/>
   <input type="radio" name="<?php echo $type_opt_name; ?>" value='userspecified' <?php if($type_opt_val=='userspecified') { echo 'checked'; } ?>> User-specified posts: <input type="text" name="<?php echo $user_specified_posts_opt_name; ?>" value="<?php echo $user_specified_posts_opt_val; ?>" size="35"> (comma separated - e.g. "4, 1, 16, 5")<br/>
   <!--This field is only used if  '<code>User-specified posts</code>' is selected as the <code>Post Selection</code>.-->
  </td>
 </tr>

 <tr valign="top">
  <th scope="row">Category Filter:</th>
  <td>
   <select style="height: auto;" name="<?php echo $category_filter_opt_name; ?>[]" multiple="multiple"> 
    <?php 
			$categories =  get_categories(array('hide_empty' => false));
			if($categories!=null) {
				foreach ($categories as $cat) {
					if($category_filter_val!=null && in_array($cat->cat_ID, $category_filter_val))
						$selected = 'selected="selected"';
					else
						$selected = '';

					$option = '<option value="'.$cat->cat_ID.'" '.$selected.'>';
					$option .= $cat->cat_name;
					$option .= ' ('.$cat->category_count.')';
					$option .= '</option>';
					echo $option;
				}
			}
    ?>
   </select><br />
   The categories whose posts you want to include in post selection.  Select one or more categories to restrict post selection to those categories.  Select zero categories to allow all posts to be selected regardless of category.  (Select/Deselect with Ctrl-click (PC) or Command-click (Mac))
  </td>
 </tr>

 <tr valign="top">
  <th scope="row">Number of Posts:</th>
  <td>
   <input type="text" name="<?php echo $num_posts_opt_name; ?>" value="<?php echo $num_posts_opt_val; ?>" size="2"> posts<br />
   The number of posts (i.e. screens) to include in the gallery.
  </td>
 </tr>

 <tr valign="top">
  <th scope="row">Screen Duration:</th>
  <td>
   <input type="text" name="<?php echo $screen_duration_opt_name; ?>" value="<?php echo $screen_duration_opt_val; ?>" size="5"> milliseconds<br />
   The duration for which each screen will be displayed.  Note that this value is provided in <strong>milliseconds</strong>.
  </td>
 </tr>

</table>

<h3>Advanced Options</h3>

<table class="form-table">

 <tr valign="top">
  <th scope="row">Screen Assignment:</th>
  <td>
   <input type="radio" name="<?php echo $screen_assignment_opt_name; ?>" value='ordered' <?php if($screen_assignment_opt_val=='ordered') { echo 'checked'; } ?>> Ordered (Assign first post to screen 1, second post to screen 2, etc.  Predictable, but the same screen layout is displayed first each time the page loads.)<br/>
   <input type="radio" name="<?php echo $screen_assignment_opt_name; ?>" value='random' <?php if($screen_assignment_opt_val=='random') { echo 'checked'; } ?>> Random (Assign posts to random screens.  A random screen layout is displayed first each time the page loads, which visually suggests that the page has been updated.)<br />
   Note that a new random screen is chosen every time the data.xml file is generated (every 10 minutes), not every time the page is loaded.
  </td>
 </tr>

 <tr valign="top">
  <th scope="row">Auto-Excerpt Length:</th>
  <td>
   First <input type="text" name="<?php echo $auto_excerpt_length_opt_name; ?>" value="<?php echo $auto_excerpt_length_opt_val; ?>" size="3"> characters<br />
   When an excerpt is not provided for a post, an excerpt is automatically created from the first x characters of the post.  This field specifies how many characters long the auto-excerpt should be (i.e. the value of x).
  </td>
 </tr>

 <tr valign="top">
  <th scope="row">Update Frequency:</th>
  <td>
   Every <input type="text" name="<?php echo $frequency_opt_name; ?>" value="<?php echo $frequency_opt_val; ?>" size="2"> minutes<br />
   How often the gallery will be re-generated (e.g. to include new posts).
  </td>
 </tr>

 <tr valign="top">
  <th scope="row">Storage Location:</th>
  <td>
   <table style="border-collapse: collapse">
    <tr>
     <td style="border-style: none; padding: 5px;"></td>
     <td style="border-style: none; padding: 5px;">Database</td>
     <td style="border-style: none; padding: 5px;">Filesystem</td>
		</tr>
		<tr>
		 <td style="border-style: none; padding: 5px;">data.xml</td>
		 <td style="border: solid; border-width: 1px; text-align: center"><input type="radio" name="<?php echo $store_data_xml_opt_name; ?>" value='<?php echo FEATURIFIC_STORE_IN_DB; ?>' <?php if($store_data_xml_opt_val==FEATURIFIC_STORE_IN_DB) { echo 'checked'; } ?> <?php if(!get_option('featurific_root_write_access')) { echo 'disabled'; } ?>/></td>
     <td style="border: solid; border-width: 1px; text-align: center"><input type="radio" name="<?php echo $store_data_xml_opt_name; ?>" value='<?php echo FEATURIFIC_STORE_ON_FILESYSTEM; ?>' <?php if($store_data_xml_opt_val==FEATURIFIC_STORE_ON_FILESYSTEM) { echo 'checked'; } ?> <?php if(!get_option('featurific_root_write_access')) { echo 'disabled'; } ?>/></td>
    </tr>
    <tr>
     <td style="border-style: none; padding: 5px;">Cached Images</td>
     <td style="border: solid; border-width: 1px; text-align: center"><input type="radio" name="<?php echo $store_cached_images_opt_name; ?>" value='<?php echo FEATURIFIC_STORE_IN_DB; ?>' <?php if($store_cached_images_opt_val==FEATURIFIC_STORE_IN_DB) { echo 'checked'; } ?> <?php if(!get_option('featurific_image_cache_write_access')) { echo 'disabled'; } ?>/></td>
     <td style="border: solid; border-width: 1px; text-align: center"><input type="radio" name="<?php echo $store_cached_images_opt_name; ?>" value='<?php echo FEATURIFIC_STORE_ON_FILESYSTEM; ?>' <?php if($store_cached_images_opt_val==FEATURIFIC_STORE_ON_FILESYSTEM) { echo 'checked'; } ?> <?php if(!get_option('featurific_image_cache_write_access')) { echo 'disabled'; } ?>/></td>
    </tr>
   </table>
   <ul>
    <li>Database: <strong>Advantage: Convenience.</strong>  Store the files in the database and serve them to clients via PHP.  This eliminates the need for the web server to be able to write to the filesystem.</li>
    <li>Filesystem: <strong>Advantage: Speed.</strong>  Store the files on the filesystem and serve them to clients directly via the web server.  This decreases server load substantially, but is more difficult to set up than 'Database' storage.</li>
    <li>If any of these options are greyed out, it is because your server is only configured to allow 'Database' storage.  To enable 'Filesystem' storage, the web server needs write access to the following locations.  (After you make the directories writeable, deactivate and reactivate Featurific for Wordpress for your changes to be detected.)
     <ul>
      <li>For data.xml files: <code><?php echo featurific_get_plugin_root(); ?></code></li>
      <li>For cached images: <code><?php echo featurific_get_plugin_root(); ?>image_cache</code></li>
     </ul>
    </li>
   </ul>
  </td>
 </tr>

 <tr valign="top">
  <th scope="row">RTL Text (beta):</th>
  <td>
   <input type="checkbox" name="<?php echo $use_rtl_text_opt_name; ?>" <?php if($use_rtl_text_opt_val) { echo 'checked'; } ?>> Use RTL (Right To Left) text for display of RTL languages (Arabic, Persian, Hebrew, Farsi, Urdu, etc.).<br/>
   RTL sentence separators: <input type="text" name="<?php echo $rtl_sentence_separators_opt_name; ?>" value="<?php echo $rtl_sentence_separators_opt_val; ?>" size="15"><br/>
   RTL sentence separators are characters that are used to find sentence boundaries in RTL text.  These separators ensure RTL text is displayed correctly.  Typical separators include limited punctuation such as '.!?-' (the '-' is necessary because it replaces the newline character when present).<br/>
   <strong>Note</strong>: When using RTL Text, you may want to use one of the templates labeled as "(Right Justified)".
  </td>
 </tr>

 <tr valign="top">
  <th scope="row">data.xml Override:</th>
  <td>
   <input type="text" name="<?php echo $data_xml_override_opt_name; ?>" value="<?php echo $data_xml_override_opt_val; ?>" size="20"><br />
   Specify a manually-created <code>data.xml</code> file to use instead of the auto-generated one.  This file should be in the Featurific plugin directory (<?php echo $plugin_directory; ?>) (Leave this field blank to use the auto-generated data.xml)
  </td>
 </tr>

</table>

<hr />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'featurific_trans_domain' ) ?>" />
</p>

</form>
</div>

<?php
 
}


/**
 * Perform cron-ish functions, such as generating the data.xml file every x
 * minutes.
 *
 * Note that this isn't a true cron because it runs at *most* every x minutes,
 * not exactly every x minutes.  (Unless $ignore_times is set to true, in which case
 * cron runs immediately, regardless of when it was last run)  If there are no
 * calls to insert_featurific() for longer than x minutes, the data.xml is not
 * regenerated.  However, when the first visitor hits the main page after the
 * respite, the data.xml file will be regenerated.
 *
 * Note that even if $ignore_times is true, if we're using a data_xml_override,
 * the XML file will NOT be regenerated.
 */
function featurific_do_cron($ignore_times=false) {
	//Return if the user has specified a manually created XML file
	$data_xml_override = get_option('featurific_data_xml_override');
	if($data_xml_override!=null && $data_xml_override!='')
		return;

	//If ignore_times is false, determine if we can just return (and avoid the cost of regenerating the XML file)
	if(!$ignore_times) {
		$diff = time() - get_option('featurific_last_generation_time');
		$freq_in_secs = get_option('featurific_generation_frequency')*60; //TODO: Optimization candidate
	
		//echo "diff: $diff<br/>";
		//echo "freq_in_secs: $freq_in_secs<br/>";

		//Return if the necessary rest period has not passed
		if($diff<$freq_in_secs)
			return;
	}

	//Either ignore_times was true, or we determined that we need to regenerate the XML file.  So, regenerate the file.
	update_option('featurific_last_generation_time', time());
	featurific_data_xml_housekeeping();
}


/**
 * Perform data.xml-related housekeeping functions.
 *
 * Specifically, create a new data.xml filename and generate the file.  Set
 * the _old_filename and _filename_to_delete options appropriately.  Note that
 * we change the filename every time we generate a new data.xml file so that
 * browsers and proxies don't cache the XML file.  This is the simplest way
 * to ensure caching does not occur - other (more complicated and suboptimal)
 * possibilities include cache-related HTTP headers, generating and serving
 * the XML file dynamically, etc.
 */
function featurific_data_xml_housekeeping() {
	//echo 'Performing data.xml housekeeping...<br/>';

	//Get the current options.
	$f_new = 'data_' . time() . '.xml';
	$f_current = get_option('featurific_data_xml_filename');
	$f_old = get_option('featurific_data_xml_filename_old');
	$f_to_delete = get_option('featurific_data_xml_filename_to_delete');
	
	// echo "f_new: $f_new<br/>";
	// echo "f_current: $f_current<br/>";
	// echo "f_old: $f_old<br/>";
	// echo "f_to_delete: $f_to_delete<br/>";

	//Generate the new XML file.
	$success = featurific_generate_data_xml($f_new);
	
	if(!$success) {
		featurific_show_admin_message_once('Featurific for Wordpress needs your attention: There was a minor error while generating your Featurific data.xml file.  Don\'t worry - everything is fine with your site and Featurific.  However, we recommend that you <a href="http://featurific.com/content/contact-us">contact us</a> so we can help you resolve this minor issue.');
		return;
	}

	//Delete the oldest XML file.
	if($f_to_delete!='')
		@unlink(featurific_get_plugin_root() . $f_to_delete); //NOTE: The '@' suppresses warnings (e.g. if the file could not be deleted, no error is reported.)

	//Update the options.  Note that we keep two old files around to avoid attempts at deleting the file while the web server is still sending it to clients.  It's unprobable, although possible, that if we immediately deleted the old XML file, that a client could still be requesting the old file.  So, keeping around the *two* old XML files (instead of just one) essentially eliminates this possibility.
	update_option('featurific_data_xml_filename', $f_new);
	update_option('featurific_data_xml_filename_old', $f_current);
	update_option('featurific_data_xml_filename_to_delete', $f_old);
}


/**
 * Generate the data.xml file according to the current template and post
 * selection type.
 */
function featurific_generate_data_xml($output_filename) {
	$template = get_option('featurific_template');
	
	//Parse the template XML
	$in = @file_get_contents(featurific_get_plugin_root() . 'templates/' . $template);
	
	//If for some reason the chosen template is inaccessible, just choose the first valid template found and use it.
	if($in==false) {
		$templates = featurific_get_templates();
		asort($templates);
		foreach($templates as $template => $path) {
			$in = @file_get_contents(featurific_get_plugin_root() . 'templates/'. $path);
			if($in) break;
		}
	}
	
	//If no valid template can be found, just return.
	if($in==false)
		return false;

	$template_xml = new XMLParser($in);
	$template_xml->Parse();
	
	//Set options for the dimensions of the gallery
	$dimensions = featurific_get_dimensions_from_xml($template_xml);
	update_option('featurific_width', $dimensions['width']);
	update_option('featurific_height', $dimensions['height']);
	
	//Generate the gallery XML
	$out = "<data>\n";
	$out .= featurific_generate_non_screen_elements($template_xml);
	
	$posts = featurific_get_posts(
		get_option('featurific_type'),
		get_option('featurific_category_filter'),
		get_option('featurific_num_posts'),
		get_option('featurific_user_specified_posts')
	);
	
	$out .= featurific_generate_screen_elements($template_xml, $posts);

	$out .= "\n</data>\n";
	
	$out = featurific_strip_tags($out);
	
	//echo str_replace("\n", "<br/>", htmlentities($out));

	//Write the XML to the data persistence layer (in the DB or directly to disk)
	switch(get_option('featurific_store_data_xml')) {
		case FEATURIFIC_STORE_ON_FILESYSTEM:
			//Write the gallery XML to disk
			$fout_filename = featurific_get_plugin_root() . $output_filename;

			$fout = @fopen($fout_filename, 'w'); //NOTE: The '@' suppresses warning messages.  We need to be certain to check the return value.
			if(!$fout) {
				echo "Could not open $fout_filename for writing.";
				return false;
			}

			if(fwrite($fout, $out)===false) {
				echo "Could not write the XML to $fout_filename.";
				fclose($fout);
				return false;
			}

			fclose($fout);
			break;
			
		case FEATURIFIC_STORE_IN_DB:
		default:
			//Write the gallery XML to the DB
			update_option('featurific_data_xml', $out);
			//echo get_option('featurific_data_xml');
			break;
	}

	return true;
}


/**
 * Return the height and width specified in the template or data.xml file.
 *
 * Since template XML and data.xml files have a similar structure, this
 * function works on both file types.
 */
function featurific_get_dimensions_from_xml($xml) {
	$dimensions = array();
	
	$dimensions['width'] = $xml->document->size[0]->tagAttrs['width'];
	$dimensions['height'] = $xml->document->size[0]->tagAttrs['height'];
	
	return $dimensions;
}


/**
 * Return the contents of the <notes>...</notes> tag.
 *
 * Since template XML and data.xml files have a similar structure, this
 * function works on both file types.
 */
function featurific_get_notes_from_xml($xml) {
	return
		str_replace('[', '<',
			str_replace(']', '>',
				$xml->document->notes[0]->tagData
			)
		);
}


/**
 * Generate the non-screen XML elements (size, automute, timeout, etc.)
 */
function featurific_generate_non_screen_elements($template_xml) {
	$out = '';
	
	foreach($template_xml->document->tagChildren as $k => $v) {
		if(strtolower($v->tagName)!='screen')
			$out .= $v->GetXML()."\n";
	}

	return $out;
}


/**
 * Generate the screen XML elements according to the specified $template_xml
 * and $posts.
 */
function featurific_generate_screen_elements($template_xml, $posts) {
	$templates = featurific_get_template_screen_elements($template_xml);
	$screen_assignment_type = get_option('featurific_screen_assignment');
	$last_template_id = -1; //-1 so that the first time into featurific_choose_screen_template(), the template at index 0 is chosen (if we're using the 'ordered' type)
	$out = '';
	
	//echo str_replace("\n", "<br/>", htmlentities($templates[0]->GetXML()));
	
	//Generate the screen elements
	foreach ($posts as $post_id => $post) {
		//TODO: Note that $last_template_id is passed by reference - it will be incremented and managed within featurific_choose_screen_template().  I don't like this solution, it should be re-coded.
		$screen_template_xml = featurific_choose_screen_template($templates, $screen_assignment_type, $last_template_id);
		
		//If something went wrong with getting the screen template xml, just continue to the next post.
		if($screen_template_xml==null)
			continue;
		
		//Create the translation array from the post
		$tr_arr = featurific_generate_translation_array($post);

		//Parse and set tag defaults
		foreach($screen_template_xml->tagChildren as $c) {
			if(strtolower($c->tagName)!='default')
				continue;
			
			$key = '%'.$c->tagAttrs['name'].'%';
			$value = $c->tagAttrs['value'];
			// echo "default: $key = $value<br/>";
			
			//Only set the tag to the default value if the post did not specify a valid value
			if($tr_arr[$key]==null || $tr_arr[$key]=='')
				$tr_arr[$key] = $value;
		}

		//Actually perform the translation
		$out .= strtr($screen_template_xml->GetXML(), $tr_arr);
		$out .= "\n";
	}
	
	return $out;
}


/**
 * Generate the translation array used for converting tags (e.g. %tagname%)
 * to their respective values from $post.
 */
function featurific_generate_translation_array($post) {
	$tr_arr = array();
	
	//Wrap all keys in '%', leave values untouched.
	foreach ($post as $k => $v) {
		$v = featurific_flash_escape($v);
		$tr_arr["%$k%"] = $v;
	}
	
	return $tr_arr;
}


/**
 * Escape a string suitable for use in Flash-manipulated XML.
 *
 * We could use htmlspecialchars(), but that escapes too much (&, ', ", <, >).
 * We only need to escape ' and ", so that's all we do here.  In addition to
 * escaping special HTML chars, we also want to escape '%' characters since
 * those have special meaning in our XML attributes.  We also need to
 * transform some HTML sequences that are not valid in Flash (e.g.
 * Some users copy over their posts from Word/OpenOffice which may use, for
 * example, &ldquo; &rdquo; &lsquo; &rsquo; for opening and closing quotes.
 * Flash (or at least the font we're using?) doesn't support these sequences,
 * so we need to transform them to their normal (' and ") equivalents.)
 */
function featurific_flash_escape($s) {
	//OPT: Optimization candidate

	return
	$string =
		str_replace('<', '&lt;',
			str_replace('>', '&gt;',
				str_replace('\'', '&#039;',
					str_replace('"', '&quot;',
						str_replace('%', '&#37;',
							str_replace('&ldquo;', '&quot;',
								str_replace('&rdquo;', '&quot;',
									str_replace('&lsquo;', '&#039;',
										str_replace('&rsquo;', '&#039;', $s)
									)
								)
							)
						)		
					)
				)
			)
		);
}


/**
 * Strip all Featurific for Wordpress tags (e.g. %tagname%) from $out.
 */
function featurific_strip_tags($out) {
	return ereg_replace("%[a-zA-Z0-9_]+%", '', $out);
}


/**
 * Convert $html to plain text.  Newlines, <div>s, <table>s (etc) become
 * ' - '.  Unlike many html to plain text converters, this function makes no
 * attempt to emulate HTML formatting in the plaintext (beyond replacing
 * certain elements with ' - '.)
 *
 * Adapted from a (slightly flawed) function found at
 * http://sb2.info/php-script-html-plain-text-convert/
 */
function featurific_html_to_text($html) {
	//Remove everything inside of <style> tags.
	$html = preg_replace('/<style[^>]*>.*?<\/style[^>]*>/si','',$html);

	//Remove everything inside of <script> tags.
	$html = preg_replace('/<script[^>]*>.*?<\/script[^>]*>/si','',$html);

	//Replace certain elements (that typically result in line breaks) with a newline character.
  $tags = array (
	  0 => '/<(\/)?h[123][^>]*>/si',
	  1 => '/<(\/)?h[456][^>]*>/si',
	  2 => '/<(\/)?table[^>]*>/si',
	  3 => '/<(\/)?tr[^>]*>/si',
	  4 => '/<(\/)?li[^>]*>/si',
	  5 => '/<(\/)?br[^>]*>/si',
	  6 => '/<(\/)?p[^>]*>/si',
	  7 => '/<(\/)?div[^>]*>/si',
  );
  $html = preg_replace($tags, "\n", $html);

	//Remove tags
	$html = preg_replace('/<[^>]+>/s', '', $html);

	//Replace non-breaking spaces with actual spaces.
	$html = preg_replace('/\&nbsp;/', ' ', $html);

	//Reduce spaces
	$html = preg_replace('/ +/s', ' ', $html);
	$html = preg_replace('/^\s+/m', '', $html);
	$html = preg_replace('/\s+$/m', '', $html);

	//Replace newlines with spaces
	$html = preg_replace('/\n+/s', '-!Line Break123!-', $html); //-!Line Break123!- is just a string that is highly unlikely to occur in the original string.

	//Reduce line break chars.
	$html = preg_replace('/(-!Line Break123!-)+/s', ' - ', $html);

	//Reduce spaces
	$html = preg_replace('/ +/s', ' ', $html);
	$html = preg_replace('/^\s+/m', '', $html);
	$html = preg_replace('/\s+$/m', '', $html);

	return $html;
}


/**
 * Convert $s to RTL text.
 *
 */
/**
 * Known bugs with RTL support:
 *  - On Barebones Black, the (Read More...) text is messed up on the Arabic test slide... Suggesting that the Flash player is doing some sort of auto-RTL-ing. Weird.
 *  - Multiple spaces within text are condensed down to one (actually, not a bug of the RTL process, this is a bug of the strip HTML process)
 *  - Flash portion of the plugin (Featurific itself) still needs to be modified to display with 'tf.autoSize = TextFieldAutoSize.RIGHT;' when RTL is enabled.
 *  - Will likely need to update templates, creating RTL versions of them (how does this work?  When we turn on TextFieldAutoSize.RIGHT, does the text 'grow' from the specified point to the left (instead of growing to the right, as is done with LTR text))
 */
function featurific_string_to_rtl($s) {
	//Split the string into sentences.
	$separators = get_option('featurific_rtl_sentence_separators');
	$pattern = '/([' . preg_quote($separators) . ']+)/';
	$sentences = preg_split($pattern, $s, -1, PREG_SPLIT_DELIM_CAPTURE); //TODO: Security risk?  (We're not sanitizing the chars the user enters - just spitting it straight into this regex.)

	// featurific_dump($pattern);
	// featurific_dump($s);
	// featurific_dump($sentences);
	
	$sentence_rtl = '';
	
	//Reverse the words in each sentence.
	foreach ($sentences as $sentence) {
		//Get preceding whitespace
		preg_match('/^\s+/', $sentence, $matches);
		$whitespace_prefix = $matches[0];

		//Get following whitespace
		preg_match('/\s+$/', $sentence, $matches);
		$whitespace_postfix = $matches[0];
		
		//Split the sentence into its constituent words.
		$pattern = '/([ ]+)/';
		$words = preg_split($pattern, $sentence, -1, PREG_SPLIT_DELIM_CAPTURE);
		$words = array_reverse($words);
		
		//Cumulatively reassemble the words onto the sentence (in the new RTL order).
		$sentence_rtl .= $whitespace_prefix . trim(implode($words)) . $whitespace_postfix; //Reassemble the words, in reverse order, with the whitespace that originally preceded and followed the sentence correctly positioned (in its original location either at the beginning or end of the sentence).
		// featurific_dump($words);
		// featurific_dump($sentence_rtl);
		// featurific_dump($whitespace_prefix);
		// featurific_dump($whitespace_postfix);
	}
	
	return $sentence_rtl;
}


/**
 * Choose a particular screen template in $templates according to the
 * $screen_assignment_type, and (if 'ordered' is chosen), the
 * $last_template_id.
 */
function featurific_choose_screen_template($templates, $screen_assignment_type, &$last_template_id) {
	$templates_size = sizeof($templates);
		
	//Ordered
	if($screen_assignment_type=='ordered' && $last_template_id!==null) {
		if($last_template_id==$templates_size-1) {
			$last_template_id = 0;
			return $templates[$last_template_id];
		}
			
		$last_template_id++;
		return $templates[$last_template_id];
	}
	//Random
	else {
		return $templates[rand(0, $templates_size-1)];
	}
}


/**
 * Get the screen elements from $template_xml.
 */
function featurific_get_template_screen_elements($template_xml) {
	$s = array();
	
	foreach($template_xml->document->tagChildren as $k => $v) {
		if(strtolower($v->tagName)=='screen')
			$s[] = $v;
	}

	return $s;
}


/**
 * Get an array of $n posts according to the post selection type ($type) and
 * (if 'userspecified' is chosen) $post_list.
 */
function featurific_get_posts($type, $cat_filter, $n, $post_list=null)
{
	switch($type) {
		case 'popular':
			$days = get_option('featurific_popular_days');
			$popular_posts = stats_get_csv('postviews', "days=$days&limit=0"); //Get all posts with stats over the last $days
			
			$post_list = '';
			foreach ($popular_posts as $post) {
				if($post_list!='')
					$post_list .= ', ';
					
				$post_list .= $post['post_id'];
			}
			
			return featurific_get_posts('userspecified', $cat_filter, $n, $post_list);
			break;

			
		case 'recent':
			// $posts = get_posts(
			// 	array(
			// 		'numberposts' => 5, 'offset' => 0,
			// 		'category' => 0, 'orderby' => 'post_date',
			// 		'order' => 'DESC', 'include' => '',
			// 		'exclude' => '', 'meta_key' => '',
			// 		'meta_value' =>'', 'post_type' => 'post',
			// 		'post_status' => 'publish', 'post_parent' => 0
			// 	)
			// );

			$posts = get_posts(
				array(
					'numberposts' => FEATURIFIC_MAX_INT, //Get all posts.  We initially just used 0 (zero) here, which seemed to return all posts, but this didn't work on some installations.  My suspicion is that another plugin was interfering.  Regardless, this is a sufficient solution for now.
					'orderby' => 'post_date'
				)
			);
			
			break;

			
		case 'commented':
			$posts = get_posts(
				array(
					'numberposts' => FEATURIFIC_MAX_INT,
					'orderby' => 'comment_count'
				)
			);
			break;


		case 'random':
			$posts = get_posts(
				array(
					'numberposts' => FEATURIFIC_MAX_INT,
					'orderby' => 'rand'
				)
			);
			break;


		case 'userspecified':
			$posts_tmp = get_posts(
				array(
					'numberposts' => FEATURIFIC_MAX_INT,
					'include' => $post_list //Only get posts within the comma-separated $post_list
				)
			);
			
			//Order posts according to their order in $post_list
			$posts = array();
			$post_list_arr = preg_split('/[\s,]+/', $post_list); //From WP's post.php
			
			//For all post id's in the $post_list
			foreach($post_list_arr as $post_id) {
				//Find the post with the corresponding post id
				foreach($posts_tmp as $post) {
					if($post->ID==$post_id) {
						$posts[] = $post;
						break; //Break out of the inner-most loop
					}
				}
			}
			break;

		
		//TODO: combinations of types

			
		default:
			$posts = null;
			break;
	}
	
	//echo 'Initial post selection yielded '.sizeof($posts).' posts.<br/>';
	
	if($cat_filter==null || sizeof($cat_filter)<1)
		$do_category_filter = false;
	else
		$do_category_filter = true;
		
	//Convert get_posts()'s returned array of objects to an array of arrays to make the data easier to work with.
	//Also, re-index the posts so they can be accessed by their post id.  Note that given PHP's arrays, this still retains the order of the posts in the new $posts_fixed array - they will be in the same order as we insert them, not in order according to their numeric keys (post id)
	$posts_fixed = array();
	if($posts!=null && sizeof($posts)>0 && is_object($posts[0])) {
		foreach ($posts as $k => $v) {
			
			//Once we've stored the specified max number of posts, stop searching for more posts (break out of this loop)
			if(sizeof($posts_fixed)==$n)
				break;

			//Copy the post to $posts_fixed if it belongs to a category specified in $cat_filter OR if category filtering is disabled
			$post_categories = wp_get_post_categories($v->ID);
			if(!$do_category_filter || ($do_category_filter && sizeof(array_intersect($cat_filter, $post_categories))>0))
				$posts_fixed[$v->ID] = (array) $v;
		}
	}
	
	//echo 'Final post selection yielded '.sizeof($posts_fixed).' posts.<br/>';

	featurific_get_posts_categories($posts_fixed);	//Add categories
	featurific_get_posts_tags($posts_fixed);				//Add tags
	featurific_get_posts_meta($posts_fixed);				//Add custom fields
	featurific_get_posts_tweak($posts_fixed);
	
	return $posts_fixed;
}


/**
 * Get the text version of categories for all $posts.  Since this is pass by
 * reference, we modify $posts in place and return nothing.
 */
function featurific_get_posts_categories(&$posts) {
	//For each post, get the categories
	foreach ($posts as $post_id => $post) {
		$cats = wp_get_post_categories($post_id);
		
		//For each category, get the name
		$categories = '';
		$cat_num = 1;
		foreach ($cats as $cat_id) {
			$cat = get_category($cat_id);
			
			//Comma-separated list of categories
			if($categories!='')
				$categories .= ', ';
			$categories .= $cat->name;
			
			//New entry for every category
			$posts[$post_id]["category_$cat_num"] = $cat->name;
			$cat_num++;
		}
		
		$posts[$post_id]['categories'] = $categories;
	}
}


/**
 * Get the text version of tags for all $posts.  Since this is pass by
 * reference, we modify $posts in place and return nothing.
 */
function featurific_get_posts_tags(&$posts) {
	//For each post, get the categories
	foreach ($posts as $post_id => $post) {
		$tags = get_the_tags($post_id);
		
		$tags_str = '';
		if($tags!=null && sizeof($tags)>0) {
			//For each tag, get the name
			$tag_num = 1;
			foreach ($tags as $tag) {
				//Comma-separated list of tags
				if($tags_str!='')
					$tags_str .= ', ';
				$tags_str .= $tag->name;

				//New entry for every tag
				$posts[$post_id]["tag_$tag_num"] = $tag->name;
				$tag_num++;
			}
		}
		
		$posts[$post_id]['tags'] = $tags_str;
	}
}


/**
 * Get the custom fields for all $posts.  Since this is pass by reference, we
 * modify $posts in place and return nothing.
 */
function featurific_get_posts_meta(&$posts) {
	//For each post, get the custom fields
	foreach ($posts as $post_id => $post) {
		$custom_fields = get_post_custom($post_id);
		
		//print_r($custom_fields);
		
		//For each field, get the value
		foreach ($custom_fields as $k => $v) {
			$posts[$post_id][$k] = $v[0];
		}
	}
}


/**
 * Tweak certain values for all $posts.  Since this is pass by reference, we
 * modify $posts in place and return nothing.
 *
 * Due to the fact that this function tweaks values created by other
 * featurific_get_posts_xxx() functions, it should be called last of all of
 * the featurific_get_posts_xxx() functions.
 */
function featurific_get_posts_tweak(&$posts) {
	//NOTE: Since this method is called AFTER featurific_get_posts_meta(), we can't use custom fields to override any tags defined in this method (e.g. post_human_date) unless we've explicitly checked to ensure that the value is empty before we write to it (like we're doing with 'post_excerpt' and 'image_x').  As for the other tags, (e.g. the 'post_xxx_date' fields, writing a method to only perform the assignment if the original value is null would be trivial.
	
	$date_chars = array('d', 'D', 'j', 'l', 'N', 'S', 'w', 'z', 'W', 'F', 'm', 'M', 'n', 't', 'L', 'o', 'Y', 'y', 'a', 'A', 'B', 'g', 'G', 'h', 'H', 'i', 's', 'u', 'e', 'I', 'O', 'P', 'T', 'Z', 'c', 'r', 'U');

	//For each post...
	$screen_number = 1;
	foreach ($posts as $post_id => $p) {
		//Post Date/Time
		$date_str = $p['post_date'];
		$date = featurific_parse_date($date_str);
		$posts[$post_id]['post_human_date'] = featurific_date_to_human_date($date);
		$posts[$post_id]['post_long_human_date'] = featurific_date_to_long_human_date($date);
		$posts[$post_id]['post_slashed_date'] = featurific_date_to_slashed_date($date);
		$posts[$post_id]['post_dotted_date'] = featurific_date_to_dotted_date($date);
		$posts[$post_id]['post_human_time'] = featurific_date_to_human_time($date);
		$posts[$post_id]['post_long_human_time'] = featurific_date_to_long_human_time($date);
		$posts[$post_id]['post_military_time'] = featurific_date_to_military_time($date);
		
		foreach($date_chars as $dc)
			$posts[$post_id]["post_date_$dc"] = date($dc, $date);


		//Modified Date/Time
		$date_str = $p['post_modified'];
		$date = featurific_parse_date($date_str);
		$posts[$post_id]['post_modified_human_date'] = featurific_date_to_human_date($date);
		$posts[$post_id]['post_modified_long_human_date'] = featurific_date_to_long_human_date($date);
		$posts[$post_id]['post_modified_slashed_date'] = featurific_date_to_slashed_date($date);
		$posts[$post_id]['post_modified_dotted_date'] = featurific_date_to_dotted_date($date);
		$posts[$post_id]['post_modified_human_time'] = featurific_date_to_human_time($date);
		$posts[$post_id]['post_modified_long_human_time'] = featurific_date_to_long_human_time($date);
		$posts[$post_id]['post_modified_military_time'] = featurific_date_to_military_time($date);

		foreach($date_chars as $dc)
			$posts[$post_id]["post_modified_date_$dc"] = date($dc, $date);		


		//Copy the post_content from $p to $posts[]
		$posts[$post_id]['post_content'] = $p['post_content'];


		//Process any shortcodes, converting them into their resulting HTML.
		if(function_exists('do_shortcode')) {
			//gallery_shortcode() expects the global object $post to have a member, ID ($post->ID), which contains the post id for the current post.  So, we prepare that value here.
			global $post;
			$post = new StdClass();
			$post->ID = $post_id;
			$posts[$post_id]['post_content'] = do_shortcode($posts[$post_id]['post_content']);
		}


		//Find images in the post content's HTML and prepare the images and $posts[$post_id] so the images can be accessed in the template.
		$web_root = get_option('siteurl'); //e.g. 'http://mysite.com/wordpress' (provided wordpress was installed at public_html/wordpress)
		$images = featurific_parse_images_from_html($posts[$post_id]['post_content']);
		//print dp_attachment_image($post->ID, 'full', 'alt="' . $post->post_title . '"');


		//Before we process the images and cache them if necessary, load any image_x custom fields.  These custom fields specify images that should be used *instead of* OR *in addition to* (depending on whether or not the corresponding image (e.g. image_1) was found in the post) the existing images as parsed from the post.
		//We already have the custom fields in $posts[$post_id] since featurific_get_posts_tweak() is called after featurific_get_posts_meta().
		foreach($posts[$post_id] as $k => $v) {
			
			//If this is an image_x custom field...
			if(strpos($k, 'image_')===0) {
				
				//Get the image number and add it to the $images working variable
				$image_number = intval(substr($k, 6)); //6 because strlen('image_') is 6
				$images[$image_number] = $v;
			}
		}
		
		
		$image_number = 1;
		foreach($images as $image) {
			//Only process one image per post to prevent Featurific from running out of memory.  (All existing templates only use one image per post (the first one, or 'image_1'), so we really only need to process one image per post.)
			if($image_number>1)
				break;
				
			//echo "pos: ".strpos($image, $web_root)."<br/>";
			if($posts[$post_id]['image_'.$image_number]!=null)
				$image = $posts[$post_id]['image_'.$image_number];
				
			$image = str_replace(' ', '%20', $image); //Escape spaces in the filename.  TODO: Are there other characters that need escaping?
			
			//If the image is on the same domain (absolute path or relative path), we can access it from Flash 9 directly.
			if(strpos($image, $web_root)===0 || $image[0]=='/' || $image[0]=='\\')
				$image_url = $image;
			//If the image is on a different domain, we can't access it from Flash 9 directly, so we've got to load it by proxy (save locally via PHP).
			else {
				$image_data = file_get_contents($image);
				
				//On error, just continue to the next image
				if($image_data===false)
					continue;
				
				//Store the cached images as files on the local filesystem
				if(get_option('featurific_store_cached_images')==FEATURIFIC_STORE_ON_FILESYSTEM) {
					$relative_path = 'image_cache/screen_'.$screen_number.'_image_'.$image_number;
					$image_path = featurific_get_plugin_root().$relative_path;
					$bytes_written = @file_put_contents($image_path, $image_data); //NOTE: The '@' suppresses warning messages.  We need to be certain to check the return value.
				
					//On error, just continue to the next image
					if($bytes_written===false) {
						echo "Error writing proxied image to file ($image_path)";
						continue;
					}

					$image_url = featurific_get_plugin_web_root().$relative_path;
				}
				//Store the cached images in the DB
				else {
					$image_info = getimagesize($image); //OPT Yes, this requires fetching the image via HTTP twice (we fetch it once to get the image (above), and again to get the mime type); but since getimagesize() will only work with a filename/uri, I can't just feed it the raw data.  The best workaround I found for this was writing a temporary file (which we can't because we might not have write access to the FS), and using stream_wrapper_register() (http://fi.php.net/manual/en/function.stream-wrapper-register.php) to 'fake' the file

					$success = featurific_image_cache_put_image($screen_number, $image_number, $image_data, $image_info['mime']);
					//echo $success?'put successful!':'put failed!';
					
					$image_url = featurific_get_plugin_web_root()."cached_image.php?snum=$screen_number&inum=$image_number";
					//echo "Stored featurific_cached_image_screen_{$screen_number}_image_{$image_number}.<br/>";

					// echo "Its value was: $image_data";
					// echo "Try it: " . get_option("featurific_cached_image_screen_{$screen_number}_image_{$image_number}");
				}
			}

			//Actually add the image url to the post.  Images can be accessed in template.xml in this manner: %image_1%, %image_2%, etc.
			$posts[$post_id]["image_$image_number"] = $image_url;
			
			$image_number++;
		}


		//Fix up the post content for plaintext display.
		$posts[$post_id]['post_content'] =
			featurific_html_to_text(					//Convert the HTML to text
				str_replace("\xC2\xA0", '',			//The wordpress editor seems to put the chars "\xC2\xA0" into our content (perhaps at newlines?).  Remove these extra characters before converting to plain text.
					$posts[$post_id]['post_content']
				)
			);


		//Fix up the post title for plaintext display.
		$posts[$post_id]['post_title'] = featurific_html_to_text($posts[$post_id]['post_title']);
		
		//Convert various fields in the post to RTL (if specified)
		if(get_option('featurific_use_rtl_text')) {
			$posts[$post_id]['post_title'] = featurific_string_to_rtl($posts[$post_id]['post_title']);
			$posts[$post_id]['post_content'] = featurific_string_to_rtl($posts[$post_id]['post_content']);
		}


		//If the post doesn't have a post_excerpt, then create one.
		if($posts[$post_id]['post_excerpt']==null || $posts[$post_id]['post_excerpt']=='') {
			$auto_excerpt_chars = get_option('featurific_auto_excerpt_length');
			$s = $posts[$post_id]['post_content'];
			$s = substr($s, 0, $auto_excerpt_chars);
			$s = substr($s, 0, strrpos($s, ' '));
			
			$posts[$post_id]['post_excerpt'] = $s;
		}
		//If the post does already have an excerpt, convert the HTMl to text.
		else {
			$posts[$post_id]['post_excerpt'] = featurific_html_to_text($posts[$post_id]['post_excerpt']);
		}

		//Etc
		$posts[$post_id]['nickname'] = get_usermeta($p['post_author'], 'nickname');
		$posts[$post_id]['url'] = apply_filters('the_permalink', get_permalink($post_id)); //Instead of using $p['guid'], this method (copied from link-template.php's the_permalink()) generates functional URLs even if the blog is moved to a new directory/domain/etc.
		$posts[$post_id]['screen_duration'] = get_option('featurific_screen_duration');
		$posts[$post_id]['screen_number'] = $screen_number;
		
		$screen_number++;
	}
}


/**
 * file_get_contents for PHP 4
 *
 * (from http://www.phpbuilder.com/board/showthread.php?t=10292234)
 */
// Check to see if functin exists 
if (!function_exists('file_get_contents')) { 

    // Define function and arguments 
    function file_get_contents($file, $include=false) 
    { 
        // Varify arguments are correct types 
        if (!is_string($file))  return(false); 
        if (!is_bool($include)) return(false); 
         
        // Open the file with givin options 
        if (!$handle = @fopen($file, 'rb', $include)) return(false); 
        // Read data from file 
        $contents = fread($handle, filesize($file)); 
        // Close file 
        fclose($handle); 
         
        // Return contents of file 
        return($contents); 
    } 
}


function featurific_get_template_library() {
	//TODO: Temporary workaround.  Check to see if we can use file_get_contents.  If not, just return.  (The better, long-term solution, is to use the WP_Http class in http.php instead of file_get_contents)
	if(ini_get('allow_url_fopen')!=1)
		return null;

	$data = file_get_contents(FEATURIFIC_TEMPLATES_URL.'/'.FEATURIFIC_TEMPLATES_LIBRARY_FILENAME);
	
	//featurific_dump($data);

	if($data===false)
		return null;

	$xml = new XMLParser($data);
	$xml->Parse();
	//$xml->document->template[0]->tagAttrs['dirname'];
	
	return $xml;
}


function featurific_count_new_templates() {
	$xml = featurific_get_template_library();
	
	if($xml==null)
		return -1;
	
	$num_new_templates = 0;
	foreach($xml->document->template as $template) {
		if(!featurific_check_template_exists($template->tagAttrs['dirname']))
			$num_new_templates++;
	}

	return $num_new_templates;
}


function featurific_check_template_exists($dirname)
{
	$path = featurific_get_plugin_root() . 'templates/'. $dirname;
	//featurific_dump('testing ' . $path);
	
	$f = @fopen($path, 'r');

	//If the path could be opened, then the template exists
	if($f) {
		fclose($f);
		return true;
	}
	
	return false;
}


function featurific_get_credentials() {
	if(!function_exists('request_filesystem_credentials')) {
		return null;
	}
	
	$url = wp_nonce_url("update.php?action=upgrade-plugin&plugin=$plugin", "upgrade-plugin_$plugin");
	if ( false === ($credentials = request_filesystem_credentials($url)) )
		return null;

	if ( ! WP_Filesystem($credentials) ) {
		$error = true;
		if ( is_object($wp_filesystem) && $wp_filesystem->errors->get_error_code() )
			$error = $wp_filesystem->errors;
		request_filesystem_credentials($url, '', $error); //Failed to connect, Error and request again
		return null;
	}

/*
	echo '<div class="wrap">';
	echo '<h2>' . __('Upgrade Plugin') . '</h2>';
	if ( $wp_filesystem->errors->get_error_code() ) {
		foreach ( $wp_filesystem->errors->get_error_messages() as $message )
			show_message($message);
		echo '</div>';
		return null;
	}
*/
	
	return $credentials;
}


function featurific_install_featurific() {
	$featurific_settings_url = featurific_get_wordpress_web_root().'wp-admin/options-general.php?page=featurificoptions';
?>

	<div class="wrap">
	<h2>Featurific for Wordpress</h2>

	<?php
		if(get_option('featurific_swf_installed')) {
			echo 'FeaturificFree.swf has already been successfully installed.';
			return;
		}
		
		$auto_install_prequisites_met = true;
		
		if(!get_option('featurific_root_write_access')) {
			echo '<br/><strong><font color="red">Auto Install prerequisite not met:</font></strong> In order for auto installation to work, Wordpress needs write access to the home directory of Featurific for Wordpress.  The full path of this directory is:<code>'.featurific_get_plugin_root().'</code>.  (After you make the directory writeable, deactivate and reactivate Featurific for Wordpress for your changes to be detected.)';
			$auto_install_prequisites_met = false;
		}
		
		$manual_install_instructions = '<h3>Installation</h3>To manually install Featurific Free:<br/><br/><ol><li>Download <a href="'.FEATURIFIC_SWF_MANUAL_URL.'">FeaturificFree.swf</a>.</li><li>Upload FeaturificFree.swf to your web server at the following location: <code>'.featurific_get_plugin_root().'</code>.</li><li>That\'s it!</li></ol><br/>';
		if(!$auto_install_prequisites_met) {
			echo '<br/><br/>To install Featurific Free, either <strong>satisfy the prerequisites above</strong> or <strong>perform the simple manual installation</strong> detailed below.';
			echo $manual_install_instructions;
		}
	?>
		<br/>
		<?php
			if($auto_install_prequisites_met) {
				$success = featurific_install_swf(FEATURIFIC_SWF_URL);
				if($success) {
					// echo '<script type="text/javascript">setTimeout(function() {window.location = "' . $featurific_settings_url . '"}, 3000)</script>';
					echo '<script type="text/javascript">window.location = "' . $featurific_settings_url . '&installed=1"</script>';
					// echo '<br/><br/><center><h1><font color="#00cc00">FeaturificFree.swf installation successful</font></h1><a href="' . $featurific_settings_url . '">Edit Featurific for Wordpress settings</a></center><br/><br/><br/>';
				}
				else {
					// echo 'Auto Installation failed.  Don\'t give up hope, though!  Please try the simple Manual Installation steps below.';
					echo $manual_install_instructions;
				}
			}
		?>

	<hr />
	</div>
<?php
	//Update the 'featurific_swf_installed' option.
	featurific_test_featurific_swf_present();
}


function featurific_install_swf($url) {
	global $wp_filesystem;

	// $status_indicator = '<strong>...</strong>';
	// 
	// echo '<strong>Starting</strong>';
	// 
	// echo $status_indicator;
	// echo '<strong>Downloading</strong>';

	//$data = file_get_contents($url);
	$data = file_get_contents(str_replace(' ', '%20', $url));
	$dir = featurific_get_plugin_root();
	$filename = basename($url);

	// echo $status_indicator;
	
	if($data==null)
		return false;

	// echo $status_indicator;
	// echo '<strong>Saving Archive</strong>';
	
	file_put_contents($dir.$filename, $data);

	// echo $status_indicator;
	
	//Adapted from WP's plugin-install.php
	// Is a filesystem accessor setup?
	if ( ! $wp_filesystem || ! is_object($wp_filesystem) )
		WP_Filesystem();

	if ( ! is_object($wp_filesystem) ) {
		featurific_dump(new WP_Error('fs_unavailable', __('Could not access filesystem.')));
		return false;
	}

	if ( $wp_filesystem->errors->get_error_code() ) {
		featurific_dump(new WP_Error('fs_error', __('Filesystem error'), $wp_filesystem->errors));
		return false;
	}

	return true;
}


function featurific_install_templates() {
	$credentials = featurific_get_credentials();
	
	$tmp_back_url = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
	$back_url = substr($tmp_back_url, 0, strrpos($tmp_back_url, '&action'));
?>

	<div class="wrap">
	<h2>Featurific for Wordpress</h2>
	<a href="<?php echo $back_url ?>">Back to main configuration page</a><br/>

	<?php
		$xml = featurific_get_template_library();
		$auto_install_prequisites_met = true;
		
		if(!function_exists('unzip_file')) {
			echo '<br/><strong><font color="red">Auto-install prerequisite not met:</font></strong> In order for template auto installation to work, the PHP function \'unzip_file()\' is required.  The easiest way to satisfy this prerequisite is by upgrading your Wordpress version.';
			$auto_install_prequisites_met = false;
		}

		if(!get_option('featurific_templates_write_access')) {
			echo '<br/><strong><font color="red">Auto-install prerequisite not met:</font></strong> In order for template auto installation to work, Wordpress needs write access to the \'templates\' directory.  Its full path is:<code>'.featurific_get_plugin_root().'templates</code>.  (After you make the directory writeable, deactivate and reactivate Featurific for Wordpress for your changes to be detected.)';
			$auto_install_prequisites_met = false;
		}
		
		if($credentials==null) {
			if(function_exists('request_filesystem_credentials'))
				echo '<br/><strong><font color="red">Auto-install prerequisite not met:</font></strong> In order for template auto installation to work, Wordpress needs advanced access to your filesystem.  Please fill out and submit the form at the top of this page to satisfy this prerequisite.';
			$auto_install_prequisites_met = false;
		}
		
		if(!$auto_install_prequisites_met) {
			echo '<br/><br/><h3>Manual Installation</h3>Since auto installation is currently not available on your installation, please satisfy the dependencies above or manually install the desired templates.  To manually install a template:<ul><li>First, download the .zip file and extract its contents.</li><li>Second, upload the contents of the zip file to <code>'.featurific_get_plugin_root().'templates</code>.</li><li>That\'s it!</li></ul>';
		}

		?>
		<br/>
		<h3>Note</h3>
		The Template Auto-Install functionality is a beta feature.  We'd love to hear your thoughts on it - <a href="mailto:rich@byu.net">drop us a line!</a><br/>
		<table class="form-table">
		<?php
		
		$num_new_templates = 0;
		foreach($xml->document->template as $template) {
			$url = FEATURIFIC_TEMPLATES_URL.'/'.$template->tagAttrs['filename'];

			if(featurific_check_template_exists($template->tagAttrs['dirname']))
				continue;
			
			$num_new_templates++;
			?>

			 <tr valign="top">
			  <td>
					<table width="700">
						<tr>
							<td width="15%">
								<strong><?php echo $template->tagAttrs['name'] ?></strong>
							</td>
							<td width="40%">
								<?php
									$screenshot_url = FEATURIFIC_TEMPLATES_URL.'/'.$template->tagAttrs['screenshot'];
								?>
								<a href="<?php echo $screenshot_url ?>"><img width="200" src="<?php echo $screenshot_url ?>"/></a>
							</td>
							<td width="35%">
								<?php echo $template->tagAttrs['shortdescription'] ?>
							</td>
							<td width="10%">
								<?php
									if($auto_install_prequisites_met) {
										echo '<small>';
										if(featurific_install_template($url))
											echo '<strong>Done!</strong>';
										else
											echo '<strong>Error</strong>';
										echo '</small>';
									}
									else {
										echo '<a href="'.$url.'">Download</a>';
									}
								?>
							</td>
						</tr>
					</table>
			  </td>
			 </tr>

			<?php
		}
	?>

	</table>

	<hr />
	</div>

<?php
	if($num_new_templates==0)
		echo '<br/>Template library is up to date.';
}


function featurific_install_template($url) {
	global $wp_filesystem;

	$status_indicator = '<strong>...</strong>';

	echo '<strong>Starting</strong>';

	echo $status_indicator;
	echo '<strong>Downloading</strong>';

	//$data = file_get_contents($url);
	$data = file_get_contents(str_replace(' ', '%20', $url));
	$dir = featurific_get_plugin_root() . 'templates/';
	$filename = basename($url);

	echo $status_indicator;
	
	if($data==null)
		return false;

	echo $status_indicator;
	echo '<strong>Saving Archive</strong>';
	
	file_put_contents($dir.$filename, $data);

	echo $status_indicator;
	
	//Adapted from WP's plugin-install.php
	// Is a filesystem accessor setup?
	if ( ! $wp_filesystem || ! is_object($wp_filesystem) )
		WP_Filesystem();

	if ( ! is_object($wp_filesystem) ) {
		featurific_dump(new WP_Error('fs_unavailable', __('Could not access filesystem.')));
		return false;
	}

	if ( $wp_filesystem->errors->get_error_code() ) {
		featurific_dump(new WP_Error('fs_error', __('Filesystem error'), $wp_filesystem->errors));
		return false;
	}

	echo '<strong>Unarchiving</strong>';
	echo $status_indicator;

	// Unzip package to working directory
	//echo 'Unzipping '.$dir.$filename.' to '.$dir;
	$result = unzip_file($dir.$filename, $dir);

	echo $status_indicator;
	echo '<strong>Deleting Archive</strong>';

	// Once extracted, delete the package
	@unlink($dir.$filename);
	echo $status_indicator;

	if ( is_wp_error($result) ) {
		// $wp_filesystem->delete($working_dir, true);
		// return $result;
		return false;
	}
	echo $status_indicator;
	
	return true;
}


/*
function featurific_install_template($url) {
	//Portions adapted from WP's plugin-install.php

	// Is a filesystem accessor setup?
	if(!$wp_filesystem || !is_object($wp_filesystem))
		WP_Filesystem();
		
	featurific_dump(get_filesystem_method());

	global $wp_filesystem;

	$status_indicator = '<strong>.</strong>';
	echo $status_indicator;

	$data = file_get_contents(str_replace(' ', '%20', $url));
	$dir = featurific_get_plugin_root() . 'templates/';
	$filename = basename($url);
	echo $status_indicator;

	featurific_dump($dir.'blahhhh');
	if(!$wp_filesystem->mkdir($dir.'blahhhh', FS_CHMOD_DIR))
	//if(!$wp_filesystem->mkdir($dir.'blahhhh', 777))
		return false;

	//$data = file_get_contents($url);
	
	if($data==null)
		return false;
	echo $status_indicator;
	
	file_put_contents($dir.$filename, $data);
	echo $status_indicator;
	
	// Unzip package to working directory
	$result = unzip_file($dir.$filename, $dir);
	echo $status_indicator;

	// Once extracted, delete the package
	//@unlink($dir.$filename);
	echo $status_indicator;

	featurific_dump($result);
	if ( is_wp_error($result) ) {
		// $wp_filesystem->delete($working_dir, true);
		// return $result;
		return false;
	}
	echo $status_indicator;
	
	return true;
}
*/


/**
 * file_put_contents for PHP 4
 *
 * (from http://www.phpbuilder.com/board/showthread.php?t=10292234)
 */
if (!function_exists('file_put_contents')) { 
    // Define flags related to file_put_contents(), if necessary 
    if (!defined('FILE_USE_INCLUDE_PATH')) { 
        define('FILE_USE_INCLUDE_PATH', 1); 
    } 
    if (!defined('FILE_APPEND')) { 
        define('FILE_APPEND', 8); 
    } 

    function file_put_contents($filename, $data, $flags = 0) { 
        // Handle single dimensional array data 
        if (is_array($data)) { 
            // Join the array elements 
            $data = implode('', $data); 
        } 

        // Flags should be an integral value 
        $flags = (int)$flags; 
        // Set the mode for fopen(), defaulting to 'wb' 
        $mode = ($flags & FILE_APPEND) ? 'ab' : 'wb'; 
        $use_include_path = (bool)($flags & FILE_USE_INCLUDE_PATH); 

        // Open file with filename as a string 
        if ($fp = fopen("$filename", $mode, $use_include_path)) { 
            // Acquire exclusive lock if requested 
            if ($flags & LOCK_EX) { 
                if (!flock($fp, LOCK_EX)) { 
                    fclose($fp); 
                    return false; 
                } 
            } 

            // Write the data as a string 
            $bytes = fwrite($fp, "$data"); 

            // Release exclusive lock if it was acquired 
            if ($flags & LOCK_EX) { 
                flock($fp, LOCK_UN); 
            } 

            fclose($fp); 
            return $bytes; // number of bytes written 
        } else { 
            return false; 
        } 
    } 
}


/**
 * Formatted print_r().  From http://www.bin-co.com/php/scripts/dump/
 *
 * Arguments : $data - the variable that must be displayed
 * Prints a array, an object or a scalar variable in an easy to view format.
 */
function featurific_dump($data) {
    if(is_array($data)) { //If the given variable is an array, print using the print_r function.
        print "<pre>-----------------------\n";
        print_r($data);
        print "-----------------------</pre>";
    } elseif (is_object($data)) {
        print "<pre>==========================\n";
        var_dump($data);
        print "===========================</pre>";
    } else {
        print "=========&gt; ";
        var_dump($data);
        print " &lt;=========";
    }
		print "<br/>";
}
?>