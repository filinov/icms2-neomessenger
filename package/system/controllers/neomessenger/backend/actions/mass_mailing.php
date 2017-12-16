<?php

class actionNeomessengerMassMailing extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();
        $users_model = cmsCore::getModel('users');
        $messenger = cmsCore::getController('messages');

        $form = $this->getForm('mass_mailing');

        // Форма отправлена?
        $is_submitted = $this->request->has('submit');

        // Парсим форму и получаем поля
        $mailing = $form->parse($this->request, $is_submitted);

        if ($is_submitted) {

            // Проверям правильность заполнения
            $errors = $form->validate($this,  $mailing);

            if (!$errors) {

                $content_html = cmsEventsManager::hook('html_filter', $mailing['message_text']);

                if (!$content_html) {
                    $errors = array('message_text' => LANG_VALIDATE_REQUIRED);
                }

                if (!$errors) {

                    $filters = $this->request->get('filters', array());

                    $users_model->resetFilters()->applyDatasetFilters(array('filters' => $filters));
                    $users_model->filterNotEqual('id', $user->id);

                    if ($mailing['groups'] && $mailing['groups'][0] > 0) {
                        $users_model->filterGroups($mailing['groups']);
                    }

                    $recipients = $users_model->getUsersIds();

                    if ($recipients) {

                        $messenger->addRecipients(array_keys($recipients));
                        $messenger->setSender($user->id);

                        if ($mailing['send_type'] === 'message') {

                            $messages_ids = $messenger->sendMessage($content_html);
                            $count = $messages_ids ? count($messages_ids) : 0;

                            cmsUser::addSessionMessage(LANG_NEOMESSENGER_CP_SENDED .' '. html_spellcount($count, LANG_NEOMESSENGER_CP_MESSAGES_SPELLCOUNT), 'success');

                        }

                        if ($mailing['send_type'] === 'notify') {

                            $notices_ids = $messenger->sendNoticePM(array(
                                'content' => $content_html
                            ));
                            $count = $notices_ids ? count($notices_ids) : 0;

                            cmsUser::addSessionMessage(LANG_NEOMESSENGER_CP_SENDED .' '. html_spellcount($count, LANG_NEOMESSENGER_CP_NOTICES_SPELLCOUNT), 'success');

                        }

                        $mailing['message_text'] = '';

                    }

                    if (!$recipients) {

                        cmsUser::addSessionMessage(LANG_NEOMESSENGER_CP_NOT_RECIPIENTS, 'info');

                    }

                }

            }

            if ($errors) {

                cmsUser::addSessionMessage(LANG_FORM_ERRORS, 'error');

            }

        }

        $filterForm = cmsTemplate::getInstance()->render('backend/users_filter', array(
            'fields' => $this->getUserFields()
        ));

        return cmsTemplate::getInstance()->render('backend/mass_mailing', array(
            'mailing' => $mailing,
            'form'    => $form,
            'filter'  => $filterForm,
            'errors'  => isset($errors) ? $errors : false
        ));

    }

    private function getUserFields() {

        $content_model = cmsCore::getModel('content')->setTablePrefix('');
        $fields  = $content_model->getContentFields('users');

        $fields[] = array(
            'title' => LANG_RATING,
            'name' => 'rating',
            'handler' => new fieldNumber('rating')
        );

        $fields[] = array(
            'title' => LANG_KARMA,
            'name' => 'karma',
            'handler' => new fieldNumber('karma')
        );

        return $fields;

    }

}