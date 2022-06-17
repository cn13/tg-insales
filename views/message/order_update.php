<?php
/** @var $order array */

?>
Изменения в заказе <?= $order['number'] ?>:
<?php
foreach ($order['order_changes'] as $change): ?>
    <?= date('d.m.Y в H:i', strtotime($change['created_at'])) ?>
    <?= $change['full_description'] ?>
<?php
endforeach; ?>
Сумма: <?= $order['total_price'] ?>
