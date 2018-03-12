<!-- File: src/Template/Articles/index.ctp -->

<h1>Messages</h1>
<table>
    <tr>
        <th>Message</th>
        <th>Created</th>
    </tr>

    <!-- Here is where we iterate through our $articles query object, printing out article info -->

    <?php foreach ($articles as $article): ?>
        <tr>
            <td>
                <?= $article->body ?>
            </td>
            <td>
                <?= $article->created->format(DATE_RFC850) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>