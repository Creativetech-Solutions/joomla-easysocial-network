<?php
/**
* @package RSBrixton!
* @copyright (C) 2009-2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class pkg_rsbrixtonInstallerScript
{
	public function preflight($type, $parent) {
		$app = JFactory::getApplication();
		
		$jversion = new JVersion();
		if (!$jversion->isCompatible('3.2.0')) {
			$app->enqueueMessage('Please upgrade to at least Joomla! 3.2.0 before continuing!', 'error');
			return false;
		}

		return true;
	}
	public function postflight($type, $parent) {
		if ($type == 'uninstall') {
			return true;
		}
		?>
<style>
/* isis backend template fix */
@media (min-width: 768px) and (max-width: 1120px) {
	.row-fluid [class*="span"] {
		margin-left:10px;
	}
}
@media (max-width: 767px) {
	#system-message-container.j-toggle-main, #j-main-container.j-toggle-main, #system-debug.j-toggle-main{
		float:none;
	}
}
/* End isis backend template fix */

#j-main-container > .span12 > strong {
	font-weight: normal;
}
.template-intro *,
.template-intro *::before,
.template-intro *::after {
	box-sizing: border-box;
}
.template-intro-container {
	margin: 0 auto;
	max-width: 85%;
}

.template-intro-container .btn {
	font-size: 90%;
	font-weight: bold;
	margin-bottom:10px;
}
.intro-header {
	text-align: center;
	padding: 60px 60px 0;
	background: url("../templates/rsbrixton/images/installer/bg.jpg") no-repeat top left;
}

.intro-header-title {
	font-weight: normal;
	color: rgb(255, 255, 255);
	margin-bottom: 60px;
}

.intro-header-title small {
	color: rgba(255, 255, 255, 0.5);
}

.intro-header-screens {
	position: relative;
	bottom: -8px;
}
.intro-footer, .intro-content-highlights, .intro-content-features {
	padding: 60px 20px;
}

.intro-content-highlights {
	background-color: #e2e0da;
}

.intro-quote {
	max-width: 70%;
	text-align: center;
	font-style: italic;
	opacity: 0.75;
	margin: 0 auto 50px auto;
}
.intro-section-title {
	font-weight: normal;
	text-align: center;
	padding-bottom: 20px;
	margin-bottom: 40px;
	border-bottom: 1px solid rgba(0, 0, 0, 0.15);
	font-size:40px;
	line-height:1;
}
.thumbnails .row-fluid .span4{
	margin-bottom:40px;
}
.thumbnail {
	padding: 30px;
	border: 1px solid rgba(0, 0, 0, 0.15);
	background-color: rgba(255, 255, 255, 0.35);
	box-shadow: none;
}

.thumbnail h4 {
	line-height:1.3;
	font-size: 18px;
	margin: 25px 0 0 0;
	text-align: center;
}

.intro-feature .btn,
.thumbnail .btn {
	margin-top: 20px;
}

.intro-feature h4 {
	margin-bottom: 25px;
}

.intro-feature + .intro-feature {
	margin-top: 40px;
}
.intro-footer {
	text-align: center;
	background: #202935;
	color:#fff;
}

.version-history {
	margin: 0 auto 2rem auto;
	padding: 0;
	list-style-type: none;
	color:#ffffff;
}
.version-history > li {
	margin: 0 0 0.5em 0;
	padding: 0 0 0 4em;
	text-align:left;
	font-weight:normal;
}
.version-new,
.version-fixed,
.version-upgraded {
	float: left;
	font-size: 0.8em;
	margin-left: -4.9em;
	width: 4.5em;
	color: white;
	text-align: center;
	font-weight: bold;
	text-transform: uppercase;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}

.version-new {
	background: #7dc35b;
}
.version-fixed {
	background: #e9a130;
}
.version-upgraded {
	background: #61b3de;
}

.install-ok {
	background: #7dc35b;
	color: #fff;
	padding: 3px;
}

.install-not-ok {
	background: #E9452F;
	color: #fff;
	padding: 3px;
}

#installer-left {
	border: 1px solid #e0e0e0;
	float: left;
	margin: 10px;
}

