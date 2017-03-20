<?php

    $this->addControllerJS('editor');

    $options = array(
        'id' => 'editor-nm-msg-field',
        'placeholder' => LANG_NM_ENTER_MESSAGE
    );

?>
<div id="nm-editor">
    <form action="<?php echo $this->href_to('send_message'); ?>" method="post">
        <div class="nm-editor">
            <?php echo html_editor('nm-msg-field', '', $options); ?>
        </div>
        <div class="nm-buttons">
            <input type='button' class='nm-button nm-submit-button pull-right' value='<?php echo LANG_SEND; ?>'/>
        </div>
    </form>
</div>