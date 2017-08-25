<?php
defined('_JEXEC') or die('Unauthorized Access');
?>
<?php
$uid = $this->input->get('uid', null, 'int');
$type = $this->input->get('type', SOCIAL_TYPE_USER, 'cmd');

$user 	= FD::user();
// If this is a user type, we will want to get a list of albums the current logged in user created
if ($type == SOCIAL_TYPE_USER) {
	$user 	= FD::user($uid);
	$uid 	= $user->id;
}

$currentProfile = $user->getProfile()->get('title');
// Retrieve user's step
$usersModel = ES::model('Users');
// Get the active step
$activeStep = 0;
$privacy = $this->my->getPrivacy();

// Get the list of available steps on the user's profile
$steps = $usersModel->getAbout($user, $activeStep);
echo $this->output('site/dashboard/profile-header',array('currentProfile' => $currentProfile,'user'=>$user,'cover' => $cover,'steps' => $steps,'privacy' => $privacy ) );

//app-audios" data-canvas-app-audios data-app-id="<?php echo $app->id;"
// data-app-contents
//data-apps-audios
?>

<div class="es-container es-audios app-audios" data-canvas-app-audios data-app-id="<?php echo $app->id; ?>">
    <?php if($this->my->id == $this->input->get('uid', 0, 'int')){ ?>
    <div class="clearfix">
        <div class="es-widget-create mr-10 pull-right">
            <a class="btn-add-audio" href="<?= FRoute::apps(array('layout' => 'canvas', 'clayout' => 'form', 'id' => $app->getAlias())) ?>"><i class="fa fa-cloud-upload" aria-hidden="true"></i>  Add Audio</a>
        </div>


    </div>
    <?php } ?>
    <div class="es-audio-listing" data-app-contents>

        <div class="row" data-apps-audios>
            <div class="col-md-12">
                <div class="es-audios-featured">

                    <?php
                    echo $this->loadTemplate('apps/user/audios/canvas/featured', array('featuredAudios' => $featuredAudios, 'returnUrl' => $returnUrl));
                    ?>
                </div>
            </div>

        </div>
        <div class="spacer40"></div>
        <div class="row" data-apps-audios>
            <div class="col-md-12 other-audios">
                <?php
                echo $this->loadTemplate('apps/user/audios/canvas/items', array('audios' => $audios, 'returnUrl' => $returnUrl));
                ?>
            </div>
        </div>

        <div class="empty" data-feeds-empty>
            <i class="fa fa-database"></i>
            <?php echo JText::_('APP_AUDIOS_EMPTY_NOTES'); ?>
        </div>
    </div>
</div>

</div>