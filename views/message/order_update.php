<?php
/** @var $order array */

?>
<b>Изменения в заказе <?= $order['number'] ?>:</b>
<?php
foreach ($order['order_changes'] as $change): ?>
    <?= date('d.m.Y в H:i', strtotime($change['created_at'])) ?><br>
    <?= $change['full_description'] ?>
    <hr>
<?php
endforeach; ?>
Сумма: <?= $order['total_price'] ?>
