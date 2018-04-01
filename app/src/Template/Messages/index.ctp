<!-- File: src/Template/Articles/index.ctp -->

<h1>Messages</h1>
<?= $this->Html->link('Write a message', ['action' => 'add']) ?>
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
            <td>
                <?= $this->Html->link('Edit', ['action' => 'edit', $message->slug]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>