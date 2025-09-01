<?php

class User {
    public static function getAllUsers() {
        global $pdo;
        $sql = "SELECT * FROM users";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUser($user_id) {
        global $pdo;

        $sql = "SELECT * FROM users WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":user_id" => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUserByEmail($email) {
        global $pdo;

        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":email" => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updateUser($user_id, $first_name, $last_name, $email) {
        global $pdo;

        $sql = "UPDATE users
                SET first_name = :first_name, last_name = :last_name, email = :email
                WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ":user_id" => $user_id,
            ":first_name" => $first_name,
            ":last_name" => $last_name,
            ":email" => $email
        ]);
    }

    public static function deleteUser($user_id) {
    global $pdo;

    
    $stmt1 = $pdo->prepare("DELETE FROM user_abonamente WHERE user_id = :user_id");
    $stmt1->execute([':user_id' => $user_id]);

   
    $stmt2 = $pdo->prepare("DELETE FROM users WHERE user_id = :user_id");
    $stmt2->execute([':user_id' => $user_id]);
}

    public static function createUser($first_name, $last_name, $email, $pass, $role_id) {
        global $pdo;

        $sql = "INSERT INTO users (first_name, last_name, email, password, role_id)
                VALUES (:first_name, :last_name, :email, :password, :role_id)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ":first_name" => $first_name,
            ":last_name" => $last_name,
            ":email" => $email,
            ":password" => $pass,
            ":role_id" => $role_id
        ]);

        return $pdo->lastInsertId();
    }

 public static function hasPermission($user_id, $permission_name){
    global $pdo;

    $sql = "SELECT COUNT(*) as count
            FROM users u
            JOIN roles_permissions rp ON u.role_id = rp.role_id
            WHERE u.user_id = :user_id AND rp.permission_name = :permission_name";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":user_id" => $user_id,
        ":permission_name" => $permission_name
    ]);

    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($res && isset($res["count"])) ? ($res["count"] > 0) : false;
}

public static function countAdmins(): int {
    global $pdo;
    $sql = "SELECT COUNT(*) as total FROM users u
            JOIN user_roles r ON u.role_id = r.role_id
            WHERE r.name = 'admin'";
    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int) $row['total'];
}
}

class UserRole {
    public static function getAllRoles(): array {
        global $pdo;
        $sql = "SELECT role_id, name FROM user_roles ORDER BY role_id";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRole($role_id): ?array {
        global $pdo;
        $sql = "SELECT role_id, name FROM user_roles WHERE role_id = :role_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':role_id' => (int)$role_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
?>
