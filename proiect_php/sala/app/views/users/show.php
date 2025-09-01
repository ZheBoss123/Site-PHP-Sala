<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>User profile</title>
</head>
<body>
    <h1>User profile</h1>
    <p>First Name: <?= $user["first_name"] ?></p>
    <p>Last Name: <?= $user["last_name"] ?></p>
    <p>Email: <?= $user["email"] ?></p>
    <p>Abonament: <?= $user['abonament_name'] ?? 'NULL' ?></p>
    <p>Rol: <?= htmlspecialchars($user['role_name']) ?></p>

    
    <a href="index">Back</a>
</body>
</html>