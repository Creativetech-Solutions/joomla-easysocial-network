<?php

/**

* @package Affiliate

* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.

* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php

* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com

* Visit : http://www.joomlaextensions.co.in/

**/ 

defined('_JEXEC') or die('Restricted access'); 
jimport( 'joomla.application.component.controller' );
JHTML::_('behavior.modal');
$uri = JURI::getInstance();
$url= $uri->root();
$option	= JRequest::getVar('option', '','request','string');
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
$userdetail = mod_jeloginHelper::getUserdetails($user->id);
$profilePicture = mod_jeloginHelper::getProfilepic($user->id);
$image_path_user= $url.'images/watermarks/';
?>

<link href="<?php echo $url.'modules/mod_jelogin/css/popup_style.css'; ?>" rel="stylesheet" />
<script src="<?php echo $url.'modules/mod_jelogin/js/main_popup.js'; ?>" type="text/javascript"></script>
 <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css" />

<script type="text/javascript">
var old_div = "";



jQuery.noConflict();
jQuery(document).ready(function(){

  
    //start listing questions
    jQuery(document).click(function(e) {
        if(jQuery(e.target).attr('class')!=undefined)
        {
            if(jQuery(e.target).attr('class')!="user_login_menus" && jQuery(e.target).attr('class')!="user_profile_pic" && jQuery(e.target).attr('class')!="namespan" && !jQuery(e.target).attr('class').match("fa-user"))
            {
                jQuery("#prodash11").hide();
                //jQuery('.profilediv').hide();
            }
        }
    });
});

function profatch1(val)
{
	jQuery('.profilediv').slideToggle();
	/*if(val == "1")
	{
		//jQuery('.profilediv').show();
	}
	if(val == "2")
	{
		//jQuery('.profilediv').hide();
	}*/
	/*if(document.getElementById("prodash11").style.display=='none')
	{
		jQuery('.profilediv').show();
	} 
	else 
	{
		jQuery('.profilediv').hide();
	}*/
	
	/*if(old_div!="" && old_div!=active_div)
	document.getElementById(old_div).style.display='none';
	old_div = active_div;	*/
}

</script>


<?php  if($type == 'logout') : ?>
 
