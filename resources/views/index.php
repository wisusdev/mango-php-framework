<?php $i = 1; ?>
<?php foreach ($users as $user): ?>
    <tr>
        <td><?= $i ?></td>
        <td><?= $user->first_name ?></td>
        <td><?= $user->last_name ?></td>
        <td><?= $user->email ?></td>
        <td><?= $user->age ?></td>
        <td><?= $user->country ?></td>
    </tr>
	<?php $i++; ?>
<?php endforeach; ?>