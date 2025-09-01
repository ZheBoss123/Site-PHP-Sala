<?php

require_once "app/models/User.php";
require_once "app/models/User.php";
require_once "app/models/Abonament.php";

class AbonamentController {

  
public static function index() {
    global $pdo;

    $current_user_id = $_SESSION["request_user"]["user_id"];

   
    $stmt = $pdo->query("SELECT * FROM abonamente ORDER BY abonament_id ASC");
    $abonamente = $stmt->fetchAll(PDO::FETCH_ASSOC);

   
    foreach ($abonamente as &$ab) {
      
        $stmt2 = $pdo->prepare("
            SELECT f.facilitate_name 
            FROM privilegiu p
            JOIN facilitati f ON p.facilitate_id = f.facilitate_id
            WHERE p.abonament_id = :id
        ");
        $stmt2->execute([':id' => $ab['abonament_id']]);
        $ab['facilitati'] = $stmt2->fetchAll(PDO::FETCH_COLUMN);

      
        $stmt3 = $pdo->prepare("
            SELECT purchase_date FROM user_abonamente 
            WHERE user_id = :user_id AND abonament_id = :abonament_id
        ");
        $stmt3->execute([
            ':user_id' => $current_user_id,
            ':abonament_id' => $ab['abonament_id']
        ]);
        $purchase = $stmt3->fetch(PDO::FETCH_ASSOC);
        $ab['user_purchase_date'] = $purchase ? $purchase['purchase_date'] : null;
    }
    unset($ab);

    
    $discount_accum = 0;
    foreach ($abonamente as &$ab) {
        if (empty($ab['user_purchase_date'])) { // doar abonamente ne-cumpărate de user
            $ab['display_price'] = max(0, $ab['total_price'] - $discount_accum);
        } else {
            $discount_accum += $ab['total_price'];
            $ab['display_price'] = 0; // deja cumpărat
        }
    }
    unset($ab);
$stmt4 = $pdo->prepare("
    SELECT MAX(abonament_id) as max_id 
    FROM user_abonamente 
    WHERE user_id = :user_id
");
$stmt4->execute([':user_id' => $current_user_id]);
$userMax = $stmt4->fetch(PDO::FETCH_ASSOC);
$currentMaxAbonamentId = $userMax['max_id'] ?? null;

// Salvăm informația în fiecare abonament pentru view
foreach ($abonamente as &$ab) {
    $ab['can_buy'] = is_null($currentMaxAbonamentId) || $ab['abonament_id'] > $currentMaxAbonamentId;
}
unset($ab);
    

    require_once "app/views/abonamente/index.php";
}


    // Arată un abonament
   public static function show() {
    global $pdo;

    $abonament_id = $_GET['abonament_id'] ?? null;
    if (!$abonament_id) {
        echo "Abonament not found";
        return;
    }

    // Preluăm abonamentul
    $stmt = $pdo->prepare("SELECT * FROM abonamente WHERE abonament_id = :id");
    $stmt->execute([':id' => $abonament_id]);
    $abonament = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$abonament) {
        echo "Abonament not found";
        return;
    }

    // Preluăm facilitățile pentru abonamentul curent
    $stmt2 = $pdo->prepare("
        SELECT f.facilitate_name 
        FROM privilegiu p
        JOIN facilitati f ON p.facilitate_id = f.facilitate_id
        WHERE p.abonament_id = :id
    ");
    $stmt2->execute([':id' => $abonament['abonament_id']]);
    $abonament['facilitati'] = $stmt2->fetchAll(PDO::FETCH_COLUMN);

    // Verificăm dacă user-ul curent a cumpărat acest abonament
    $current_user_id = $_SESSION["request_user"]["user_id"];
    $stmt3 = $pdo->prepare("
        SELECT purchase_date FROM user_abonamente 
        WHERE user_id = :user_id AND abonament_id = :abonament_id
    ");
    $stmt3->execute([
        ':user_id' => $current_user_id,
        ':abonament_id' => $abonament['abonament_id']
    ]);
    $purchase = $stmt3->fetch(PDO::FETCH_ASSOC);
    $abonament['purchase_date'] = $purchase ? $purchase['purchase_date'] : null;

    require_once "app/views/abonamente/show.php";
}


   public static function buy() {
    global $pdo;

    $abonament_id = $_GET['abonament_id'] ?? null;
    if (!$abonament_id) {
        $_SESSION['error'] = "Abonament invalid";
        header("Location: /proiect_php/sala/Abonamente/index");
        exit;
    }

    $user_id = $_SESSION["request_user"]["user_id"];

    // Verificăm dacă user-ul nu a cumpărat deja
    $stmt_check = $pdo->prepare("
        SELECT 1 FROM user_abonamente 
        WHERE user_id = :user_id AND abonament_id = :abonament_id
    ");
    $stmt_check->execute([
        ':user_id' => $user_id,
        ':abonament_id' => $abonament_id
    ]);
    if ($stmt_check->fetch()) {
        $_SESSION['error'] = "Abonament deja cumpărat!";
        header("Location: /proiect_php/sala/Abonamente/index");
        exit;
    }

    // Inserăm în tabelul user_abonamente
    $stmt = $pdo->prepare("
        INSERT INTO user_abonamente (user_id, abonament_id, purchase_date) 
        VALUES (:user_id, :abonament_id, CURDATE())
    ");
    $stmt->execute([
        ':user_id' => $user_id,
        ':abonament_id' => $abonament_id
    ]);

    $_SESSION['success'] = "Abonament cumpărat cu succes!";
    header("Location: /proiect_php/sala/Abonamente/index");
    exit;
}


public static function edit() {
    global $pdo;

    $abonament_id = $_GET['abonament_id'] ?? null;
    if (!$abonament_id) {
        $_SESSION['error'] = "Abonament not found";
        header("Location: /proiect_php/sala/Abonamente/index");
        exit;
    }

    // Verificăm rolul user-ului curent
    $current_user_id = $_SESSION["request_user"]["user_id"];
   $role = UserRole::getRole($_SESSION["request_user"]["role_id"] ?? 0);
if (!$role || $role['name'] !== 'admin') {
    $_SESSION['error'] = "Nu ai permisiunea să editezi acest abonament!";
    header("Location: /proiect_php/sala/Abonamente/index");
    exit;
}

    // Preluăm abonamentul
    $stmt = $pdo->prepare("SELECT * FROM abonamente WHERE abonament_id = :id");
    $stmt->execute([':id' => $abonament_id]);
    $abonament = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$abonament) {
        $_SESSION['error'] = "Abonament not found";
        header("Location: /proiect_php/sala/Abonamente/index");
        exit;
    }

    // POST: salvare modificări
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $price = $_POST['total_price'] ?? null;

        if (!is_numeric($price) || $price < 0) {
            $_SESSION['error'] = "Preț invalid!";
            header("Location: /proiect_php/sala/Abonamente/edit?abonament_id={$abonament_id}");
            exit;
        }

        $stmt = $pdo->prepare("UPDATE abonamente SET total_price = :price WHERE abonament_id = :id");
        $stmt->execute([
            ':price' => $price,
            ':id' => $abonament_id
        ]);

        $_SESSION['success'] = "Preț actualizat cu succes!";
        header("Location: /proiect_php/sala/Abonamente/index");
        exit;
    }

    // Încarcă view-ul edit
    require_once "app/views/abonamente/edit.php";
}

}
