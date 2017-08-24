<?php 
/**
* @package   JE communitysociallinks
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

defined('_JEXEC') or die('Restricted access'); 

$doc 	=  JFactory::getDocument();
$user 	=  JFactory::getUser();
$uri 	=  JFactory::getURI();
$url	= $uri->root();
$item 	= JRequest::getVar('Itemid');



?>

	<div class="car_listbox">
	    <h3> Social Links </h3>

		<div class="maindivlist">
			<span class="mysociallink"> 

				<?php
				if($community->faceboocUrl != '')
				{ ?>
					 <a href= "<?php echo $community->faceboocUrl; ?>" target="_blank"><i aria-hidden="true" class="fa fa-facebook"></i></a>
				<?php	
				}
				else
				{ ?>
					 <!-- <a href= "javascript:void(0);" ><i aria-hidden="true" class="fa fa-facebook"></i></a> -->
				<?php
				}

				if($community->twitterUrl != '')
				{?>
					 <a href="<?php echo $community->twitterUrl; ?>" target="_blank"><i aria-hidden="true" class="fa fa-twitter"></i></a>

				<?php
				}
				else
				{ ?>
					 <!-- <a href="javascript:void(0);"><i aria-hidden="true" class="fa fa-twitter"></i></a> -->
				<?php
				}?>
 

				<!-- <a href="javascript:void(0);"> <i aria-hidden="true" class="fa fa-instagram"></i></a>
				<a href="javascript:void(0);"><i aria-hidden="true" class="fa fa-youtube-play"></i></a> -->
			</span>

		    <!-- <span class="mysociallinkedit">
			  <a href="<?php echo JRoute::_('index.php?option=com_community&view=profile&task=edit');?>"><i aria-hidden="true" class="fa fa-pencil-square-o"></i></a>
			</span> -->
	  	</div>
	  	
	</div>



