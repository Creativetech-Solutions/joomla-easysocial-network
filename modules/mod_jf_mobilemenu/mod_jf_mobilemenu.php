<?php
/**
 * @package 	JF Mobile Menu
 * @author		JoomForest.com
 * @email		support@joomforest.com
 * @website		http://www.joomforest.com
 * @copyright	Copyright (C) 2011-2016 JoomForest.com, All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

defined('_JEXEC') or die;

// ini_set('display_errors', 'On');
// error_reporting(E_ALL | E_STRICT);

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

JHtml::_('jquery.framework');

$list		= ModJFMMHelper::getList($params);
$base		= ModJFMMHelper::getBase($params);
$active		= ModJFMMHelper::getActive($params);
$active_id 	= $active->id;
$path		= $base->tree;

$showAll	= $params->get('showAllChildren');
$class_sfx	= htmlspecialchars($params->get('class_sfx'));


// Main Variables
$juri = JURI::base();
$assets_path = $juri.'modules/mod_jf_mobilemenu/assets/';
$jf_doc = JFactory::getDocument();

/* START - FUNCTIONS ==================================================================================================== */
	// Params
		$jf_mm_i_1_ID						= $params->get('jf_mm_i_1_ID');
		$jf_mm_i_1_Icon						= $params->get('jf_mm_i_1_Icon');
		if(!$params->get('jf_mm_i_1_Img') == ''){$jf_mm_i_1_Img = $juri.$params->get('jf_mm_i_1_Img');} else {$jf_mm_i_1_Img = '';}
		$jf_mm_i_1_Style					= $params->get('jf_mm_i_1_Style');
		
		$jf_mm_i_2_ID						= $params->get('jf_mm_i_2_ID');
		$jf_mm_i_2_Icon						= $params->get('jf_mm_i_2_Icon');
		if(!$params->get('jf_mm_i_2_Img') == ''){$jf_mm_i_2_Img = $juri.$params->get('jf_mm_i_2_Img');} else {$jf_mm_i_2_Img = '';}
		$jf_mm_i_2_Style					= $params->get('jf_mm_i_2_Style');
		
		$jf_mm_i_3_ID						= $params->get('jf_mm_i_3_ID');
		$jf_mm_i_3_Icon						= $params->get('jf_mm_i_3_Icon');
		if(!$params->get('jf_mm_i_3_Img') == ''){$jf_mm_i_3_Img = $juri.$params->get('jf_mm_i_3_Img');} else {$jf_mm_i_3_Img = '';}
		$jf_mm_i_3_Style					= $params->get('jf_mm_i_3_Style');
		
		$jf_mm_i_4_ID						= $params->get('jf_mm_i_4_ID');
		$jf_mm_i_4_Icon						= $params->get('jf_mm_i_4_Icon');
		if(!$params->get('jf_mm_i_4_Img') == ''){$jf_mm_i_4_Img = $juri.$params->get('jf_mm_i_4_Img');} else {$jf_mm_i_4_Img = '';}
		$jf_mm_i_4_Style					= $params->get('jf_mm_i_4_Style');
		
		$jf_mm_i_5_ID						= $params->get('jf_mm_i_5_ID');
		$jf_mm_i_5_Icon						= $params->get('jf_mm_i_5_Icon');
		if(!$params->get('jf_mm_i_5_Img') == ''){$jf_mm_i_5_Img = $juri.$params->get('jf_mm_i_5_Img');} else {$jf_mm_i_5_Img = '';}
		$jf_mm_i_5_Style					= $params->get('jf_mm_i_5_Style');
		
		$jf_mm_i_6_ID						= $params->get('jf_mm_i_6_ID');
		$jf_mm_i_6_Icon						= $params->get('jf_mm_i_6_Icon');
		if(!$params->get('jf_mm_i_6_Img') == ''){$jf_mm_i_6_Img = $juri.$params->get('jf_mm_i_6_Img');} else {$jf_mm_i_6_Img = '';}
		$jf_mm_i_6_Style					= $params->get('jf_mm_i_6_Style');
		
		$jf_mm_i_7_ID						= $params->get('jf_mm_i_7_ID');
		$jf_mm_i_7_Icon						= $params->get('jf_mm_i_7_Icon');
		if(!$params->get('jf_mm_i_7_Img') == ''){$jf_mm_i_7_Img = $juri.$params->get('jf_mm_i_7_Img');} else {$jf_mm_i_7_Img = '';}
		$jf_mm_i_7_Style					= $params->get('jf_mm_i_7_Style');
		
		$jf_mm_i_8_ID						= $params->get('jf_mm_i_8_ID');
		$jf_mm_i_8_Icon						= $params->get('jf_mm_i_8_Icon');
		if(!$params->get('jf_mm_i_8_Img') == ''){$jf_mm_i_8_Img = $juri.$params->get('jf_mm_i_8_Img');} else {$jf_mm_i_8_Img = '';}
		$jf_mm_i_8_Style					= $params->get('jf_mm_i_8_Style');
		
		$jf_mm_i_9_ID						= $params->get('jf_mm_i_9_ID');
		$jf_mm_i_9_Icon						= $params->get('jf_mm_i_9_Icon');
		if(!$params->get('jf_mm_i_9_Img') == ''){$jf_mm_i_9_Img = $juri.$params->get('jf_mm_i_9_Img');} else {$jf_mm_i_9_Img = '';}
		$jf_mm_i_9_Style					= $params->get('jf_mm_i_9_Style');
		
		$jf_mm_i_10_ID						= $params->get('jf_mm_i_10_ID');
		$jf_mm_i_10_Icon					= $params->get('jf_mm_i_10_Icon');
		if(!$params->get('jf_mm_i_10_Img') == ''){$jf_mm_i_10_Img = $juri.$params->get('jf_mm_i_10_Img');} else {$jf_mm_i_10_Img = '';}
		$jf_mm_i_10_Style					= $params->get('jf_mm_i_10_Style');
		
		$jf_mm_i_11_ID						= $params->get('jf_mm_i_11_ID');
		$jf_mm_i_11_Icon					= $params->get('jf_mm_i_11_Icon');
		if(!$params->get('jf_mm_i_11_Img') == ''){$jf_mm_i_11_Img = $juri.$params->get('jf_mm_i_11_Img');} else {$jf_mm_i_11_Img = '';}
		$jf_mm_i_11_Style					= $params->get('jf_mm_i_11_Style');
		
		$jf_mm_i_12_ID						= $params->get('jf_mm_i_12_ID');
		$jf_mm_i_12_Icon					= $params->get('jf_mm_i_12_Icon');
		if(!$params->get('jf_mm_i_12_Img') == ''){$jf_mm_i_12_Img = $juri.$params->get('jf_mm_i_12_Img');} else {$jf_mm_i_12_Img = '';}
		$jf_mm_i_12_Style					= $params->get('jf_mm_i_12_Style');
		
		$jf_mm_i_13_ID						= $params->get('jf_mm_i_13_ID');
		$jf_mm_i_13_Icon					= $params->get('jf_mm_i_13_Icon');
		if(!$params->get('jf_mm_i_13_Img') == ''){$jf_mm_i_13_Img = $juri.$params->get('jf_mm_i_13_Img');} else {$jf_mm_i_13_Img = '';}
		$jf_mm_i_13_Style					= $params->get('jf_mm_i_13_Style');
		
		$jf_mm_i_14_ID						= $params->get('jf_mm_i_14_ID');
		$jf_mm_i_14_Icon					= $params->get('jf_mm_i_14_Icon');
		if(!$params->get('jf_mm_i_14_Img') == ''){$jf_mm_i_14_Img = $juri.$params->get('jf_mm_i_14_Img');} else {$jf_mm_i_14_Img = '';}
		$jf_mm_i_14_Style					= $params->get('jf_mm_i_14_Style');
		
		$jf_mm_i_15_ID						= $params->get('jf_mm_i_15_ID');
		$jf_mm_i_15_Icon					= $params->get('jf_mm_i_15_Icon');
		if(!$params->get('jf_mm_i_15_Img') == ''){$jf_mm_i_15_Img = $juri.$params->get('jf_mm_i_15_Img');} else {$jf_mm_i_15_Img = '';}
		$jf_mm_i_15_Style					= $params->get('jf_mm_i_15_Style');
		
		$jf_mm_i_16_ID						= $params->get('jf_mm_i_16_ID');
		$jf_mm_i_16_Icon					= $params->get('jf_mm_i_16_Icon');
		if(!$params->get('jf_mm_i_16_Img') == ''){$jf_mm_i_16_Img = $juri.$params->get('jf_mm_i_16_Img');} else {$jf_mm_i_16_Img = '';}
		$jf_mm_i_16_Style					= $params->get('jf_mm_i_16_Style');
		
		$jf_mm_i_17_ID						= $params->get('jf_mm_i_17_ID');
		$jf_mm_i_17_Icon					= $params->get('jf_mm_i_17_Icon');
		if(!$params->get('jf_mm_i_17_Img') == ''){$jf_mm_i_17_Img = $juri.$params->get('jf_mm_i_17_Img');} else {$jf_mm_i_17_Img = '';}
		$jf_mm_i_17_Style					= $params->get('jf_mm_i_17_Style');
		
		$jf_mm_i_18_ID						= $params->get('jf_mm_i_18_ID');
		$jf_mm_i_18_Icon					= $params->get('jf_mm_i_18_Icon');
		if(!$params->get('jf_mm_i_18_Img') == ''){$jf_mm_i_18_Img = $juri.$params->get('jf_mm_i_18_Img');} else {$jf_mm_i_18_Img = '';}
		$jf_mm_i_18_Style					= $params->get('jf_mm_i_18_Style');
		
		$jf_mm_i_19_ID						= $params->get('jf_mm_i_19_ID');
		$jf_mm_i_19_Icon					= $params->get('jf_mm_i_19_Icon');
		if(!$params->get('jf_mm_i_19_Img') == ''){$jf_mm_i_19_Img = $juri.$params->get('jf_mm_i_19_Img');} else {$jf_mm_i_19_Img = '';}
		$jf_mm_i_19_Style					= $params->get('jf_mm_i_19_Style');
		
		$jf_mm_i_20_ID						= $params->get('jf_mm_i_20_ID');
		$jf_mm_i_20_Icon					= $params->get('jf_mm_i_20_Icon');
		if(!$params->get('jf_mm_i_20_Img') == ''){$jf_mm_i_20_Img = $juri.$params->get('jf_mm_i_20_Img');} else {$jf_mm_i_20_Img = '';}
		$jf_mm_i_20_Style					= $params->get('jf_mm_i_20_Style');
		
		
	
	// Calling
		// CSS
			$jf_doc->addStyleSheet($assets_path.'jf_mm.min.css');
			
		// JS
			$jf_doc->addScript($assets_path.'jquery.jf_multilevelpushmenu.min.js');
			$jf_doc->addScript($assets_path.'jf_mm.min.js');
			$jf_doc->addScriptDeclaration('
				var jf_mm_icons = [ 
					'.(($jf_mm_i_1_ID)?'{itemID:"-"+"'.$jf_mm_i_1_ID.'",fa_icon:"'.$jf_mm_i_1_Icon.'",img:"'.$jf_mm_i_1_Img.'",style:"'.$jf_mm_i_1_Style.'"}':'{itemID:"-"+"",fa_icon:"",img:"",style:""}').'
					'.(($jf_mm_i_2_ID)?',{itemID:"-"+"'.$jf_mm_i_2_ID.'",fa_icon:"'.$jf_mm_i_2_Icon.'",img:"'.$jf_mm_i_2_Img.'",style:"'.$jf_mm_i_2_Style.'"}':"").'
					'.(($jf_mm_i_3_ID)?',{itemID:"-"+"'.$jf_mm_i_3_ID.'",fa_icon:"'.$jf_mm_i_3_Icon.'",img:"'.$jf_mm_i_3_Img.'",style:"'.$jf_mm_i_3_Style.'"}':"").'
					'.(($jf_mm_i_4_ID)?',{itemID:"-"+"'.$jf_mm_i_4_ID.'",fa_icon:"'.$jf_mm_i_4_Icon.'",img:"'.$jf_mm_i_4_Img.'",style:"'.$jf_mm_i_4_Style.'"}':"").'
					'.(($jf_mm_i_5_ID)?',{itemID:"-"+"'.$jf_mm_i_5_ID.'",fa_icon:"'.$jf_mm_i_5_Icon.'",img:"'.$jf_mm_i_5_Img.'",style:"'.$jf_mm_i_5_Style.'"}':"").'
					'.(($jf_mm_i_6_ID)?',{itemID:"-"+"'.$jf_mm_i_6_ID.'",fa_icon:"'.$jf_mm_i_6_Icon.'",img:"'.$jf_mm_i_6_Img.'",style:"'.$jf_mm_i_6_Style.'"}':"").'
					'.(($jf_mm_i_7_ID)?',{itemID:"-"+"'.$jf_mm_i_7_ID.'",fa_icon:"'.$jf_mm_i_7_Icon.'",img:"'.$jf_mm_i_7_Img.'",style:"'.$jf_mm_i_7_Style.'"}':"").'
					'.(($jf_mm_i_8_ID)?',{itemID:"-"+"'.$jf_mm_i_8_ID.'",fa_icon:"'.$jf_mm_i_8_Icon.'",img:"'.$jf_mm_i_8_Img.'",style:"'.$jf_mm_i_8_Style.'"}':"").'
					'.(($jf_mm_i_9_ID)?',{itemID:"-"+"'.$jf_mm_i_9_ID.'",fa_icon:"'.$jf_mm_i_9_Icon.'",img:"'.$jf_mm_i_9_Img.'",style:"'.$jf_mm_i_9_Style.'"}':"").'
					'.(($jf_mm_i_10_ID)?',{itemID:"-"+"'.$jf_mm_i_10_ID.'",fa_icon:"'.$jf_mm_i_10_Icon.'",img:"'.$jf_mm_i_10_Img.'",style:"'.$jf_mm_i_10_Style.'"}':"").'
					'.(($jf_mm_i_11_ID)?',{itemID:"-"+"'.$jf_mm_i_11_ID.'",fa_icon:"'.$jf_mm_i_11_Icon.'",img:"'.$jf_mm_i_11_Img.'",style:"'.$jf_mm_i_11_Style.'"}':"").'
					'.(($jf_mm_i_12_ID)?',{itemID:"-"+"'.$jf_mm_i_12_ID.'",fa_icon:"'.$jf_mm_i_12_Icon.'",img:"'.$jf_mm_i_12_Img.'",style:"'.$jf_mm_i_12_Style.'"}':"").'
					'.(($jf_mm_i_13_ID)?',{itemID:"-"+"'.$jf_mm_i_13_ID.'",fa_icon:"'.$jf_mm_i_13_Icon.'",img:"'.$jf_mm_i_13_Img.'",style:"'.$jf_mm_i_13_Style.'"}':"").'
					'.(($jf_mm_i_14_ID)?',{itemID:"-"+"'.$jf_mm_i_14_ID.'",fa_icon:"'.$jf_mm_i_14_Icon.'",img:"'.$jf_mm_i_14_Img.'",style:"'.$jf_mm_i_14_Style.'"}':"").'
					'.(($jf_mm_i_15_ID)?',{itemID:"-"+"'.$jf_mm_i_15_ID.'",fa_icon:"'.$jf_mm_i_15_Icon.'",img:"'.$jf_mm_i_15_Img.'",style:"'.$jf_mm_i_15_Style.'"}':"").'
					'.(($jf_mm_i_16_ID)?',{itemID:"-"+"'.$jf_mm_i_16_ID.'",fa_icon:"'.$jf_mm_i_16_Icon.'",img:"'.$jf_mm_i_16_Img.'",style:"'.$jf_mm_i_16_Style.'"}':"").'
					'.(($jf_mm_i_17_ID)?',{itemID:"-"+"'.$jf_mm_i_17_ID.'",fa_icon:"'.$jf_mm_i_17_Icon.'",img:"'.$jf_mm_i_17_Img.'",style:"'.$jf_mm_i_17_Style.'"}':"").'
					'.(($jf_mm_i_18_ID)?',{itemID:"-"+"'.$jf_mm_i_18_ID.'",fa_icon:"'.$jf_mm_i_18_Icon.'",img:"'.$jf_mm_i_18_Img.'",style:"'.$jf_mm_i_18_Style.'"}':"").'
					'.(($jf_mm_i_19_ID)?',{itemID:"-"+"'.$jf_mm_i_19_ID.'",fa_icon:"'.$jf_mm_i_19_Icon.'",img:"'.$jf_mm_i_19_Img.'",style:"'.$jf_mm_i_19_Style.'"}':"").'
					'.(($jf_mm_i_20_ID)?',{itemID:"-"+"'.$jf_mm_i_20_ID.'",fa_icon:"'.$jf_mm_i_20_Icon.'",img:"'.$jf_mm_i_20_Img.'",style:"'.$jf_mm_i_20_Style.'"}':"").'
				];
				jQuery(document).ready(function($){$("#jf_mm_menu").jf_mm_menu()});
			');
/*   END - FUNCTIONS ==================================================================================================== */

/* START - SECONDRY FUNCTIONS ==================================================================================================== */
	// Params
		$jf_mm_fa							= $params->get('jf_mm_fa');
		$jf_fa_Sheet						= $params->get('jf_fa_Sheet','');
		$jf_mm_device						= $params->get('jf_mm_device');
		$jf_mm_direction					= $params->get('jf_mm_direction');
		$jf_mm_title						= $params->get('jf_mm_title');
		$jf_mm_btn							= $params->get('jf_mm_btn');
		$jf_mm_back_txt						= $params->get('jf_mm_back_txt');
		$jf_mm_back_pos						= $params->get('jf_mm_back_pos');
		$jf_mm_close						= $params->get('jf_mm_close');
		$jf_mm_styles						= $params->get('jf_mm_styles');
		
		$jf_mm_color_1						= $params->get('jf_mm_color_1');
		$jf_mm_color_2						= $params->get('jf_mm_color_2');
		$jf_mm_color_3						= $params->get('jf_mm_color_3');
		$jf_mm_color_4						= $params->get('jf_mm_color_4');
		$jf_mm_color_5						= $params->get('jf_mm_color_5');
		$jf_mm_color_6						= $params->get('jf_mm_color_6');
		$jf_mm_color_7						= $params->get('jf_mm_color_7');
		$jf_mm_color_8						= $params->get('jf_mm_color_8');
		$jf_mm_color_9						= $params->get('jf_mm_color_9');
		$jf_mm_color_10						= $params->get('jf_mm_color_10');
		$jf_mm_color_11						= $params->get('jf_mm_color_11');
	// FUNCS
		if ($jf_mm_fa) {
			$jf_doc->addStyleSheet($jf_fa_Sheet);
			// $jf_doc->addScriptDeclaration('alert("enabled fontawesome");');
		}
		if ($jf_mm_device == '2') {
			$jf_doc->addStyleDeclaration('@media(max-width:1024px){.jf_mm_trigger,#jf_mm_menu{display:block}}');
		} elseif ($jf_mm_device == '3') {
			$jf_doc->addStyleDeclaration('@media(max-width:768px){.jf_mm_trigger,#jf_mm_menu{display:block}}');
		} elseif ($jf_mm_device == '4') {
			$jf_doc->addStyleDeclaration('@media(max-width:568px){.jf_mm_trigger,#jf_mm_menu{display:block}}');
		} elseif ($jf_mm_device == '5') {
			$jf_doc->addStyleDeclaration('@media(max-width:320px){.jf_mm_trigger,#jf_mm_menu{display:block}}');
		} else {
			$jf_doc->addStyleDeclaration('.jf_mm_trigger,#jf_mm_menu{display:block}');
		}
		if ($jf_mm_direction) {
			$jf_doc->addScriptDeclaration('var jf_mm_direction = "rtl";jQuery(document).ready(function($){$("#jf_mm_menu,.jf_mm_trigger").addClass("jf_mm_rtl")});');
		} else {
			$jf_doc->addScriptDeclaration('var jf_mm_direction = "ltr";');
		}
		$jf_doc->addScriptDeclaration('var jf_mm_backBtnTxt = "'.$jf_mm_back_txt.'";');
		if ($jf_mm_back_pos) {
			$jf_doc->addScriptDeclaration('!function(n){n(window).load(function(){n(".backItemClass").each(function(){n(this).next().append("<li class=\"new_back\"></li>");var a=n(this),e=n(this).next().children(".new_back");e.append(a)})})}(jQuery);');
			/* UNCOMPRESSED
				(function($){$(window).load(function(){
					$(".backItemClass").each(function() {
						$(this).next().append("<li class=\"new_back\"></li>");
						var back_Tag =  $(this);
						var childrenCHOnly = $(this).next().children(".new_back");
						childrenCHOnly.append(back_Tag);
					});
				})})(jQuery);
			*/
			// $jf_doc->addScriptDeclaration('jQuery(document).ready(function(e){e(".backItemClass").each(function(){e(this).next().append("<li class=\"new_back\"></li>");var n=e(this),a=e(this).next().children(".new_back");a.append(n)})});');
			/* UNCOMPRESSED
				jQuery(document).ready(function($){
					$(".backItemClass").each(function() {
						$(this).next().append("<li class=\"new_back\"></li>");
						var back_Tag =  $(this);
						var childrenCHOnly = $(this).next().children(".new_back");
						childrenCHOnly.append(back_Tag);
					});
				});;
			*/
		}
		$jf_doc->addStyleDeclaration(''.$jf_mm_styles.'');

	// COLORS
		$jf_doc->addStyleDeclaration('
			.jf_mm_trigger{background-color:'.$jf_mm_color_1.';color:'.$jf_mm_color_2.'}
			.jf_mm_wrapper .levelHolderClass,.jf_mm_wrapper .jf_mm_inactive{background-color:'.$jf_mm_color_3.'}
			.jf_mm_wrapper li{background-color:'.$jf_mm_color_4.'}
			.jf_mm_wrapper li:hover{background-color:'.$jf_mm_color_5.'}
			.jf_mm_wrapper .backItemClass{background-color:'.$jf_mm_color_6.'}
			.jf_mm_wrapper .backItemClass:hover{background-color:'.$jf_mm_color_7.'}
			.jf_mm_wrapper li,.jf_mm_wrapper li:last-child,.jf_mm_wrapper .backItemClass{border-color:'.$jf_mm_color_8.'}
			.jf_mm_wrapper h2{color:'.$jf_mm_color_9.'}
			.jf_mm_wrapper a,.jf_mm_wrapper a:hover{color:'.$jf_mm_color_10.'}
			.jf_mm_wrapper .ltr,.jf_mm_wrapper .rtl{-webkit-box-shadow:5px 0 5px -5px '.$jf_mm_color_11.';-moz-box-shadow:5px 0 5px -5px '.$jf_mm_color_11.';box-shadow:5px 0 5px -5px '.$jf_mm_color_11.';}
		');
		$jf_doc->addStyleDeclaration('#jf_mm_menu.jf_hidden{display:none!important}');
		$jf_doc->addScriptDeclaration('!function(n){n(window).load(function(){n("#jf_mm_menu").removeClass("jf_hidden")})}(jQuery);');
/*   END - SECONDRY FUNCTIONS ==================================================================================================== */


if (count($list))
{
	require JModuleHelper::getLayoutPath('mod_jf_mobilemenu', $params->get('layout', 'default'));
}
