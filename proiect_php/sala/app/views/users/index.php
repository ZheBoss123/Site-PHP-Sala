<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>Users</title>
</head>
<body>
<h1>All Users</h1>
<a href="/proiect_php/sala/">Back</a>
<?php if ($create_permission): ?>
    <a href="create" style="background-color: blue; color: white;">Create</a>
<?php endif; ?>

<table>
    <tr>
        <th>First Name</th>
        <th>Last Name</th>  
        <th>Email</th>
        <th>Action</th>
    </tr>
    <?php foreach ($users as $user) : ?>
        <tr>
            <td><?= $user["first_name"] ?></td>
            <td><?= $user["last_name"] ?></td>
            <td><?= $user["email"] ?></td>
            <td>
  <a href="/proiect_php/sala/users/show?user_id=<?= $user['user_id'] ?>">Show</a>
  <?php if ($is_admin): ?>
      | <a href="/proiect_php/sala/users/edit?user_id=<?= $user['user_id'] ?>">Edit</a>
      | <a href="/proiect_php/sala/users/delete?user_id=<?= $user['user_id'] ?>" onclick="return confirm('Sigur vrei să ștergi acest user?');">Delete</a>
  <?php endif; ?>
</td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
