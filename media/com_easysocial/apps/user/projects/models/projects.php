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
defined('_JEXEC') or die('Unauthorized Access');
FD::import('admin:/includes/model');

class ProjectsModel extends EasySocialModel {

    /**
     * Initializes all the generic states from the form
     *
     * @since   1.0
     * @access  public
     * @param   string
     * @return
     */
    public function initStates() {
        $filter = $this->getUserStateFromRequest('filter', 'all');
        $ordering = $this->getUserStateFromRequest('ordering', 'id');
        $direction = $this->getUserStateFromRequest('direction', 'ASC');

        $this->setState('filter', $filter);

        parent::initStates();

        // Override the ordering behavior
        $this->setState('ordering', $ordering);
        $this->setState('direction', $direction);
    }

    //override coz the damn thing wasn't working from within the model
    public function getTable($type = 'Project') {

        $project = new SocialAppsController('user', ES_SOCIAL_TYPE_PROJECTS);
        $table = $project->getTable($type);

        return $table;
    }

    /**
     * Retrieves a list of profiles that has access to a category
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return
     */
    public function getCategoryAccess($categoryId, $type = 'create') {
        $db = ES::db();

        $sql = $db->sql();
        $sql->select('#__social_videos_categories_access');
        $sql->column('profile_id');
        $sql->where('category_id', $categoryId);
        $sql->where('type', $type);

        $db->setQuery($sql);

        $ids = $db->loadColumn();

        return $ids;
    }

    /**
     * Inserts new access for a cluster category
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return
     */
    public function insertCategoryAccess($categoryId, $type = 'create', $profiles = array()) {
        $db = FD::db();

        // Delete all existing access type first
        $sql = $db->sql();
        $sql->delete('#__social_videos_categories_access');
        $sql->where('category_id', $categoryId);
        $sql->where('type', $type);

        $db->setQuery($sql);
        $db->Query();

        if (!$profiles) {
            return;
        }

        foreach ($profiles as $id) {
            $sql->clear();
            $sql->insert('#__social_videos_categories_access');
            $sql->values('category_id', $categoryId);
            $sql->values('type', $type);
            $sql->values('profile_id', $id);

            $db->setQuery($sql);
            $db->Query();
        }

        return true;
    }
 
    /**
     * Retrieves the list of videos for the back end
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return
     */
    public function getItems() {
        $sql = $this->db->sql();

        $filter = $this->getState('filter');

        $sql->select('#__social_projects');

        if ($filter != 'all') {
            $sql->where('state', $filter);
        }

        // Set the total records for pagination.
        $this->setTotal($sql->getTotalSql());

        $result = $this->getData($sql->getSql());

        if (!$result) {
            return $result;
        }

        $videos = array();

        foreach ($result as $row) {

            $tmp = (array) $row;

            $row = ES::table('Project');
            $row->bind($tmp);

            $video = ES::video($row);

            $videos[] = $video;
        }

        return $videos;
    }

