<h2>伝言メモ確認</h2>
<?php if (empty($messages)): ?>
    <div>伝言メモはありません</div>
<?php else : ?>
    <form action="<?php echo $base_url; ?>/message/index" method="post">
<table>
    <tr>
        <th>メッセージID</th>
        <th>相手先文門</th>
        <th>相手先電話</th>
        <th>相手先名前</th>
        <th>伝言区分</th>
        <th>伝言</th>
        <th>削除</th>
    </tr>
    <?php foreach ($messages as $message): ?>
        <tr>
            <td><?php echo $message['message_id']; ?></td>
            <td><?php echo $message['pass_sec']; ?></td>
            <td><?php echo $message['pass_tel']; ?></td>
            <td><?php echo $message['pass_name']; ?></td>
            <td><?php echo $msec_list[$message['msec']]; ?></td>
            <td><?php echo $message['message']; ?></td>
            <td>
                <label>
                    <input type="radio" name="delete[<?php echo $message['message_id']; ?>][]" value="0" checked="checked" />削除しない
                </label>
                <label>
                    <input type="radio" name="delete[<?php echo $message['message_id']; ?>][]" value="1" />削除する
                </label>
            </td>
        </tr>
        <?php endforeach; ?>
</table>
<div><input type="submit" value="更新" /></div>
</form>
<?php endif; ?>