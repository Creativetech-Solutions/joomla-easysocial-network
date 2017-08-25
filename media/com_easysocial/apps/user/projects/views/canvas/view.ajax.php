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

// Import dependencies.
FD::import( 'admin:/includes/apps/dependencies' );

/**
 * Dashboard view for Feeds app.
 *
 * @since	1.0
 * @access	public
 */
class ProjectsViewCanvas extends SocialAppsView
{
	/**
     * Responsible to show the invite friends dialog.
     *
     * @author Jason Rey <jasonrey@stackideas.com>
     * @since  1.3
     * @access public
     */
    public function inviteFriendsDialog()
    {
        FD::requireLogin();

        $id = $this->input->get('id', '0', 'int');

        $project = $this->getTable('Project');
        $state = $project->load($id);

        if (empty($project) || empty($project->id)) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_INVALID_PROJECT_ID'));
        }

        if (!$project->isPublished()) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_UNAVAILABLE'));
        }

        $model = FD::model('Projects');
        $friends = $model->getFriendsInProject($project->id, array('userId' => $this->my->id));

        $exclusion = array();

        foreach ($friends as $friend) {
            $exclusion[] = $friend->id;
        }

        $theme = FD::themes();
        $theme->set('exclusion', $exclusion);
        $theme->set('project', $project);
        $contents = $theme->output('apps/user/projects/dialog.inviteFriends');

        return $this->ajax->resolve($contents);
    }
}
