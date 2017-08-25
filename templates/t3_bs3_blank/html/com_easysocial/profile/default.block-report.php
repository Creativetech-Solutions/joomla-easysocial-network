<?php 
if(!$user){
    $id = $this->input->get('id', 0, 'int');
    $user = FD::user($id);
}
if ((!$this->my->guest && $this->my->id != $user->id && $this->config->get('users.blocking.enabled')) || ($this->my->id != $user->id && $this->template->get('profile_report', true) && $this->access->allowed('reports.submit') && $this->config->get('reports.enabled'))) { ?>
    <li data-friends-block-user>
        <?php if (!$this->my->guest && $this->my->id != $user->id && !$user->isSiteAdmin() && $this->config->get('users.blocking.enabled')) { ?>
            <?php echo FD::blocks()->getForm($user->id); ?>
        <?php } ?>
    </li>
    <li data-friends-report-user>
        <?php if ($this->my->id != $user->id && $this->template->get('profile_report', true) && $this->access->allowed('reports.submit') && $this->config->get('reports.enabled')) { ?>
            <?php echo FD::reports()->getForm('com_easysocial', SOCIAL_TYPE_USER, $user->id, $user->getName(), JText::_('COM_EASYSOCIAL_PROFILE_REPORT_USER'), '', JText::_('COM_EASYSOCIAL_PROFILE_REPORT_USER_DESC'), $user->getPermalink(true, true)); ?>
        <?php } ?>
    </li>
<?php } ?>