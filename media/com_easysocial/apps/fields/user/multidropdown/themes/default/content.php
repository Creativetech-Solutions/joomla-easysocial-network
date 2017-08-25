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
?>
<div class="data-field-multitextbox" data-field-multidropdown data-max="<?php echo $params->get('max'); ?>">
	<ul data-field-multidropdown-list class="fd-reset-list">
	<?php
	if (!empty($value)) {
		foreach ($value as $v) {
			
			// check if there have empty value, we skip it
			if (empty($v)) {
				continue;
			}
			
			echo $class->loadTemplate('input', array(
					'inputName' => $inputName,
					'choices' => $choices,
					'value' => $v
				)
			);
		}
	}

	if (empty($count)) {
		echo $class->loadTemplate('input', array(
				'inputName' => $inputName,
				'choices' => $choices,
				'value'	=> ''
			)
		);
	}

 	?>
	</ul>

	<a href="javascript:void(0);" data-field-multidropdown-add 
		<?php if (!(empty($limit) || $count < $limit)) { ?>
			style="display: none;"
		<?php } ?>
	>
	<?php echo JText::_($params->get('add_button_text')); ?></a>
</div>
