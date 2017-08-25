<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

/**
 * Projects application installer.
 *
 * @since	1.0
 * @author	Dowalo
 */
class SocialUserAppsProjectsInstaller implements SocialAppInstaller
{
	/**
	 * This is executed during the initial installation after the files are copied over.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	bool	True to proceed with installation, false otherwise.
	 *
	 * @author	Dowalo
	 */
	public function install()
	{
		/*
		 * Run something here if necessary
		 */
		 return true;
	}

	/**
	 * This is executed during the uninstallation.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	bool	True to proceed with uninstall, false otherwise.
	 *
	 * @author	Dowalo
	 */
	public function uninstall()
	{
		/*
		 * Run something here if necessary
		 */
		 return true;
	}

	/**
	 * This is executed when user upgrades the application.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	bool	True to proceed with the upgrade, false otherwise.
	 *
	 * @author	Dowalo
	 */
	public function upgrade()
	{
		/*
		 * Run something here if necessary
		 */
		 return true;
	}

	/**
	 * This is executed when there is an error during the installation.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	bool	True to proceed with the process, false otherwise.
	 *
	 * @author	Dowalo
	 */
	public function error()
	{
		/*
		 * Run something here if necessary
		 */
		 return true;
	}

	/**
	 * This is executed when installation is successfull.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	string	Message to be displayed to the user.
	 *
	 * @author	Dowalo
	 */
	public function success()
	{
		ob_start();
	?>
	<h6>Thank you for installing Projects!</h6>
	<p>
		Projects is an application that alows user on the site to create projects that is visible to either public user, friends, registered users and also allow them to share on their favorite social network sites..
	</p>

	<ul class="list-unstyled">

		<li>
			<div>Like us on Facebook</div>
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<div class="fb-like" data-href="http://www.facebook.com/StackIdeas" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>

		</li>

		<li style="margin-top:20px;">
			<div>Follow us on Twitter</div>
			<a href="https://twitter.com/stackideas" class="twitter-follow-button" data-show-count="false">Follow @stackideas</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</li>
	</ul>
	<?php

		$message = ob_get_contents();
		@ob_end_clean();


		return $message;
	}
}
