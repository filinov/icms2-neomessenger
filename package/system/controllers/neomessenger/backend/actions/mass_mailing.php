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

                    $users_model->resetFilters();

                    if ($mailing['groups'] && $mailing['groups'][0] > 0) {
                        $users_model->filterGroups($mailing['groups']);
                    }

                    $recipients = $users_model->getUsersIds();

                    if ($recipients) {
                        $recipients = $recipients ? array_keys($recipients) : false;
                        if (($key = array_search($user->id, $recipients)) !== FALSE) {
                            unset($recipients[$key]);
                        }
                    }

                    if ($recipients) {

                        $messenger->addRecipients($recipients);

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

        return cmsTemplate::getInstance()->render('backend/mass_mailing', array(
            'mailing'  => $mailing,
            'form'     => $form,
            'errors'   => isset($errors) ? $errors : false
        ));

    }

}