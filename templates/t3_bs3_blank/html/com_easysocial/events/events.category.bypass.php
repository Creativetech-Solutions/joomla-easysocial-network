<?php

//bypass the first step - default to the first category

$model = ES::model('EventCategories');
$cats = $model->getCategories();
if (count($cats)) {
    $category_id = $cats[0]->id;
    $session = JFactory::getSession();
    $session->set('category_id', $category_id, SOCIAL_SESSION_NAMESPACE);

    $stepSession = FD::table('StepSession');
    $stepSession->load(array('session_id' => $session->getId(), 'type' => SOCIAL_TYPE_EVENT));

    $stepSession->session_id = $session->getId();
    $stepSession->uid = $category_id;
    $stepSession->type = SOCIAL_TYPE_EVENT;

    $stepSession->set('step', 1);

    $stepSession->addStepAccess(1);
    $stepSession->store();
}


