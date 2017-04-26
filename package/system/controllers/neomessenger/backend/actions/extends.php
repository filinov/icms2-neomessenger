<?php

class actionNeomessengerExtends extends cmsAction {

    public function run() {

        $template = cmsTemplate::getInstance();

        if (!cmsCore::isControllerExists('nm_extends')) {
            return $template->render('backend/extends', array(
                'text' => LANG_NM_EXTENDS_NOT_INSTALLED
            ));
        }

        if (!$this->isControllerInstalled('nm_extends')) {
            return $template->render('backend/extends', array(
                'text' => LANG_NM_EXTENDS_NOT_INSTALLED
            ));
        }

        if (!cmsController::enabled('nm_extends')) {
            return $template->render('backend/extends', array(
                'text' => LANG_NM_EXTENDS_DISABLED
            ));
        }

        $request = new cmsRequest($this->request->getData(), cmsRequest::CTX_INTERNAL);

        return cmsCore::getController('nm_extends', $request)->runAction('options');

    }

}