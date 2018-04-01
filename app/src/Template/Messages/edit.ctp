<h1>Edit Message</h1>
<?php
    echo $this->Form->create($message);
    echo $this->Form->control('user_id', ['type' => 'hidden']);
    echo $this->Form->control('body', ['rows' => '3']);
    echo $this->Form->button(__('Save Message'));
    echo $this->Form->end();
?>