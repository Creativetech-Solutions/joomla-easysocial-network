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
if(isset($project)):
?>
EasySocial.require()
.script('<?php echo rtrim( JURI::root() , ' / ' );?>/media/com_easysocial/apps/user/projects/assets/js/projects.js')
.done(function ($)
{
        //console.log('loaded');
        $('[data-project-edit]').addController('EasySocial.Controller.Canvas.User.Apps.Project.Edit', {
            userid: <?php echo $this->my->id; ?>
        });
        
        $('[data-field-project-image]').addController('EasySocial.Controller.Canvas.User.Apps.Projects.Cover', {
                id: <?php echo $project->id; ?>,
                group: 'project',
                required: 0,
                hasCover: <?php echo $project->getOriginal() ? 1 : 0; ?>,
                defaultCover: '<?php echo JURI::base(); ?>/media/com_easysocial/defaults/covers/event/default.jpg'
        });
});

EasySocial.require().script('apps/fields/user/address/maps').done(function($) {
	$('[data-field-address]').addController('EasySocial.Controller.Field.Address.Maps', {
        id: '',
        latitude: <?php echo !empty($address->latitude) ? $address->latitude : '""'; ?>,
        longitude: <?php echo !empty($address->longitude) ? $address->longitude : '""'; ?>,
        address: '<?php echo addslashes($address->toString()); ?>',
        zoom:  2,
        required: false
    });
});

<?php 
else:
?>
EasySocial.require()
.script('<?php echo rtrim( JURI::root() , ' / ' );?>/media/com_easysocial/apps/user/projects/assets/js/projects.js')
.done(function ($)
{
        $('[data-project-edit]').addController('EasySocial.Controller.Canvas.User.Apps.Project.Edit', {
            userid: <?php echo $this->my->id; ?>
        });
        
        $('[data-field-project-image]').addController('EasySocial.Controller.Canvas.User.Apps.Projects.Cover', {
                id: 0,
                group: 'project',
                required: 0,
                hasCover: 0,
                defaultCover: '<?php echo JURI::base(); ?>/media/com_easysocial/defaults/covers/event/default.jpg'
        });
});

EasySocial.require().script('apps/fields/user/address/maps').done(function($) {
	$('[data-field-address]').addController('EasySocial.Controller.Field.Address.Maps', {
        id: '',
        latitude: "",
        longitude: "",
        address: '',
        zoom:  2,
        required: false
    });
});
<?php 
endif;
?>
