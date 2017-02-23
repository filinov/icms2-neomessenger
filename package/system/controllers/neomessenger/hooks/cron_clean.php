<?php

class onNeomessengerCronClean extends cmsAction {

    public function run() {

        $this->model->filterNotNull('is_deleted_to');
        $this->model->filterNotNull('is_deleted_from');
        $this->model->lockFilters();

        $delete_msg_ids = $this->model->selectOnly('id')->get('{users}_messages', function($item, $model) {
            return $item['id'];
        }, false);

        $this->model->unlockFilters();

        if ($delete_msg_ids) {

            $this->model->deleteFiltered('{users}_messages');

            cmsEventsManager::hookAll('neomessenger_messages_delete', $delete_msg_ids);

        }

        return true;

    }

}