<?php
/** @var $order array */

?>
<b>Изменения в заказе <?= $order['number'] ?>:</b>
<?php
foreach ($order['order_changes'] as $change): ?>
* <?= date('d.m.Y H:i', strtotime($change['created_at'])) ?> <?= $change['full_description'] ?>
<?php
endforeach; ?>
Сумма: <?= $order['total_price'] ?>
