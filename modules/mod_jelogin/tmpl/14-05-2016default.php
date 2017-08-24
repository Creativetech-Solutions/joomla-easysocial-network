<?php

/**

* @package Affiliate

* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.

* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php

* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com

* Visit : http://www.joomlaextensions.co.in/

**/ 

defined('_JEXEC') or die('Restricted access'); 

JHTML::_('behavior.modal');
$uri = JURI::getInstance();
$url= $uri->root();
//$option	= JRequest::getVar('option', 'com_hoicoiapi','request','string');
//$welcome	= JRequest::getVar('welcome', 0,'request','int');  
//$Itemid = JRequest::getVar('Itemid','','request','int');

$doc =  JFactory::getDocument();

//$doc->addStyleSheet("components/com_joomproject/assets/css/registration.css");
//$doc->addStyleSheet("components/com_joomproject/assets/css/popup_style.css");
//$doc->addScript("components/com_joomproject/assets/js/main_popup.js");


//$doc->addStyleSheet('modules/mod_jelogin/css/login_style.css');
$user =  clone(JFactory::getUser());
$link_forget_pass = JRoute::_( 'index.php?tmpl=component&option=com_users&view=reset' );
//$doc->addStyleSheet("modules/mod_jelogin/css/popup_style.css");
//$doc->addScript("modules/mod_jelogin/js/main_popup.js");

?>

<link href="<?php echo $url.'modules/mod_jelogin/css/popup_style.css'; ?>" rel="stylesheet" />
<script src="<?php echo $url.'modules/mod_jelogin/js/main_popup.js'; ?>" type="text/javascript"></script>
 <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css" />
<script type="text/javascript" language="javascript">
	//jQuery.noConflict();	

	function validatelogin()
	{ 
		var uname = document.getElementById("modlgn_username").value;
		var passwd = document.getElementById("modlgn_passwd").value;
		if(uname=='')
		{
			alert('<?php echo JText::_('PLEASE_ENTER_USERNAME');?>');
			document.getElementById("modlgn_username").focus();
			return false;

		}

		if(passwd=='')
		{
			alert('<?php echo JText::_('PLEASE_ENTER_PASSWORD');?>');
			document.getElementById("modlgn_passwd").focus();
			return false;
		}
 
	}
</script>
<?php  if($type == 'logout') : ?>
 
<div class="main-logindiv">

	<form action="index.php" method="post" name="login" id="form-login">



		<script type="text/javascript">
		var old_div = "";
		function profatch(active_div)
		{
			if(document.getElementById(active_div).style.display=='block'){
			document.getElementById(active_div).style.display='none';
			} else {
				document.getElementById(active_div).style.display='block';
			}
			
			if(old_div!="" && old_div!=active_div)
			document.getElementById(old_div).style.display='none';
			old_div = active_div;	
		}

		</script>

	 


		<input type="hidden" name="option" value="com_hoicoiapi" />

		<input type="hidden" name="view" id="view" value="register" />

		<input type="hidden" name="task" id="task" value="logout" />

		<input type="hidden" name="return" value="<?php echo $return; ?>" />

	</form>
