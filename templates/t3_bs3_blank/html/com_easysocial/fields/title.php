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
<!-- Field title -->
<label class="col-sm-12 control-label" for="es-fields-<?php echo $field->id;?>">
	<?php if( ( isset( $options['required'] ) && $options['required'] ) || ( !isset( $options['required'] ) && $field->isRequired() ) ){ ?>
	<span><?php echo JText::_( 'COM_EASYSOCIAL_REGISTRATION_REQUIRED_SYMBOL' );?></span>
	<?php } ?>

	<?php echo JText::_( $params->get( 'title' ) ); ?>:
</label>