    private function getAccessColumn($type = 'access', $prefix = 'a') {
        $column = '';
        if ($type == 'access') {
            $column = "(select pri.value as `access` from `#__social_privacy_items` as pri";
            $column .= " left join `#__social_privacy_customize` as prc on pri.id = prc.uid and prc.utype = 'item' where pri.uid = " . $prefix . ".id and pri.`type` = 'videos'";
            $column .= " UNION ALL ";
            $column .= " select prm.value as `access`";
            $column .= " from `#__social_privacy_map` as prm";
            $column .= "  inner join `#__social_privacy` as pp on prm.privacy_id = pp.id";
            $column .= "  left join `#__social_privacy_customize` as prc on prm.id = prc.uid and prc.utype = 'user'";
            $column .= " where prm.uid = " . $prefix . ".user_id and prm.utype = 'user'";
            $column .= "  and pp.type = 'videos' and pp.rule = 'view'";
            $column .= " union all ";
            $column .= " select prm.value as `access`";
            $column .= " from `#__social_privacy_map` as prm";
            $column .= "  inner join `#__social_privacy` as pp on prm.privacy_id = pp.id";
            $column .= "  inner join `#__social_profiles_maps` pmp on prm.uid = pmp.profile_id";
            $column .= " where prm.utype = 'profiles' and pmp.user_id = " . $prefix . ".user_id";
            $column .= "  and pp.type = 'videos' and pp.rule = 'view'";
            $column .= " limit 1";
            $column .= ") as access";
        } else if ($type == 'customaccess') {

            $column = "(select concat(',', group_concat(prc.user_id SEPARATOR ','), ',') as `custom_access` from `#__social_privacy_items` as pri";
            $column .= " left join `#__social_privacy_customize` as prc on pri.id = prc.uid and prc.utype = 'item' where pri.uid = " . $prefix . ".id and pri.`type` = 'videos'";
            $column .= " UNION ALL ";
            $column .= " select concat(',', group_concat(prc.user_id SEPARATOR ','), ',') as `custom_access`";
            $column .= " from `#__social_privacy_map` as prm";
            $column .= "    inner join `#__social_privacy` as pp on prm.privacy_id = pp.id";
            $column .= "    left join `#__social_privacy_customize` as prc on prm.id = prc.uid and prc.utype = 'user'";
            $column .= " where prm.uid = " . $prefix . ".user_id and prm.utype = 'user'";
            $column .= "    and pp.type = 'videos' and pp.rule = 'view'";
            $column .= " limit 1";
            $column .= ") as custom_access";
        }

        return $column;
    }
    /**
     * Retrieves a list of videos from the site
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return
     */
    public function getProjects($options = array()) {
        $db = ES::db();
        $sql = $db->sql();

        $accessColumn = $this->getAccessColumn('access', 'a');
        $accessCustomColumn = $this->getAccessColumn('customaccess', 'a');

        $likeCountColumn = "(select count(1) from `#__social_likes` as exb where exb.uid = a.id and exb.type = 'projects.user.create') as likes";
        $commentCountColumn = "(select count(1) from `#__social_comments` as exb where exb.uid = a.id and exb.element = 'projects.user.create') as totalcomments";

        // search criteria
        $layout = $this->normalize($options, 'layout', null);
        $privacy = $this->normalize($options, 'privacy', true);
        $filter = $this->normalize($options, 'filter', '');
        $featured = $this->normalize($options, 'featured', null);

        $sort = $this->normalize($options, 'sort', 'latest');
        $maxlimit = $this->normalize($options, 'maxlimit', 0);
        $limit = $this->normalize($options, 'limit', false);

        $storage = $this->normalize($options, 'storage', false);
        $uid = $this->normalize($options, 'uid', null);
        $type = $this->normalize($options, 'type', null);
        $source = $this->normalize($options, 'source', false);
        $prid = $this->normalize($options, 'prid', false);

        $userid = $this->normalize($options, 'userid', null);
        $my = ES::user();

        $useLimit = true;

        $query = array();

        $isSiteAdmin = ES::user()->isSiteAdmin();

        if (!$isSiteAdmin && $privacy) {
            $query[] = "select * from (";
        }

        $query[] = "select a.*";

        /* if (!$isSiteAdmin && $privacy) {
          if ($type == 'user' || is_null($type)) {
          $query[] = ", $accessColumn, $accessCustomColumn";
          } else {
          $query[] = ", cls.`type` as `access`";
          }
          } */

        if ($sort == 'likes') {
            $query[] = ", $likeCountColumn";
        }

        if ($sort == 'commented') {
            $query[] = ", $commentCountColumn";
        }

        $query[] = "from `#__social_projects` as a";

        if ($type != 'user' && !is_null($type)) {
            $query[] = " inner join `#__social_clusters` as cls on a.`uid` = cls.`id` and a.`type` = cls.`cluster_type`";
        }
        
        if($layout == 'default'){
            $query[] = " left join `#__social_projects_team` as t on a.`id` = t.`pid`";
        }

        if ($filter == 'pending') {
            $query[] = "where a.`state` = " . $db->Quote(2);
        } else if ($filter == 'processing') {
            $query[] = 'WHERE a.`state`=' . $db->Quote(3);
        } else {
            $query[] = "where a.`state` = " . $db->Quote(1);
        }

        if ($uid && $type) {
            if($layout == 'default'){
                $query[] = 'AND (a.`uid`=' . $db->Quote($uid).' OR t.`user_id` = '.$db->Quote($uid).')';
            }else{
                $query[] = 'AND a.`uid`=' . $db->Quote($uid);
            }
            
            $query[] = 'AND a.`type`=' . $db->Quote($type);
        } else {
            $query[] = 'and a.`type` = ' . $db->Quote('user');
            //$query[] = '    or ((select count(1) from `#__social_clusters` as c where c.`id` = a.`uid` and c.`cluster_type` = a.`type` and c.type = '. $db->Quote(SOCIAL_GROUPS_PUBLIC_TYPE) .') > 0))';
        }

        if ($filter == 'mine') {
            $my = ES::user();
            if($layout == 'default'){
                $query[] = "and (a.`user_id` = " . $db->Quote($my->id).' OR t.`user_id` = '.$db->Quote($my->id).')';
            }else{
                $query[] = "and a.`user_id` = " . $db->Quote($my->id);
            }            
        }

        if ($filter == 'pending' && $userid) {
            $query[] = "and a.`user_id` = " . $db->Quote($userid);
        }

        if ($filter == SOCIAL_TYPE_USER) {
            if($layout == 'default'){
                $query[] = "and (a.`user_id` = " . $db->Quote($userid).' OR t.`user_id` = '.$db->Quote($userid).')';
            }else{
                $query[] = "and a.`user_id` = " . $db->Quote($userid);
            }
        }

        $exclusion = $this->normalize($options, 'exclusion', null);

        if ($exclusion) {

            $exclusion = ES::makeArray($exclusion);
            $exclusionIds = array();

            foreach ($exclusion as $exclusionId) {
                $exclusionIds[] = $db->Quote($exclusionId);
            }

            $exclusionIds = implode(',', $exclusionIds);

            $query[] = 'AND a.' . $db->qn('id') . ' NOT IN (' . $exclusionIds . ')';
        }

        //specific id
        if ($prid) {
            $query[] = "and a.`id` = " . $db->Quote($prid);
        }
        // featured filtering
        if ($filter == 'featured') {
            $query[] = "and a.`featured` = " . $db->Quote(SOCIAL_VIDEO_FEATURED);
        }

        // featured
        if (!is_null($featured)) {
            $query[] = "and a.`featured` = " . $db->Quote((int) $featured);
        }


        if ($storage !== false) {
            $query[] = 'AND a.`storage` = ' . $db->Quote($storage);
        }

        if ($source !== false) {
            $query[] = 'AND a.`source`=' . $db->Quote($source);
        }
        
        if($layout == 'default'){
            $query[] = 'GROUP BY a.`id`';
        }

        if ($sort) {
            switch ($sort) {
                case 'popular':
                    $query[] = "order by a.hits desc";
                    break;

                case 'alphabetical':
                    $query[] = "order by a.title asc";
                    break;

                case 'random':
                    $query[] = "order by RAND()";
                    break;

                case 'likes':
                    $query[] = "order by likes desc";
                    break;

                case 'commented':
                    $query[] = "order by totalcomments desc";
                    break;

                case 'latest':
                default:
                    $query[] = "order by a.created desc";
                    break;
            }
        }else{
            $query[] = "order by a.created asc";
        }

        if (!$isSiteAdmin && $privacy) {

            $viewer = FD::user()->id;

            $query[] = ") as x";

            if ($type != 'user' && !is_null($type)) {
                // cluster privacy
                $query[] = " WHERE (";
                $query[] = " (x.`access` = 1) OR";
                $query[] = " (x.`access` > 1) AND " . $db->Quote($viewer) . " IN ( select scn.`uid` from `#__social_clusters_nodes` as scn where scn.`cluster_id` = x.`uid` and scn.`type` = " . $db->Quote(SOCIAL_TYPE_USER) . " and scn.`state` = 1)";
                $query[] = ")";
            } else {

                // privacy here.
                $query[] = " WHERE (";

                //public
                $query[] = "(x.`access` = " . $db->Quote(SOCIAL_PRIVACY_PUBLIC) . ") OR";

                //member
                $query[] = "( (x.`access` = " . $db->Quote(SOCIAL_PRIVACY_MEMBER) . ") AND (" . $viewer . " > 0 ) ) OR ";

                //friends
                $query[] = "( (x.`access` = " . $db->Quote(SOCIAL_PRIVACY_FRIEND) . ") AND ( (" . $this->generateIsFriendSQL('x.`user_id`', $viewer) . ") > 0 ) ) OR ";

                //only me
                $query[] = "( (x.`access` = " . $db->Quote(SOCIAL_PRIVACY_ONLY_ME) . ") AND ( x.`user_id` = " . $viewer . " ) ) OR ";

                // custom
                $query[] = "( (x.`access` = " . $db->Quote(SOCIAL_PRIVACY_CUSTOM) . ") AND ( x.`custom_access` LIKE " . $db->Quote('%,' . $viewer . ',%') . "    ) ) OR ";

                // my own items.
                $query[] = "(x.`user_id` = " . $viewer . ")";

                // privacy checking end here.
                $query[] = ")";
            }
        }


        if ($maxlimit) {
            $useLimit = false;
            $query[] = "limit $maxlimit";
        }

        $query = implode(' ', $query);
        $sql->raw($query);

        // dump($sql->debug());

        if (!$maxlimit && $limit) {

            $this->setState('limit', $limit);

            // Get the limitstart.
            $limitstart = $this->getUserStateFromRequest('limitstart', 0);
            $limitstart = ( $limit != 0 ? ( floor($limitstart / $limit) * $limit ) : 0 );

            $this->setState('limitstart', $limitstart);

            // Set the total number of items.
            $this->setTotal($sql->getSql(), true);
        } else {
            $useLimit = false;
        }
        

        $this->db->setQuery($sql);
        $result = $this->getData($sql, $useLimit);
        //echo $sql;
       //exit;
        if (!$result) {
            return $result;
        }

        $projects = array();

        foreach ($result as $row) {

            //$project = ES::project($row->uid, $row->type);
            //print_r($project);exit;
            //$project->load($row);

            $project = $this->getTable('Project');
            $project->load($row->id);
            $category = $this->getTable('ProjectCategory');
            $category->load($project->category_id);
            $project->category = $category;
            $projects[] = $project;
            
        }

        return $projects;
    }

