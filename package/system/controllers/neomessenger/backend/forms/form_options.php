<?php

class formNeomessengerOptions extends cmsForm {

    public $is_tabbed = true;

    public function init() {

        return array(

            array(
                'type' => 'fieldset',
                'title' => LANG_OPTIONS,
                'childs' => array(

                    new fieldNumber('refresh_interval', array(
                        'title' => LANG_NEOMESSENGER_CP_UPDATE_INTERVAL,
                        'hint' => LANG_NEOMESSENGER_CP_UPDATE_INTERVAL_HINT,
                        'default' => 20,
                        'units' => LANG_SECOND10,
                        'rules' => array(
                            array('required'),
                            array('min', 5)
                        )
                    )),

                    new fieldNumber('time_delete_old', array(
                        'title'   => LANG_NEOMESSENGER_TIME_DELETE_OLD,
                        'hint'    => LANG_NEOMESSENGER_TIME_DELETE_OLD_HINT,
                        'default' => 0,
                        'units'   => LANG_DAY10
                    )),

                    new fieldList('send_enter', array(
                        'title' => LANG_NEOMESSENGER_CP_SEND_TYPE,
                        'default' => 1,
                        'items' => array(
                            0 => 'Ctrl + Enter',
                            1 => 'Enter'
                        )
                    )),

                    new fieldList('close_backdrop', array(
                        'title' => LANG_NEOMESSENGER_CP_BACKDROP_CLOSE,
                        'default' => 1,
                        'items' => array(
                            1 => LANG_YES,
                            0 => LANG_NO
                        )
                    )),

                    new fieldCheckbox('show_widget_button', array(
                        'title' => LANG_NEOMESSENGER_CP_SHOW_WIDGET_BUTTON,
                        'default' => 1
                    )),

                    new fieldList('html_editor', array(
                        'title' => LANG_PARSER_HTML_EDITOR,
                        'default' => 'default',
                        'generator' => function() {
                            $items = array();
                            $editors = cmsEventsManager::hookAll('neomessenger_editor');
                            $editors[] = 'default';
                            foreach($editors as $editor) { $items[$editor] = $editor; }
                            return $items;
                        }
                    )),

                    new fieldString('fixed_contacts', array(
                        'title' => LANG_NEOMESSENGER_CP_FIXED_CONTACTS,
                        'hint' => LANG_NEOMESSENGER_CP_FIXED_CONTACTS_HINT
                    ))

                )
            ),

            array(
                'type' => 'fieldset',
                'title' => LANG_NEOMESSENGER_CP_COUNTERS,
                'childs' => array(

                    new fieldCheckbox('is_title_count', array(
                        'title' => LANG_NEOMESSENGER_CP_TITLE_COUNT,
                        'default' => 1
                    )),

                    new fieldCheckbox('is_favicon_count', array(
                        'title' => LANG_NEOMESSENGER_CP_FAVICON_COUNT,
                        'default' => 1
                    )),

                )
            ),

            array(
                'type' => 'fieldset',
                'title' => LANG_NEOMESSENGER_CP_LIMITS,
                'childs' => array(

                    new fieldNumber('messages_limit', array(
                        'title' => LANG_NEOMESSENGER_CP_MESSAGES_LIMIT,
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
                'title' => LANG_NEOMESSENGER_CP_DESIGN,
                'childs' => array(

                    new fieldColor('head_bg_color', array(
                        'title' => LANG_NEOMESSENGER_CP_HEAD_BG_COLOR,
                        'default' => '#5580a3'
                    )),

                    new fieldColor('title_color', array(
                        'title' => LANG_NEOMESSENGER_CP_TITLE_COLOR,
                        'default' => '#ffffff'
                    )),

                    new fieldColor('contact_panel_bg_color', array(
                        'title' => LANG_NEOMESSENGER_CP_CONTACT_PANEL_COLOR,
                        'default' => '#f1f5f8'
                    )),

                    new fieldColor('contact_panel_link_color', array(
                        'title' => LANG_NEOMESSENGER_CP_CONTACT_PANEL_LINK_COLOR,
                        'default' => '#6490b1'
                    )),

                    new fieldColor('search_panel_bg_color', array(
                        'title' => LANG_NEOMESSENGER_CP_SEARCH_PANEL_COLOR,
                        'default' => '#f1f5f8'
                    )),

                    new fieldColor('composer_bg_color', array(
                        'title' => LANG_NEOMESSENGER_CP_COMPOSER_BG_COLOR,
                        'default' => '#f1f5f8'
                    )),

                    new fieldColor('border_color', array(
                        'title' => LANG_NEOMESSENGER_CP_BORDER_COLOR,
                        'default' => '#c3d0d8'
                    )),

                    new fieldColor('contact_bg_color', array(
                        'title' => LANG_NEOMESSENGER_CP_CONTAСT_BG_COLOR,
                        'default' => '#ffffff'
                    )),

                    new fieldColor('contact_hover_bg_color', array(
                        'title' => LANG_NEOMESSENGER_CP_CONTAСT_HOVER_BG_COLOR,
                        'default' => '#f2f6fa'
                    )),

                    new fieldColor('contact_select_bg_color', array(
                        'title' => LANG_NEOMESSENGER_CP_CONTAСT_SELECT_BG_COLOR,
                        'default' => '#6490b1'
                    )),

                    new fieldColor('button_bg_color', array(
                        'title' => LANG_NEOMESSENGER_CP_BUTTON_BG_COLOR,
                        'default' => '#6490b1'
                    )),

                    new fieldColor('button_hover_bg_color', array(
                        'title' => LANG_NEOMESSENGER_CP_BUTTON_BG_HOVER_COLOR,
                        'default' => '#6898ba'
                    )),

                    new fieldColor('markitup_button_hover_bg_color', array(
                        'title' => LANG_NEOMESSENGER_CP_MARKITUP_BTN_BG_COLOR,
                        'default' => '#b1d6e4'
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