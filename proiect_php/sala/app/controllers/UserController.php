<?php
require_once "app/models/User.php";
require_once "app/models/User.php";
require_once "app/models/User.php";

class UserController{
   public static function index() {
    $users = User::getAllUsers();
    
    $create_permission = (
        isset($_SESSION["request_user"]) &&
        User::hasPermission($_SESSION["request_user"]["user_id"], "create_user")
    );

    
    $is_admin = false;
    if (isset($_SESSION["request_user"])) {
        $role = UserRole::getRole($_SESSION["request_user"]["role_id"]);
        if ($role && $role["name"] === "admin") {
            $is_admin = true;
        }
    }

    require_once "app/views/users/index.php";
}

   public static function show() {
    $user_id = $_GET['user_id'];
    $user = User::getUser($user_id);

    if ($user) {
       
        $role = UserRole::getRole($user['role_id']);
        $user['role_name'] = $role ? $role['name'] : 'Necunoscut';

        require_once "app/views/users/show.php";
    } else {
        $_SESSION['error'] = "User not found";
        require_once "app/views/404.php";
    }
}


    static function data_validation() {
        $errors = [];
        $len_name = strlen($_POST['last_name']);
        if ($len_name < 1 || $len_name > 32) {
            $errors['last_name_error'] = 'Last name must be between 1 and 32 characters';  
        }
        if (strpos($_POST['email'], '@') === false) {
            $errors['email_error'] = 'Invalid email';
        }
        if (isset($_POST['password']) && strlen($_POST['password']) < 8) {
            $errors['password_error'] = 'Password must be at least 8 characters';
        }
        if (isset($_POST['role_id']) && !UserRole::getRole($_POST['role_id'])) {
            $errors['role_error'] = 'Invalid role';
        }

        return $errors;
    }

public static function edit() {
    
    if (!isset($_SESSION["request_user"]) ||
        !User::hasPermission($_SESSION["request_user"]["user_id"], "edit_user")
    ) {
        $_SESSION["error"] = "Invalid permissions";
        require_once "app/views/404.php";
        return;
    }

  
    $user_id = $_GET['user_id'] ?? $_POST['user_id'] ?? null;
    if (!$user_id) {
        $_SESSION["error"] = "User ID missing";
        require_once "app/views/404.php";
        return;
    }

    
    $role = UserRole::getRole($_SESSION["request_user"]["role_id"] ?? 0);

    
    if (!$role || $role["name"] !== "admin") {
        if ($user_id != $_SESSION["request_user"]["user_id"]) {
            $_SESSION["error"] = "Invalid permissions";
            require_once "app/views/404.php";
            return;
        }
    }

    
    $user = User::getUser($user_id);
    if (!$user) {
        $_SESSION['error'] = "User not found";
        require_once "app/views/404.php";
        return;
    }

   
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $errors = self::data_validation();
        if (count($errors) > 0) {
            $_SESSION["edit_user"] = $errors;
            header("Location: /proiect_php/sala/users/edit?user_id=" . $user_id);
            exit;
        }

      
        User::updateUser(
            $user_id,
            htmlentities($_POST['first_name']),
            htmlentities($_POST['last_name']),
            htmlentities($_POST['email'])
        );

        
        header("Location: /proiect_php/sala/users/index");
        exit;
    }

    
    require_once "app/views/users/edit.php";
}


   public static function delete() {
    $user_id = $_GET['user_id'] ?? null;
    if (!$user_id) {
        $_SESSION['error'] = "User not found";
        header("Location: /proiect_php/sala/users/index");
        exit;
    }

    $user = User::getUser($user_id);
    if (!$user) {
        $_SESSION['error'] = "User not found";
        header("Location: /proiect_php/sala/users/index");
        exit;
    }

    
    $role = UserRole::getRole($user['role_id']);
    if ($role['name'] === 'admin') {
        $admin_count = User::countAdmins();
        if ($admin_count <= 1) {
            $_SESSION['error'] = "Nu poți șterge ultimul admin!";
            header("Location: /proiect_php/sala/users/index");
            exit;
        }
    }

    
    User::deleteUser($user_id);
  
    header("Location: /proiect_php/sala/users/index");
    exit;
}

  public static function create() {
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = $_POST['first_name'] ?? '';
            $last_name  = $_POST['last_name'] ?? '';
            $email      = $_POST['email'] ?? '';
            $password   = $_POST['password'] ?? '';
            $role_id    = (int)($_POST['role_id'] ?? 2);

            
            $hashed = password_hash($password, PASSWORD_DEFAULT);



            $id = User::createUser($first_name, $last_name, $email, $hashed, $role_id);
            
            
          
            if ($id) {
                header("Location: /proiect_php/sala/");
                exit;
            } else {
                echo "Eroare la creare user.";
            }
        }

        require_once "app/Views/users/create.php";
    }
}
?>