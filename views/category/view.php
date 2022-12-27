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
    /** @var \app\models\GoodSite[] $good */
    $i=1;foreach ($goods as $good): ?>
        <tr>
            <th scope="row"><?=$i++?></th>
            <td><?= $good->name ?></td>
            <td>0</td>
            <td>0 руб</td>
        </tr>
    <?php
    endforeach; ?>
    </tbody>
</table>