    /**
     * Overriding parent getData method so that we can specify if we need the limit or not.
     *
     * If using the pagination query, child needs to use this method.
     *
     * @since   1.4
     * @access  public
     */
    protected function getData($query, $useLimit = true) {
        if ($useLimit) {
            return parent::getData($query);
        } else {
            $this->db->setQuery($query);
        }

        return $this->db->loadObjectList();
    }

    public function generateIsFriendSQL($source, $target) {
        $query = "select count(1) from `#__social_friends` where ( `actor_id` = $source and `target_id` = $target) OR (`target_id` = $source and `actor_id` = $target) and `state` = 1";
        return $query;
    }

    /**
     * Retrieves the default category
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return
     */
    public function getDefaultCategory() {
        $db = $this->db;
        $sql = $db->sql();

        $sql->select('#__social_projects_categories');
        $sql->where('default', 1);

        $db->setQuery($sql);

        $result = $db->loadObject();

        if (!$result) {
            return false;
        }

        $category = ES::table('ProjectCategory');
        $category->bind($result);

        return $category;
    }

    /**
     * Retrieves a list of video categories from the site
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return
     */
    public function getCategories($options = array()) {
        $db = ES::db();
        $sql = $db->sql();

        $query = array();
        $query[] = 'SELECT a.* FROM ' . $db->qn('#__social_projects_categories') . ' AS a';

//        // Filter for respecting creation access
//        $respectAccess = $this->normalize($options, 'respectAccess', false);
//        $profileId = $this->normalize($options, 'profileId', 0);
//
//        if ($respectAccess && $profileId) {
//            $query[] = 'LEFT JOIN ' . $db->qn('#__social_projects_categories_access') . ' AS b';
//            $query[] = 'ON a.id = b.category_id';
//        }

        $query[] = 'WHERE 1 ';

        // Filter for searching categories
        $search = $this->normalize($options, 'search', '');

        if ($search) {
            $query[] = 'AND ';
            $query[] = $db->qn('title') . ' LIKE ' . $db->Quote('%' . $search . '%');
        }

        $query[] = 'order by ordering';
        // Ensure that the projects are published
        $state = $this->normalize($options, 'state', true);
        $prid = $this->normalize($options, 'prid', false);
        // Ensure that all the categories are listed in backend
        $adminView = $this->normalize($options, 'administrator', false);

        if (!$adminView) {
            $query[] = 'AND ' . $db->qn('state') . '=' . $db->Quote($state);
        }
        if ($prid) {
            $query[] = 'AND ' . $db->qn('id') . '=' . $db->Quote($prid);
        }

        $query = implode(' ', $query);
        $sql->raw($query);

        // Set the total records for pagination.
        $totalSql = str_ireplace('a.*', 'COUNT(1)', $query);
        $this->setTotal($totalSql);

        // Runt he main query now
        $db->setQuery($sql);

        // We need to go through our paginated library
        $result = $this->getData($sql->getSql());

        if (!$result) {
            return $result;
        }

        $categories = array();

        foreach ($result as $row) {
            $category = $this->getTable('ProjectCategory');

            $category->bind($row);

            $categories[] = $category;
        }

        return $categories;
    }

