<style>
    .fa-check {
        color: lightgreen !important;
    }

    .fa-close {
        color: red !important;
    }
</style>
<table class="table table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>Название</th>
        <th>Наличие</th>
        <th>Цена</th>
    </tr>
    </thead>
    <tbody>
    <?php
    /** @var \app\models\Good[] $good */
    $i = 1;
    foreach ($goods as $good): ?>
        <tr>
            <th scope="row"><?= $i++ ?></th>
            <td><?= $good->name ?></td>
            <td><i class="cust fa fa-<?= $good->balance > 0 ? "check" : "close" ?>"></i></td>
            <td><?= $good->price ?> руб</td>
        </tr>
    <?php
    endforeach; ?>
    </tbody>
</table>