<?php

class onNeomessengerMenuBeforeList extends cmsAction {

    public function run($menu) {

        foreach ($menu as $key => $item) {

            if ($item['menu_name'] === 'personal') {

                if ($item['url'] === '{messages:view}') {

                    $menu[$key]['url'] = '{neomessenger:messages}';

                }

            }

        }

        return $menu;

    }

}
