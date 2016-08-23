<?php

class actionNeomessengerDonate extends cmsAction {

    public function run() {

        return cmsTemplate::getInstance()->render('backend/donate');

    }

}