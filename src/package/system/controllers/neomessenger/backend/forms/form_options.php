<?php

class formNeomessengerOptions extends cmsForm {

    public function init() {

        return array(

            array(
                'type' => 'fieldset',
                'childs' => array(

                    new fieldNumber('refresh_interval', array(
                        'title' => LANG_NEOMESSENGER_TIME_UPDATE,
                        'default' => 20,
                        'rules' => array(
                            array('required'),
                            array('min', 5)
                        )
                    )),

                    new fieldList('send_type', array(
                        'title' => LANG_NEOMESSENGER_SEND_TYPE,
                        'default' => 'enter',
                        'items' => array(
                            'enter' => LANG_NEOMESSENGER_SEND_TYPE_ENTER,
                            'ctrl_enter' => LANG_NEOMESSENGER_SEND_TYPE_CTRL_ENTER
                        )
                    )),

                    new fieldList('is_backdrop_close', array(
                        'title' => LANG_NEOMESSENGER_IS_BACKDROP_CLOSE,
                        'default' => 1,
                        'items' => array(
                            1 => LANG_YES,
                            0 => LANG_NO
                        )
                    )),

                    new fieldCheckbox('is_flip_buttons', array(
                        'title' => LANG_NEOMESSENGER_FLIP_BUTTONS,
                        'default' => 0
                    ))

                )
            ),

            array(
                'type' => 'fieldset',
                'childs' => array(

                    new fieldCheckbox('is_title_count', array(
                        'title' => LANG_NEOMESSENGER_TITLE_COUNT,
                        'default' => 1
                    )),

                    new fieldCheckbox('is_favicon_count', array(
                        'title' => LANG_NEOMESSENGER_FAVICON_COUNT,
                        'default' => 1
                    )),

                )
            ),

            array(
                'type' => 'fieldset',
                'childs' => array(

                    new fieldNumber('messages_limit', array(
                        'title' => LANG_NEOMESSENGER_MESSAGES_LIMIT,
                        'default' => 20,
                        'rules' => array(
                            array('required'),
                            array('max', 100)
                        )
                    )),

                )
            ),

            array(
                'type' => 'fieldset',
                'childs' => array(

                    new fieldString('permanent_users', array(
                        'title' => LANG_NEOMESSENGER_PERMANENT_USERS,
                        'hint' => LANG_NEOMESSENGER_PERMANENT_USERS_HINT
                    ))

                )
            ),

            array(
                'type' => 'fieldset',
                'title' => LANG_PERMISSIONS,
                'childs' => array(

                    new fieldListGroups('groups_allowed', array(
                        'show_all' => true,
                        'default' => array(0)
                    ))

                )
            )
        );

    }

}