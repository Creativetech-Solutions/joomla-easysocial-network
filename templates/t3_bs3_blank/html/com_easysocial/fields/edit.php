<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<div class="form-group <?php echo !empty( $error ) ? 'has-error' : '';?>"
    data-field
    data-field-<?php echo $field->id; ?>
    data-edit-field
    data-edit-field-<?php echo $field->id; ?>
    <?php if( !isset( $options['check'] ) || $options['check'] !== false ) { ?>data-check<?php } ?>
>
    <?php if( $params->get( 'privacy' ) ) {
        //echo $this->includeTemplate( 'site/fields/privacy' );
    } ?>

    <?php if( $params->get( 'display_title' ) ) {
        echo $this->includeTemplate( 'site/fields/title' );
    } ?>
	
    <?php if($field->unique_key == 'JOOMLA_TIMEZONE'){ ?>
        <div class="col-xs-12 col-sm-4 form-input-fields data <?php echo $field->unique_key; ?>" data-content>
            <?php echo $this->includeTemplate( $subNamespace ); ?>
        </div>
    <?php }else{ ?>
    <div class="col-xs-12 col-sm-12 form-input-fields data <?php echo $field->unique_key; ?>" data-content>
        <?php echo $this->includeTemplate( $subNamespace ); ?>
    </div>
    <?php } ?>

    <?php if( !isset( $options['error'] ) || $options['error'] !== false ) {
        echo $this->includeTemplate( 'site/fields/error' );
    } ?>

    <?php if( $params->get( 'display_description' ) ) {
        echo $this->includeTemplate( 'site/fields/description' );
    } ?>
</div>