    public function getCategoryListHtml($selected='') {
        $cats = $this->getCategories();
        $list[] = JHTML::_('select.option', '', JText::_('COM_COMMUNITY_SELECT_GENRE'));
        foreach ($cats as $cat) {
            if ($cat->parent_id == 0) {
                $cat->attr = 'style="color:red;"';
                $cat->disable = true;
            } else {
                $list[] = JHTML::_('select.option', $cat->id, $cat->title);
            }
        }

        $options = array(
            'name' =>'category_id',
            'id' => 'category_id', // HTML id for select field
            'list.attr' => array(// additional HTML attributes for select field
                'class' => 'form-control',
            ),
            'list.translate' => false, // true to translate
            'option.key' => 'id', // key name for value in data array
            'option.text' => 'title', // key name for text in data array
            'option.attr' => 'attr', // key name for attr in data array
            'list.select' => $selected, // value of the SELECTED field
        );
        return JHTML::_('select.genericlist', $cats, 'category_id', $options);
    }

    /**
     * Retrieves the total number of videos from a category
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return
     */
    public function getTotalVideosFromCategory($categoryId, $cluster = false, $uid = null, $type = null) {
        $db = $this->db;
        $sql = $this->db->sql();


        // $query = "select count(1) from `#__social_videos` as a";

        $tmpTable = $this->genCounterTableWithPrivacy();
        $query = "select count(1) from $tmpTable as a";

        $query .= " where a.state = " . $this->db->Quote(SOCIAL_VIDEO_PUBLISHED);
        $query .= " and a.category_id = " . $this->db->Quote($categoryId);

        if (!is_null($uid) && !is_null($type)) {
            if ($type == SOCIAL_TYPE_USER) {
                $query .= " and a.user_id = " . $db->Quote($uid);
            }

            if ($cluster && !($cluster instanceof SocialUser)) {
                $query .= " and a.uid = " . $db->Quote($cluster->id);
                $query .= " and a.type = " . $db->Quote($cluster->getType());
            } else {
                $query .= " and (a.`type` = " . $this->db->Quote('user');
                $query .= " or ((select count(1) from `#__social_clusters` as c where c.`id` = a.`uid` and c.`cluster_type` = a.`type` and c.type = " . $this->db->Quote(SOCIAL_GROUPS_PUBLIC_TYPE) . ") > 0))";
            }
        }

        $sql->raw($query);
        $this->db->setQuery($sql);
        $total = $this->db->loadResult();

        return $total;
    }

