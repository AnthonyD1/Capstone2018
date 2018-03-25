<!-- File: src/Template/Articles/index.ctp -->

<h1>Messages</h1>
<!--Removed as the form is now on the same page-->
<!--<?= $this->Html->link('Write a message', ['action' => 'add']) ?>-->
<table>
    <tr>
        <th>Message</th>
        <th>Created</th>
    </tr>

    <!-- Here is where we iterate through our $articles query object, printing out article info -->

    <?php foreach ($messages as $message): ?>
        <tr>
            <td>
                <?= $message->body ?>
            </td>
            <td>
                <?= $message->created->format(DATE_RFC850) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<h2>Add a new message!</h2>
<?php
    echo $this->Form->create($newMessage);
    echo $this->Form->control('body');
    echo $this->Form->button(__('Save Message'));
    echo $this->Form->end();
?>