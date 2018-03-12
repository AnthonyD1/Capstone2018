<h1>Post a Message</h1>
<?php
    echo $this->Form->create($message);
    echo $this->Form->control('body');
    echo $this->Form->button(__('Save Article'));
    echo $this->Form->end();
?>