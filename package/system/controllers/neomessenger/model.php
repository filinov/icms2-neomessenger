<?php

class modelNeomessenger extends cmsModel {

    /**
     * Извлечение сообщений из базы от определенного контакта
     * @param $user_id
     * @param $contact_id
     * @return array|bool
     */
    public function getMessages($user_id, $contact_id) {

        $this->select('u.nickname', 'user_nickname');
        $this->select('u.avatar', 'user_avatar');
        $this->join('{users}', 'u', 'u.id = i.from_id');

        if ($this->filter_on) { $this->filterAnd(); }

        $this->filterStart();

        $this->filterEqual('from_id', $user_id);
        $this->filterEqual('to_id', $contact_id);
        $this->filterIsNull('is_deleted_from');

        $this->filterOr();

        $this->filterEqual('from_id', $contact_id);
        $this->filterEqual('to_id', $user_id);
        $this->filterIsNull('is_deleted_to');

        $this->filterEnd();

        $this->filterIsNull('is_deleted');

        $this->orderBy('id', 'desc');

        $messages = $this->get('{users}_messages', function($item, $model) {

            $item['id'] = (int)$item['id'];
            $item['to_id'] = (int)$item['to_id'];
            $item['from_id'] = (int)$item['from_id'];

            $item['user'] = array(
                'id'       => $item['from_id'],
                'nickname' => $item['user_nickname'],
                'url'      => href_to('users', $item['from_id']),
                'avatar'   => html_avatar_image_src($item['user_avatar'], 'micro')
            );

            $timestamp = strtotime($item['date_pub']);

            if ($timestamp + 86400 < time()) {
                $format = 'j F';
                if (date('Y', $timestamp) !== date('Y')) {
                    $format .= ' Y';
                }
            } else {
                $format = 'H:i';
            }

            $new_date = date($format, $timestamp);

            $item['time'] = lang_date($new_date);

            return $item;

        }, false);

        return is_array($messages) ? array_reverse($messages) : false;

    }

    /**
     * Извлечение сообщений из базы от всех контактов
     * @param $user_id
     * @return array
     */
    public function getMessagesFromAllContacts($user_id) {

        $this->select('u.nickname', 'user_nickname');
        $this->select('u.avatar', 'user_avatar');
        $this->join('{users}', 'u', 'u.id = i.from_id');

        if ($this->filter_on) { $this->filterAnd(); }

        $this->filterStart();
        $this->filterEqual('to_id', $user_id);
        $this->filterIsNull('is_deleted_to');
        $this->filterIsNull('is_deleted');
        $this->filterEnd();

        $this->orderBy('id');

        $messages = $this->get('{users}_messages', function($item, $model) {

            $item['id'] = (int)$item['id'];
            $item['to_id'] = (int)$item['to_id'];
            $item['from_id'] = (int)$item['from_id'];

            $item['user'] = array(
                'id'       => $item['from_id'],
                'nickname' => $item['user_nickname'],
                'url'      => href_to('users', $item['from_id']),
                'avatar'   => html_avatar_image_src($item['user_avatar'], 'micro')
            );

            $timestamp = strtotime($item['date_pub']);

            if ($timestamp + 86400 < time()) {
                $format = 'j F';
                if (date('Y', $timestamp) !== date('Y')) {
                    $format .= ' Y';
                }
            } else {
                $format = 'H:i';
            }

            $new_date = date($format, $timestamp);

            $item['time'] = lang_date($new_date);

            return $item;

        }, false);

        return $messages;

    }

    /**
     * Получение списка контактов
     * @param $user_id
     * @return array
     */
    public function getContacts($user_id) {

        $this->select('u.id', 'id');
        $this->select('u.nickname', 'nickname');
        $this->select('u.avatar', 'avatar');
        $this->select('u.is_admin', 'is_admin');
        $this->select('IFNULL(COUNT(m.id), 0)', 'new_messages');

        $this->join('{users}', 'u', 'u.id = i.contact_id');
        $this->joinLeft('{users}_messages', 'm', 'm.from_id = i.contact_id AND m.to_id = i.user_id AND m.is_new = 1 AND m.is_deleted_to IS NULL');

        $this->filterEqual('user_id', $user_id);

        $this->groupBy('i.contact_id');

        $this->orderBy('date_last_msg', 'desc');

        return $this->get('{users}_contacts', function($item, $model) {

            $user = cmsUser::getInstance();

            $item['id'] = (int)$item['contact_id'];
            $item['is_online'] = cmsUser::userIsOnline($item['contact_id']);
            $item['url'] = href_to('users', $item['contact_id']);
            $item['avatar'] = html_avatar_image_src($item['avatar'], 'micro');
            $item['is_ignored'] = (bool)$model->isContactIgnored($user->id, $item['contact_id']);

            return $item;

        }, false);

    }

    /**
     * В игноре ли контакт
     * @param $user_id
     * @param $contact_id
     * @return int
     */
    public function isContactIgnored($user_id, $contact_id) {

        $this->filterEqual('user_id', $user_id);
        $this->filterEqual('ignored_user_id', $contact_id);
        $this->limit(1);

        $is_ignored = $this->getCount('{users}_ignors');

        $this->resetFilters();

        return $is_ignored;

    }

    /**
     * ID последнего сообщения
     * @param $user_id
     * @return int
     */
    public function getLastMessageID($user_id) {

        $this->filterEqual('to_id', $user_id);
        $this->filterIsNull('is_deleted_to');
        $this->filterIsNull('is_deleted');

        $this->orderBy('id', 'desc');

        return (int)$this->getFieldFiltered('{users}_messages', 'id');

    }

    /**
     * Количество новых сообщений
     * @param $user_id
     * @return int
     */
    public function getNewMessagesCount($user_id) {

        $this->filterEqual('to_id', $user_id);
        $this->filterEqual('is_new', 1);
        $this->filterIsNull('is_deleted_to');
        $this->filterIsNull('is_deleted');

        $count = $this->getCount('{users}_messages');

        return $count;

    }

    /**
     * Удаление контроллера
     * @param $id
     * @return bool
     */
    public function deleteController($id) {

        parent::deleteController($id);

        $this->filterEqual('controller', 'neomessenger')->deleteFiltered('scheduler_tasks');

        return true;

    }

}