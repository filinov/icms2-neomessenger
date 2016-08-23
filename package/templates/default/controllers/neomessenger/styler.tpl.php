<style>
    .nm-header {
        background-color: <?php echo $this->options['head_bg_color'] ?> !important;
    }
    .nm-title {
        color: <?php echo $this->options['title_color'] ?> !important;
    }
    .nm-search-c-wrap {
        background-color: <?php echo $this->options['search_panel_bg_color'] ?> !important;
    }
    #nm-contact-panel {
        background-color: <?php echo $this->options['contact_panel_bg_color'] ?> !important;
    }
    #nm-contact-panel a {
        color: <?php echo $this->options['contact_panel_link_color'] ?> !important;
    }
    .nm-right, .nm-search-c-wrap, #nm-contact-panel, #nm-composer {
        border-color: <?php echo $this->options['border_color'] ?> !important;
    }
    #nm-userlist li {
        background-color: <?php echo $this->options['contact_bg_color'] ?> !important;
    }
    #nm-userlist li:hover {
        background-color: <?php echo $this->options['contact_hover_bg_color'] ?> !important;
    }
    #nm-userlist li.selected {
        background-color: <?php echo $this->options['contact_select_bg_color'] ?> !important;
    }

    #nm-userlist li .username {
        color: <?php echo $this->options['contact_select_bg_color'] ?> !important;
    }
    #nm-userlist li.selected .username {
        color: <?php echo $this->options['contact_bg_color'] ?> !important;
    }

    #nm-composer {
        background-color: <?php echo $this->options['composer_bg_color'] ?> !important;
    }

    .nm-button {
        background-color: <?php echo $this->options['button_bg_color'] ?> !important;
    }
    .nm-button:hover {
        background-color: <?php echo $this->options['button_hover_bg_color'] ?> !important;
    }

    #nm-composer .markItUpHeader ul a:hover {
        background-color: <?php echo $this->options['markitup_button_hover_bg_color'] ?> !important;
    }

    #nm-userlist li .counter {
        background: <?php echo $this->options['contact_select_bg_color'] ?> !important;
        color: <?php echo $this->options['contact_bg_color'] ?> !important;
    }
    #nm-userlist li.selected .counter {
        background: <?php echo $this->options['contact_bg_color'] ?> !important;
        color: <?php echo $this->options['contact_select_bg_color'] ?> !important;
    }
</style>
