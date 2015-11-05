<?php

class modelNeomessenger extends cmsModel {

    public function getMessages($user_id, $contact_id) {

        $this->select('u.nickname', 'user_nickname');
        $this->join('{users}', 'u', 'u.id = i.from_id');

        if ($this->filter_on) { $this->filterAnd(); }

        $this->filterStart();
        $this->filterStart();
        $this->filterEqual('from_id', $user_id);
        $this->filterEqual('to_id', $contact_id);
        $this->filterEnd();

        $this->filterOr();

        $this->filterStart();
        $this->filterEqual('from_id', $contact_id);
        $this->filterEqual('to_id', $user_id);
        $this->filterEnd();
        $this->filterEnd();

        $this->orderBy('id', 'desc');

        $messages = $this->get('{users}_messages');

        return is_array($messages) ? array_reverse($messages) : false;

    }

}