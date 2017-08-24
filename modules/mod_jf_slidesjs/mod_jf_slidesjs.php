<?php
/**
 * JF SlidesJS
 * @author		JoomForest.com
 * @email		support@joomforest.com
 * @website		http://www.joomforest.com
 * @copyright	Copyright (C) 2011-2014 JoomForest. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// ini_set('display_errors', 'On');
// error_reporting(E_ALL | E_STRICT);
 
// no direct access
defined('_JEXEC') or die('Restricted access');

// Main Variables
$base = JURI::base();
$assets_path = $base.'modules/mod_jf_slidesjs/assets/';
$jf_doc = JFactory::getDocument();

/* START - FUNCTIONS ==================================================================================================== */

	// Slide #1 params
		$jf_slidesjs_slide_1					= $params->get('jf_slidesjs_slide_1');
		$jf_slidesjs_slide_1_link				= $params->get('jf_slidesjs_slide_1_link');
		$jf_slidesjs_slide_1_link_target		= $params->get('jf_slidesjs_slide_1_link_target');
		$jf_slidesjs_slide_1_caption			= $params->get('jf_slidesjs_slide_1_caption');
		$jf_slidesjs_slide_1_captionPos			= $params->get('jf_slidesjs_slide_1_captionPos');
	// Slide #2 params
		$jf_slidesjs_slide_2					= $params->get('jf_slidesjs_slide_2');
		$jf_slidesjs_slide_2_link				= $params->get('jf_slidesjs_slide_2_link');
		$jf_slidesjs_slide_2_link_target		= $params->get('jf_slidesjs_slide_2_link_target');
		$jf_slidesjs_slide_2_caption			= $params->get('jf_slidesjs_slide_2_caption');
		$jf_slidesjs_slide_2_captionPos			= $params->get('jf_slidesjs_slide_2_captionPos');
	// Slide #3 params
		$jf_slidesjs_slide_3					= $params->get('jf_slidesjs_slide_3');
		$jf_slidesjs_slide_3_link				= $params->get('jf_slidesjs_slide_3_link');
		$jf_slidesjs_slide_3_link_target		= $params->get('jf_slidesjs_slide_3_link_target');
		$jf_slidesjs_slide_3_caption			= $params->get('jf_slidesjs_slide_3_caption');
		$jf_slidesjs_slide_3_captionPos			= $params->get('jf_slidesjs_slide_3_captionPos');
	// Slide #4 params
		$jf_slidesjs_slide_4					= $params->get('jf_slidesjs_slide_4');
		$jf_slidesjs_slide_4_link				= $params->get('jf_slidesjs_slide_4_link');
		$jf_slidesjs_slide_4_link_target		= $params->get('jf_slidesjs_slide_4_link_target');
		$jf_slidesjs_slide_4_caption			= $params->get('jf_slidesjs_slide_4_caption');
		$jf_slidesjs_slide_4_captionPos			= $params->get('jf_slidesjs_slide_4_captionPos');
	// Slide #5 params
		$jf_slidesjs_slide_5					= $params->get('jf_slidesjs_slide_5');
		$jf_slidesjs_slide_5_link				= $params->get('jf_slidesjs_slide_5_link');
		$jf_slidesjs_slide_5_link_target		= $params->get('jf_slidesjs_slide_5_link_target');
		$jf_slidesjs_slide_5_caption			= $params->get('jf_slidesjs_slide_5_caption');
		$jf_slidesjs_slide_5_captionPos			= $params->get('jf_slidesjs_slide_5_captionPos');
	// Slide #6 params
		$jf_slidesjs_slide_6					= $params->get('jf_slidesjs_slide_6');
		$jf_slidesjs_slide_6_link				= $params->get('jf_slidesjs_slide_6_link');
		$jf_slidesjs_slide_6_link_target		= $params->get('jf_slidesjs_slide_6_link_target');
		$jf_slidesjs_slide_6_caption			= $params->get('jf_slidesjs_slide_6_caption');
		$jf_slidesjs_slide_6_captionPos			= $params->get('jf_slidesjs_slide_6_captionPos');
	// Slide #7 params
		$jf_slidesjs_slide_7					= $params->get('jf_slidesjs_slide_7');
		$jf_slidesjs_slide_7_link				= $params->get('jf_slidesjs_slide_7_link');
		$jf_slidesjs_slide_7_link_target		= $params->get('jf_slidesjs_slide_7_link_target');
		$jf_slidesjs_slide_7_caption			= $params->get('jf_slidesjs_slide_7_caption');
		$jf_slidesjs_slide_7_captionPos			= $params->get('jf_slidesjs_slide_7_captionPos');
	// Slide #8 params
		$jf_slidesjs_slide_8					= $params->get('jf_slidesjs_slide_8');
		$jf_slidesjs_slide_8_link				= $params->get('jf_slidesjs_slide_8_link');
		$jf_slidesjs_slide_8_link_target		= $params->get('jf_slidesjs_slide_8_link_target');
		$jf_slidesjs_slide_8_caption			= $params->get('jf_slidesjs_slide_8_caption');
		$jf_slidesjs_slide_8_captionPos			= $params->get('jf_slidesjs_slide_8_captionPos');
	// Slide #9 params
		$jf_slidesjs_slide_9					= $params->get('jf_slidesjs_slide_9');
		$jf_slidesjs_slide_9_link				= $params->get('jf_slidesjs_slide_9_link');
		$jf_slidesjs_slide_9_link_target		= $params->get('jf_slidesjs_slide_9_link_target');
		$jf_slidesjs_slide_9_caption			= $params->get('jf_slidesjs_slide_9_caption');
		$jf_slidesjs_slide_9_captionPos			= $params->get('jf_slidesjs_slide_9_captionPos');
	// Slide #10 params
		$jf_slidesjs_slide_10					= $params->get('jf_slidesjs_slide_10');
		$jf_slidesjs_slide_10_link				= $params->get('jf_slidesjs_slide_10_link');
		$jf_slidesjs_slide_10_link_target		= $params->get('jf_slidesjs_slide_10_link_target');
		$jf_slidesjs_slide_10_caption			= $params->get('jf_slidesjs_slide_10_caption');
		$jf_slidesjs_slide_10_captionPos		= $params->get('jf_slidesjs_slide_10_captionPos');
	// Params
		$jf_slidesjs_ID							= $params->get('jf_slidesjs_ID');
		$jf_slidesjs_Imgs_W						= $params->get('jf_slidesjs_Imgs_W');
		$jf_slidesjs_Imgs_H						= $params->get('jf_slidesjs_Imgs_H');
		$jf_slidesjs_Effect						= $params->get('jf_slidesjs_Effect');
		$jf_slidesjs_Effect_S_S					= $params->get('jf_slidesjs_Effect_S_S');
		$jf_slidesjs_Effect_F_S					= $params->get('jf_slidesjs_Effect_F_S');
		$jf_slidesjs_Interval					= $params->get('jf_slidesjs_Interval');
		$jf_slidesjs_Auto						= $params->get('jf_slidesjs_Auto');
		$jf_slidesjs_Pause						= $params->get('jf_slidesjs_Pause');
		$jf_slidesjs_CaptionAnim				= $params->get('jf_slidesjs_CaptionAnim');
		$jf_slidesjs_CaptionDistance			= $params->get('jf_slidesjs_CaptionDistance');
		$jf_slidesjs_Caption_S					= $params->get('jf_slidesjs_Caption_S');
		$jf_slidesjs_Nav						= $params->get('jf_slidesjs_Nav');
		$jf_slidesjs_Nav_State					= $params->get('jf_slidesjs_Nav_State');
		$jf_slidesjs_Pag						= $params->get('jf_slidesjs_Pag');
		
		$jf_slidesjs_styles						= $params->get('jf_slidesjs_styles');
	// Calling
		// CSS
			$jf_doc->addStyleSheet($assets_path.'jf_slidesjs.min.css');
			$jf_doc->addStyleDeclaration(''.$jf_slidesjs_styles.'');
		// JS
			$jf_doc->addScript($assets_path.'jquery.slides.min.js');

		// SCRIPT
			$jf_doc->addScriptDeclaration('(function($){$(window).load(function(){$("#'.$jf_slidesjs_ID.'").slidesjs({width:'.$jf_slidesjs_Imgs_W.',height:'.$jf_slidesjs_Imgs_H.',play:{effect:"'.$jf_slidesjs_Effect.'",interval:'.$jf_slidesjs_Interval.',auto:'.$jf_slidesjs_Auto.',swap:true,pauseOnHover:'.$jf_slidesjs_Pause.',restartDelay:2500},navigation:{active:'.$jf_slidesjs_Nav.',effect:"'.$jf_slidesjs_Effect.'"},pagination:{active:'.$jf_slidesjs_Pag.',effect:"'.$jf_slidesjs_Effect.'"},effect:{slide:{speed:'.$jf_slidesjs_Effect_S_S.'},fade:{speed:'.$jf_slidesjs_Effect_F_S.',crossfade:true}},callback:{loaded:function(number){$(".jf_slidesjs").css({opacity:0});setTimeout(function(){$(".jf_slidesjs").animate({opacity:1});$("#jf_slidesjs").removeClass("loaded")},1000)},start:function(number){$(".jf_slidesjs .caption").animate({'.$jf_slidesjs_CaptionAnim.':'.$jf_slidesjs_CaptionDistance.',opacity:0},10)},complete:function(number){$(".jf_slidesjs .caption").animate({'.$jf_slidesjs_CaptionAnim.':0,opacity:1},'.$jf_slidesjs_Caption_S.')}}})})})(jQuery);');
			
			if ($jf_slidesjs_Nav_State) {
				$jf_doc->addStyleDeclaration('.jf_slidesjs .slidesjs-previous{left:-50px}.jf_slidesjs:hover .slidesjs-previous{left:10px;}.jf_slidesjs .slidesjs-next{right:-50px}.jf_slidesjs:hover .slidesjs-next{right:10px}');
			}
/*   END - FUNCTIONS ==================================================================================================== */


$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
require JModuleHelper::getLayoutPath('mod_jf_slidesjs', $params->get('layout', 'default'));