<?php

$errors = $_SESSION['create_user']['errors'] ?? [];

if (isset($_SESSION['create_user'])) {
    unset($_SESSION['create_user']);
}

if (!isset($roles) || !is_array($roles)) {
    if (class_exists('UserRole')) {
        $roles = UserRole::getAllRoles();
    } else {
        $roles = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create User</title>
</head>
<body>
    <?php if (!empty($errors) && is_array($errors)): ?>
        <div>
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="/proiect_php/sala/users/create" method="POST">
   
        <p>
            <label for="first_name">First Name</label><br>
            <input type="text" name="first_name" id="first_name" value="">
        </p>

        <p>
            <label for="last_name">Last Name</label><br>
            <input type="text" name="last_name" id="last_name" value="">
        </p>

        <p>
            <label for="email">Email</label><br>
            <input type="email" name="email" id="email" value="">
        </p>

        <p>
            <label for="password">Password</label><br>
            <input type="password" name="password" id="password" value="">
        </p>

        <p>
            <label for="role_id">Role</label><br>
            <select name="role_id" id="role_id">
                <?php if (!empty($roles)): ?>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= (int)$role['role_id'] ?>">
                            <?= htmlspecialchars($role['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="2">user</option>
                <?php endif; ?>
            </select>
        </p>

        <button type="submit">Creează utilizator</button>
    </form>
</body>
</html>
