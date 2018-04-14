<!-- File: src/Template/Articles/index.ctp -->

<h1>Messages</h1>
<!--Removed as the form is now on the same page-->
<!--<?= $this->Html->link('Write a message', ['action' => 'add']) ?>-->
<table id="messages-table">
    <tr>
        <th>Message</th>
        <th>Created</th>
    </tr>

    <!-- Here is where we iterate through our $articles query object, printing out article info -->

    <?php foreach ($messages as $message): ?>
        <tr class="message-row">
            <td>
                <?= $message->body ?>
            </td>
            <td>
                <?= $message->created->format(DATE_RFC850) ?>
            </td>
            <td>
                <?= $this->Html->link('Edit', ['action' => 'edit', $message->slug]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<h2>Add a new message!</h2>
<?php
    echo $this->Form->create($newMessage, ['id' => 'new-message-form']);
    echo $this->Form->control('body', ['id' => 'form-message-body']);
    echo $this->Form->button('Save Message', ['id' => 'form-submit']);
    echo $this->Form->end();
    echo $this->Html->script('jquery');
    echo $this->Html->script('dateFormat');
?>
<script type="text/javascript">
    $('button#form-submit').click(function(event) {
        event.preventDefault();
        $.post('', $('form#new-message-form').serialize(), function(data) {
            var t = $('table#messages-table');
            $('.message-row').remove();
            data.forEach(function(currentMessage) {
                t.append('<tr class="message-row"><td>' + currentMessage.body + '</td><td>' + DateFormat.format.date(currentMessage.created, 'ddd, dd-MMM-yy HH:mm:ss UTC') + '</td></tr>');
            })
        }, 'json');
        $('textarea#form-message-body').val('');
    });
</script>
