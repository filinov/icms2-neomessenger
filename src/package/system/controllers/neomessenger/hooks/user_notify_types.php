<?php

class onNeomessengerUserNotifyTypes extends cmsAction {

    public function run() {

        return array(
            'messages_new' => array(
                'title' => LANG_NEOMESSENGER_NOTIFY_NEW,
                'options' => array('none', 'email')
            ),
        );

    }

}
