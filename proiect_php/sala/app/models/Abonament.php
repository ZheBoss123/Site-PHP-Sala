<?php

class Abonament {
    public static function getAllAbonamente() {
        global $pdo;

        $sql = "SELECT * 
                FROM Abonamente";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAbonament() {
        global $pdo;
        $Abonament_id = $_GET['id'];

        $sql = "SELECT * 
                FROM Abonamente 
                WHERE id = :Abonament_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":Abonament_id" => $Abonament_id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updateAbonament($Abonament_id, $amount, $reason, $status) {
        global $pdo;

        $sql = "UPDATE Abonamente
                SET amount = :amount, reason = :reason, status = :Abonament_status
                WHERE id = :Abonament_id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute(array(
            ":Abonament_id" => $Abonament_id,
            ":amount" => $amount,
            ":reason" => $reason,
            ":Abonament_status" => $status
        ));
    }
}
?>