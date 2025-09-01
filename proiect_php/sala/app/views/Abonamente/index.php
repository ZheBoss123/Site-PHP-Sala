<?php

require_once __DIR__ . "/../../models/User.php"; 


$is_admin = false;
if (isset($_SESSION["request_user"])) {
    $role = UserRole::getRole($_SESSION["request_user"]["role_id"] ?? 0);
    if ($role && $role["name"] === "admin") {
        $is_admin = true;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>Toate Abonamentele</title>
</head>
<body>
<h1>Toate Abonamentele</h1>

<?php if (isset($_SESSION['success'])): ?>
    <div style="color: green; margin-bottom: 10px;">
        <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<p>
    <a href="/proiect_php/sala/" style="text-decoration:none; padding:6px 12px; background:#3498db; color:white; border-radius:4px;">Back</a>
</p>

<table>
    <tr>
        <th>Abonament</th>
        <th>Preț</th>
        <th>Privilegii</th>
        <th>Data achiziției</th>
        <th>Acțiuni</th>
    </tr>

    <?php foreach ($abonamente as $ab): ?>
    <tr>
        <td><?= htmlspecialchars($ab['abonament_name']) ?></td>
        <td><?= number_format($ab['display_price'], 2) ?></td>
        <td>
            <?= !empty($ab['facilitati']) ? implode(', ', $ab['facilitati']) : 'Nicio facilitate' ?>
        </td>
        <td>
            <?= !empty($ab['user_purchase_date']) ? htmlspecialchars($ab['user_purchase_date']) : '—' ?>
        </td>
        <td>
            <?php if (!empty($ab['can_buy']) && $ab['can_buy']): ?>
                <a href="/proiect_php/sala/Abonamente/buy?abonament_id=<?= $ab['abonament_id'] ?>">Cumpără</a> |
            <?php endif; ?>
            <a href="/proiect_php/sala/Abonamente/show?abonament_id=<?= $ab['abonament_id'] ?>">Show</a>
            <?php if ($is_admin): ?>
                | <a href="/proiect_php/sala/Abonamente/edit?abonament_id=<?= $ab['abonament_id'] ?>">Edit</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
