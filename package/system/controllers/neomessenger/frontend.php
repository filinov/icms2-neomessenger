<?php

/**
 * Class neomessenger
 */
class neomessenger extends cmsFrontend {

    /**
     * @var bool
     */
    protected $useOptions = true;

    /**
     * Все запросы могут быть выполнены только авторизованными и только по аякс
     * @param type $action_name
     * @return bool
     */
    public function before($action_name) {

        parent::before($action_name);

        if (!$this->request->isAjax()) { cmsCore::error404(); }

        if (!cmsUser::isLogged()) { cmsCore::error404(); }

        return true;

    }

    /**
     * @return array
     */
    public function getNmOptions() {

        return array(
            'refreshInterval'    => $this->options['refresh_interval'],
            'send_enter'         => (bool)$this->options['send_enter'],
            'close_backdrop'     => (bool)$this->options['close_backdrop'],
            'show_widget_button' => (bool)$this->options['show_widget_button'],
            'is_title_count'     => (bool)$this->options['is_title_count'],
            'is_favicon_count'   => (bool)$this->options['is_favicon_count'],
            'root_url'           => cmsConfig::get('root')
        );

    }

    /**
     * @return array
     */
    public function getCurrentUser() {

        $user = cmsUser::getInstance();

        return array(
            'id'       => $user->id,
            'nickname' => $user->nickname,
            'avatar'   => html_avatar_image_src($user->avatar, 'micro'),
            'is_admin' => $user->is_admin
        );

    }

    /**
     * @return string
     */
    public function getHtmlEditor() {

        $editor = $this->options['html_editor'];
        $field_id = 'nm-msg-field';
        $options = array(
            'id' => 'editor-' . $field_id,
            'placeholder' => 'Введите ваше сообщение...'
        );

        if (!$editor || $editor == 'default') {

            return cmsTemplate::getInstance()->renderInternal($this, 'editor', array(
                'field_id' => $field_id,
                'options' => $options
            ));

        } else {

            $editor_controller = cmsCore::getController($editor, new cmsRequest(array(), cmsRequest::CTX_INTERNAL));

            return $editor_controller->getEditorWidget($field_id, '', $options);

        }


    }

}