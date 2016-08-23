<?php

class formNeomessengerMassMailing extends cmsForm {

    public function init() {

        return array(

            array(
                'type' => 'fieldset',
                'title' => LANG_FILTERS,
                'childs' => array(

                    new fieldListGroups('groups', array(
                        'title' => LANG_NEOMESSENGER_CP_SEND_GROUPS_ALLOW_ONLY,
                        'show_all' => true,
                        'rules' => array(
                            array('required')
                        )
                    ))

                )
            ),

            array(
                'type' => 'fieldset',
                'childs' => array(

                    new fieldHtml('message_text', array(
                        'title' => LANG_MESSAGE,
                        'options' => array(
                            'editor' => 'markitup'
                        ),
                        'rules' => array(
                            array('required')
                        )
                    )),

                    new fieldList('send_type', array(
                        'title' => LANG_NEOMESSENGER_CP_SEND_METHOD,
                        'items' => array(
                            'notify'  => LANG_NEOMESSENGER_CP_NOTIFY,
                            'message' => LANG_NEOMESSENGER_CP_MESSAGE
                        )
                    ))

                )
            )

        );

    }

}