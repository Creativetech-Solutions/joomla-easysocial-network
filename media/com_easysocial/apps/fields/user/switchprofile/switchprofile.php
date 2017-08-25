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

// Include the fields library
FD::import( 'admin:/includes/fields/dependencies' );

// Include helper library
require_once( dirname( __FILE__ ) . '/helper.php' );

/**
 * Field application for Currency
 *
 * @since	1.0
 * @author	Jason Rey <jasonrey@stackideas.com>
 */
class SocialFieldsUserSwitchprofile extends SocialFieldItem
{
	public function __construct()
	{
		parent::__construct();
	}
	

	/**
	 * Responsible to output the html codes that is displayed to
	 * a user when they edit their profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialUser		The user that is being edited.
	 * @return
	 */
	public function onEdit(&$post, &$user, $errors)
	{

		$model = FD::model( 'Profiles' , array( 'initState' => true ));
		
		$profiles	= $model->getItems();

		$profile_id = $user->getProfile()->get('id');

		$value = !empty($post[$this->inputName]) ? $post[$this->inputName] : $profile_id;

		// Get errors
		$error = $this->getError($errors);

		$this->set('value', $value);
		$this->set('user',$user);
		$this->set('error', $error);
		$this->set( 'profiles', $profiles );

		return $this->display();
	}


	/**
	 * Validates the field when the user edits their profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 */
	public function onEditValidate(&$post)
	{
		$profile_id = !empty($post[$this->inputName]) ? $post[$this->inputName] : '';

		return $this->validateField($profile_id);
	}

	/**
	 * Executes before a user's edit is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The post data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 */
	public function onEditAfterSave(&$post, &$user)
	{
		$profile_id_current = $user->getProfile()->get('id');

		$value = $this->escape($profile_id_current);	
                $profile_id = isset($post[$this->inputName]) ? $post[$this->inputName] : $value;
		//$profile_id = $this->input->get($this->inputName, $value, 'raw');

		// @TODO: Try to remove user from any other existing profile maps.
		$model 	= FD::model( 'Profiles' );
		//$model->removeUserFromProfiles( $user->id );
               
                // Switch the user's profile
		$model->updateUserProfile($user->id, $profile_id);
                
                // Update the user's usergroups    
                $model->updateJoomlaGroup($id, $profileId);
//		$table 	= FD::table( 'ProfileMap' );
//
//		$table->user_id 	= $user->id;
//		$table->profile_id 	= $profile_id;
//		$table->state 		= SOCIAL_STATE_PUBLISHED;
//
//
//		// @rule: Store user profile bindings
//		$table->store();

		// Remove the data from $post to prevent description saving in fields table
		unset($post[$this->inputName]);

		return true;
	}

	/**
	 * Responsible to output the html codes that is displayed to
	 * a user when their profile is viewed.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function onDisplay($user)
	{
		$profile = $user->getProfile()->get('title');

		// Push variables into theme.
		$this->set('value', $profile);

		return $this->display();
	}

	/**
	 * Validates the custom field
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	private function validateField($profile_id)
	{

		// Verify that the field are not empty
		if(empty($profile_id) && $this->isRequired()) {
			$this->setError(JText::_('PLG_FIELDS_SWITCH_PROFILE_INPUT_EMPTY'));
			return false;
		}

		return true;
	}


	/**
	 * Displays the sample html codes when the field is added into the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @return	string	The html output.
	 */
	public function onSample()
	{
		return $this->display();
	}

	/**
	 * Checks if this field is filled in.
	 *
	 * @since  2.0
	 * @access public
	 * @param  array
	 * @param  SocialUser	$user	The user being checked.
	 */
	public function onProfileCompleteCheck($user)
	{
		if (!FD::config()->get('user.completeprofile.strict') && !$this->isRequired()) {
			return true;
		}

		// Get easyblog profile
		$profile_id = $user->getProfile()->get('id');


		return !empty($profile_id);
	}
}