    /**
     * Determines if the video should be associated with the stream item
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return
     */
    public function getStreamId($id, $verb) {
        $db = ES::db();
        $sql = $db->sql();

        $sql->select('#__social_stream_item', 'a');
        $sql->column('a.uid');
        $sql->where('a.context_type', SOCIAL_TYPE_VIDEOS);
        $sql->where('a.context_id', $id);
        $sql->where('a.verb', $verb);

        $db->setQuery($sql);

        $uid = (int) $db->loadResult();

        return $uid;
    }
    
    public function getFriendsInProject($projectId, $options = array())
    {
        $db = FD::db();
        $sql = $db->sql();

        $userId = isset($options['userId']) ? $options['userId'] : FD::user()->id;

        $sql->select('#__social_clusters_nodes', 'a');
        $sql->column('a.uid', 'uid', 'distinct');
        $sql->innerjoin('#__social_friends', 'b');
        $sql->on('(');
        $sql->on('(');
        $sql->on('a.uid', 'b.actor_id');
        $sql->on('b.target_id', $userId);
        $sql->on(')');
        $sql->on('(', '', '', 'OR');
        $sql->on('a.uid', 'b.target_id');
        $sql->on('b.actor_id', $userId);
        $sql->on(')');
        $sql->on(')');
        $sql->on('b.state', SOCIAL_STATE_PUBLISHED);

        // exclude esad users
        $sql->innerjoin('#__social_profiles_maps', 'upm');
        $sql->on('a.uid', 'upm.user_id');

        $sql->innerjoin('#__social_profiles', 'up');
        $sql->on('upm.profile_id', 'up.id');
        $sql->on('up.community_access', '1');

        $sql->where('a.cluster_id', $projectId);

        if (isset($options['published'])) {
            $sql->where('a.state', $options['published']);
        }

        $db->setQuery($sql);
        $result = $db->loadColumn();

        $users = array();

        foreach ($result as $id) {
            $users[] = FD::user($id);
        }

        return $users;
    }
    
    public function getProjectJobs($pid = ''){
        $db = FD::db();
        if (!$pid) {
            return false;
        }
        $query = "SELECT * FROM #__social_projects_jobs WHERE `pid` = " . $pid;
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

}