#installer-right {
	float: left;
}

.tpl-button {
	display: inline-block;
	background: #459300 url(../templates/rsbrixton/images/bg-button-green.gif) top left repeat-x !important;
	border: 1px solid #459300 !important;
	padding: 2px;
	color: #fff !important;
	cursor: pointer;
	margin: 0;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	text-decoration: none !important;
}
.tpl-button:hover {
	text-decoration: underline !important;
}

.big-warning {
	background: #FAF0DB;
	border: solid 1px #EBC46F;
	padding: 5px;
}

.big-warning b {
	color: red;
}

</style>
	<div class="template-intro">
		<div class="intro-header">
			<div class="template-intro-container">
				<div class="row-fluid">
					<h1 class="intro-header-title">RSBrixton! <small>Multipurpose Joomla 3.X Template</small></h1>
				</div>
				<div class="row-fluid">
					<img class="intro-header-screens" src="../templates/rsbrixton/images/installer/screens-brixton.png" alt="">
				</div>
			</div>
		</div><!-- .intro-header -->
		<div class="intro-content">
			<section class="intro-content-highlights">
				<div class="template-intro-container">
					<div class="row-fluid">
						<p class="intro-quote lead">“RSBrixton! brings a electric fresh feel with its modern layout style and colour palette making quite an impression on new viewers.”</p>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<h3 class="intro-section-title"><strong>RSBrixton!</strong> Highlights</h3>
						</div>
					</div>
					<div class="thumbnails">
						<div class="row-fluid">
							<div class="span4">
								<div class="thumbnail">
									<img src="../templates/rsbrixton/images/installer/1.jpg" alt="">
									<h4>26 predefined color schemes</h4>
								</div>
							</div>
							<div class="span4">
								<div class="thumbnail">
									<img src="../templates/rsbrixton/images/installer/2.jpg" alt="">
									<h4>15 available background patterns</h4>
								</div>
							</div>
							<div class="span4">
								<div class="thumbnail">
									<img src="../templates/rsbrixton/images/installer/3.jpg" alt="">
									<h4>Fixed background image</h4>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span4">
								<div class="thumbnail">
									<img src="../templates/rsbrixton/images/installer/4.jpg" alt="">
									<h4>12 preloader effects </h4>
								</div>
							</div>
							<div class="span4">
								<div class="thumbnail">
									<img src="../templates/rsbrixton/images/installer/5.jpg" alt="">
									<h4>Google Fonts support</h4>
								</div>
							</div>
							<div class="span4">
								<div class="thumbnail">
									<img src="../templates/rsbrixton/images/installer/6.jpg" alt="">
									<h4>Social links</h4>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span4">
								<div class="thumbnail">
									<img src="../templates/rsbrixton/images/installer/7.jpg" alt="">
									<h4>Over 50 positions to choose from</h4>
								</div>
							</div>
							<div class="span4">
								<div class="thumbnail">
									<img src="../templates/rsbrixton/images/installer/8.gif" alt="">
									<h4>Fade content effect</h4>
								</div>
							</div>
							<div class="span4">
								<div class="thumbnail">
									<img src="../templates/rsbrixton/images/installer/9.jpg" alt="">
									<h4>Easy to use Shortcodes</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section><!-- .intro-content-highlights -->
		</div><!-- .intro-content -->
		<div class="intro-footer">
			<h3 class="text-center">RSBrixton! v1.0.13 Changelog</h3>
			<ul class="version-history">
				<li><span class="version-fixed">Fix</span> RSFiles! menu was not displayed properly.</li>
			</ul>
			<div class="template-intro-container">
				<a class="btn btn-success btn-large" href="index.php?option=com_templates">Start using RSBrixton!</a>
				<a class="btn btn-info btn-large" href="https://www.rsjoomla.com/support/documentation/view-knowledgebase/278-rsbrixton.html">Read the RSBrixton! User Guide</a>
				<a class="btn btn-warning btn-large" href="http://www.rsjoomla.com/customer-support/tickets.html">Get Support!</a>
			</div>
		</div><!-- .intro-footer -->
	</div>
		<?php
	}
}