</div>
<?php else : ?>

 
<form action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" >
 
 
<div class="main-logindivnew"> 
	<div class="caping_login">
			<a href="index.php?option=com_joomproject&view=registration" ><i class="fa fa-sign-in">&nbsp;</i><?php echo JText::_('SIGN_UP');?></a>
		<nav class="main-nav">
       		<p class="cd-switcher">
            	<a href="javascript:void(0);" class="SIGNIN cd-signin"> <i class="fa fa-sign-in">&nbsp;</i><?php echo JText::_('SIGN_IN');?></a>

            </p>   
		</nav>     
	</div>

	<div class="cd-user-modal"> 
		<div class="cd-user-modal-container">
		 
			<div id="cd-login"> 
  			
			 	<form action="<?php echo JRoute::_( 'index.php'); ?>" method="post" name="login" id="form-login" >

	            	<div class="greetingtext">
	                   <?php echo JText::_( 'WELCOME_BACK');?>
	                </div>
            
	                <div class="facebookdvi" align="center">
						<?php jimport('joomla.application.module.helper');
						$module = JModuleHelper::getModule('mod_slogin');
						echo JModuleHelper::renderModule($module); ?>
					</div>
            
					<div class="maindivloginii">
	                	<div class="choice_separator"><span><?php echo JText::_('JOR'); ?></span></div>
	                   		<ul class="login_uls">
			                    <li>
			                       <label for="signin-email" class="image-email fa fa-user"></label> 
			                       <input id="modlgn_username" placeholder="<?php echo JText::_('COM_HOICOIAPI_EMAIL_ADDRESS'); ?>" type="text" name="username" class="full-width has-padding has-border" alt="username" style="width: 140px;" autocomplete="off" /> 
						 		</li>
			                    <li>
			                        <label for="signin-password" class="image-password fa fa-unlock-alt"></label>
			                        <input id="modlgn_passwd" value="" type="password" name="passwd" class="full-width has-padding has-border" style="width: 140px;" alt="password" autocomplete="off" placeholder="<?php echo JText::_('COM_HOICOIAPI_PASSWORD'); ?>" />
			               		</li>
			                    <li class="towdivn">
			                    	<span class="cd-form-bottom-remember"><input type="checkbox" name="remember_me" id="remember_me" /><?php echo JText::_( 'REMEMBER_ME');?> </span>
			                  		<span class="cd-form-bottom-message"><a><?php echo JText::_( 'FORGOT_YOUR_PASSWORD');?></a></span>
			                    </li>
		                   		<input type="submit" name="Submit" class="login-bg full-width"  value="<?php echo JText::_('LOGIN') ?>" onclick="return validatelogin();" /> 
							</ul>

	                    	<!-- <div class="tobtodimdiv">
	                    	<?php echo JText::_( 'DONT_HAVE_ACCOUNT');?><a class="cd-signup-login-form"><?php echo JText::_('CREATE_ACCOUNT');?></a>
	                    </div> -->
	                </div>
            
		
		  			<input type="hidden" name="option" value="com_joomproject" />
		        	<input type="hidden" name="view" value="register" /> 
		  			<input type="hidden" name="task" value="check_login" />
		  			<input type="hidden" name="jelive_url" id="jelive_url" value="<?php echo $url; ?>" />
            
        		</form>
			   <span>
			    	<a href="javascript:void(0);" class="cd-close-form"><?php echo JText::_( '&nbsp;');?></a> 
          		</span>
		  	</div> 
        
        	<div id="cd-reset-password"> <!-- reset password form -->
				<p class="cd-form-message"><?php echo '&nbsp;&nbsp;'.JText::_( 'LOST_YOUR_PASS_HELP_MESSAGE');?></p>

				<form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=reset.request'); ?>" method="post" class="form-validate form-horizontal well cd-form">
					<p class="fieldset">
						<label class="image-replace cd-email" for="reset-email"><?php echo JText::_( 'EMAIL');?></label>
						<input class="full-width has-padding has-border" id="reset-email" required type="email" name="jform[email]" placeholder="<?php echo JText::_( 'ENTER_EMAIL');?>">
					</p>
					<p class="fieldset">
						<input class="full-width has-padding"  type="submit" value="<?php echo JText::_( 'RESET_PASSWORD');?>">
					</p>
                     
                    <div class="tobtodimdiv">
                    <div class="main-nav" style="width:100% !important">
                                <ul>
                                	<li><a class="cd-signin"><?php echo JText::_( 'BACK_TO_SIGN_IN');?></a></li>
                                </ul>
                            </div>
                    </div>
                    <?php echo JHtml::_('form.token'); ?>
				</form>

				<a href="javascript:void(0);" class="cd-close-form"><?php echo JText::_( '&nbsp;');?></a> 
			</div>
		</div>
	</div>
</div>

<input type="hidden" name="option" value="com_joomproject" />

<input type="hidden" name="view" id="view" value="register" />

<input type="hidden" name="task" value="check_login" />

<?php echo JHTML::_( 'form.token' ); ?>

</form>

<?php endif;   ?>