<div class="main-logindiv">

	<form action="<?php echo JRoute::_('index.php?option=com_joomproject&view=registration&task=logout');?>" method="post" name="login" id="form-login">

	<div class="allicontopprofle">
		<div class="maintoolbarcust">
			<?php

				if($option == 'com_community')
				{
					$actionsModel = JControllerLegacy::getInstance('CommunityBase', 'CommunityBaseController'); 
				    $view = $actionsModel->getView(JString::strtolower('profile'));

					// Do not rely on the toolbar to include necessary css or javascripts.
					$view->attachHeaders();
			
					// Display the site's toolbar.
					$view->showToolbar();
				}
				//$toolbar_lib = CToolbarLibrary::getInstance();
            	//echo $toolbar_lib->getHTML(_showMiniHeaderUser);
			?>
		</div>
		<ul class="user_login_menus">	
   			<li class="user_profile_pic">
   				<a href="javascript:void(0);" onclick="profatch1(1);">
   				
		            <?php 
		            	
			            if($profilePicture!='')
			            { 
			              if(file_exists(JPATH_ROOT.'/'.$profilePicture))
			              {
			                 echo '<img class="fa-user" src="'.$profilePicture.'" alt="'.$user->name.'" />';
			              }
			              else 
			              {
			              	$userpic='default-male-avatar.png';
			              	echo '<img class="fa-user" src="'.$image_path_user.$userpic.'" alt="'.$user->name.'" />';
			              }
			            }
			            else 
			           	{
			           		$userpic='default-male-avatar.png';
			           		echo '<img class="fa-user" src="'.$image_path_user.$userpic.'" alt="'.$user->name.'" />';
			           	} 
		            ?>
           			 
           			 <?php 
           			 	/*if($userdetail->memberType==1)
           			 	{
           			 		if($userdetail->artistname != '')
           			 		{
           			 			echo '<span class="namespan"> <label title="'.$userdetail->artistname.'">'.$userdetail->artistname.'</label><b>...</b></span>';
           			 		}
           			 		else if($userdetail->firstName != '')
           			 		{
           			 			echo '<span class="namespan"><label title="'.$userdetail->firstName.' '.$userdetail->lastName.'">'.$userdetail->firstName.' '.$userdetail->lastName.'</label><b>...</b></span>';
           			 		}
           			 		else
           			 		{
           			 			echo '<span class="namespan"><label title="'.$user->name.'">'.$user->name.'</label><b>...</b></span>';
           			 		}
           			 	}
           			 	else if($userdetail->memberType==2)
           			 	{	
           			 		if($userdetail->businessname !='')
           			 		{
           			 			echo '<span class="namespan"><label title="'.$userdetail->businessname.'">'.$userdetail->businessname.'</label><b>...</b></span>';
           			 		}
           			 		else if($userdetail->firstName != '')
           			 		{
           			 			echo '<span class="namespan"><label title="'.$userdetail->firstName.' '.$userdetail->lastName.'">'.$userdetail->firstName.' '.$userdetail->lastName.'</label><b>...</b></span>';
           			 		}
           			 		else
           			 		{
           			 			echo '<span class="namespan"><label title="'.$user->name.'">'.$user->name.'</label><b>...</b></span>';
           			 		}
           			 	}
           			 	else
           			 	{
           			 		if($userdetail->firstName != '')
           			 		{
           			 			echo '<span class="namespan"><label title="'.$userdetail->firstName.' '.$userdetail->lastName.'">'.$userdetail->firstName.' '.$userdetail->lastName.'</label><b>...</b></span>';
           			 		}
           			 		else
           			 		{
           			 			echo '<span class="namespan"><label title="'.$user->name.'">'.$user->name.'</label><b>...</b></span>';
           			 		}
           			 		
           			 	}*/

           			 ?>
           			 <span class="namespan"> &nbsp;</span>
           		</a>
           	</li>
           	<div class="profilediv" id="prodash11" style="display:none;" >
	   		
	    	<ul class="user_profile_ul">
		   		<li class="profile_icon"> 
		   			<a href="<?php echo JRoute::_('index.php?option=com_community&view=profile&page=overview');?>"> <i class="fa fa-user">&nbsp;</i> <?php echo JText::_( 'MOD_JELOGIN_MY_PROFILE'); ?></a></li>
		        <?php /*?><li class="edit_profile_icon"><i class="fa fa-pencil-square-o">&nbsp;</i> <a href="<?php echo JRoute::_('index.php?option=com_hoicoiapi&view=userprofile&edit=1');?>"><?php echo JText::_( 'Edit Profile'); ?></a></li><?php */?>
                <li><a href="<?php echo JRoute::_('index.php?option=com_community&view=profile&newsfeed=1&page=newsfeed');?>"><i aria-hidden="true" class="fa fa-newspaper-o">&nbsp;</i> <?php echo JText::_( 'MOD_JELOGIN_MY_NEWSFEED'); ?></a></li>
                
                <li><a href="<?php echo JRoute::_('index.php?option=com_community&view=inbox');?>"><i aria-hidden="true" class="fa fa-envelope">&nbsp;</i> <?php echo JText::_( 'MOD_JELOGIN_MESSAGES'); ?></a></li>
                
                <li><a href="<?php echo JRoute::_('index.php?option=com_community&view=profile&task=accountsetting');?>"><i aria-hidden="true" class="fa fa-cog">&nbsp;</i><?php echo JText::_( 'MOD_JELOGIN_ACCOUNT_SETTING'); ?></a></li>
                <li><a href="<?php echo JRoute::_('index.php?option=com_community&view=profile&task=edit');?>"><i aria-hidden="true" class="fa fa-pencil-square-o"></i><?php echo JText::_('COM_COMMUNITY_PROFILE_EDIT');?></a></li>
                <li><a href="#"><i aria-hidden="true" class="fa fa-question-circle">&nbsp;</i> <?php echo JText::_( 'MOD_JELOGIN_HELP'); ?></a></li>
                
               <li><a href="#"><i aria-hidden="true" class="fa fa-star">&nbsp;</i> <?php echo JText::_( 'MOD_JELOGIN_UPGRADE_TO_PREMIUM'); ?></a></li>
		        <li class="profile_logout"><a href="#"><i aria-hidden="true" class="fa fa-sign-out">&nbsp;</i> <input type="submit" name="Submit" class="buttonlogout" value="<?php echo JText::_( 'LOGOUT'); ?>" /></a></li>
	        </ul>
	    </div>
        </ul>
        <?php echo JHtml::_('form.token'); ?>
	    
  	</div>  


		<input type="hidden" name="option" value="com_joomproject" />

		<input type="hidden" name="view" id="view" value="registration" />

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
			                       <input type="text" name="username" id="nameuser" placeholder="<?php echo JText::_('COM_JOOMPROJECT_USERNAME_PLACEHOLDER'); ?>" value=""/> 
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
		        	<input type="hidden" name="view" value="registration" /> 
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

<input type="hidden" name="view" id="view" value="registration" />

<input type="hidden" name="task" value="check_login" />

<?php echo JHTML::_( 'form.token' ); ?>

</form>

<?php endif;   ?>

<script>
	//jQuery.noConflict();	
		var loginli=document.getElementById("logindiv");
		if(loginli!=null)
			loginli.remove();
	function validatelogin()
	{ 
		
		
		var uname = document.getElementById("nameuser").value;
	//	alert(uname);return false;
		var passwd = document.getElementById("modlgn_passwd").value;
		if(uname=='')
		{
			alert('<?php echo JText::_("PLEASE_ENTER_USERNAME");?>');
			document.getElementById("nameuser").focus();
			return false;

		}

		if(passwd=='')
		{
			alert('<?php echo JText::_("PLEASE_ENTER_PASSWORD");?>');
			document.getElementById("modlgn_passwd").focus();
			return false;
		}
 		
	}
</script>
