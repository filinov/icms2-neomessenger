<?php

class neomessenger extends cmsFrontend {

    protected $useOptions = true;
    public $messenger;

    function __construct($request) {

        $this->messenger = cmsCore::getController("messages");

        parent::__construct($request);

    }

    /**
     * Все запросы могут быть выполнены только авторизованными и только по аякс
     * @param type $action_name
     */
    public function before($action_name) {

        parent::before($action_name);

        if (!$this->request->isAjax()) { cmsCore::error404(); }

        if (!cmsUser::isLogged()) { cmsCore::error404(); }

        return true;

    }

    public function addPluginToPage() {

        $template = cmsTemplate::getInstance();
        $user = cmsUser::getInstance();

        $opt = array(

            'currentUser' => array(

                'id'       => $user->id,
                'nickname' => $user->nickname,
                'avatar'   => html_avatar_image_src($user->avatar, 'micro'),
                'is_admin' => $user->is_admin

            ),

            'options' => array(

                'refreshInterval'  => $this->options['refresh_interval'],
                'sendType'         => $this->options['send_type'],
                'isBackdropClose'  => $this->options['is_backdrop_close'],
                'is_flip_buttons'  => $this->options['is_flip_buttons'] === 1,
                'is_title_count'   => (bool)$this->options['is_title_count'],
                'is_favicon_count' => (bool)$this->options['is_favicon_count'],

            ),

            'soundsPath' => cmsConfig::get('upload_host') . '/neomessenger/sounds/',
            'htmlEditor' => self::getHtmlEditor()

        );

        $template->addCSS($template->getStylesFileName($this->name));
        $template->addJS($template->getJavascriptFileName($this->name . "/libs/favico"));
        $template->addJS($template->getJavascriptFileName($this->name . "/libs/isMobile"));
        $template->addJS($template->getJavascriptFileName($this->name . "/libs/animatetitle"));
        $template->addJS($template->getJavascriptFileName($this->name . "/libs/jquery.waitforimages"));

        $template->addJS($template->getJavascriptFileName($this->name . "/neomessenger"));

        $template->addHead("<script>var nm_options = " . json_encode($opt) . "</script>");

    }

    public static function getHtmlEditor() {

        $field_id = 'nm-msg-field';

        $options = array(
            'id' => 'editor-' . $field_id
        );

        return html_editor($field_id, '', $options);

    }

}