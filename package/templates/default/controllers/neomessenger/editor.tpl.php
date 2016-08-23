<form action="<?php echo $this->href_to('send_message'); ?>" method="post">
    <div class="nm-editor">
        <?php echo html_editor($field_id, '', $options); ?>
    </div>
    <div class="nm-buttons">
        <input type='button' class='nm-button pull-right' value='<?php echo LANG_SEND; ?>' onclick='icms.neomessenger.messages.send()'/>
    </div>
</form>


