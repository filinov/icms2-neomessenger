<?php

class backendNeomessenger extends cmsBackend {

    protected $useOptions = true;
    public $useDefaultOptionsAction = true;

    public function actionIndex() {

        $this->redirectToAction('options');

    }

    public function getBackendMenu() {

        return array(

            array(
                'title' => LANG_OPTIONS,
                'url' => href_to($this->root_url, 'options')
            ),

            array(
                'title' => LANG_NEOMESSENGER_CP_MASS_MAILING,
                'url' => href_to($this->root_url, 'mass_mailing')
            ),

            array(
                'title' => LANG_NM_EXTENDS,
                'url' => href_to($this->root_url, 'extends')
            ),

            array(
                'title' => LANG_NEOMESSENGER_CP_THANKS,
                'url' => href_to($this->root_url, 'donate')
            ),

        );

    }

}