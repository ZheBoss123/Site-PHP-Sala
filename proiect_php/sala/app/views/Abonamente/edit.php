<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Abonament</title>
</head>
<body>
    <h1>Edit Abonament</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color:red"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="abonament_id" value="<?= $abonament['abonament_id'] ?>">

        <p>
            <label for="abonament_name">Nume abonament:</label>
            <input type="text" id="abonament_name" value="<?= htmlspecialchars($abonament['abonament_name']) ?>" disabled>
        </p>

        <p>
            <label for="total_price">Preț:</label>
            <input type="text" name="total_price" id="total_price" value="<?= htmlspecialchars($abonament['total_price']) ?>">
        </p>

        <input type="submit" value="Salvează">
    </form>

    <a href="/proiect_php/sala/Abonamente/index">Back</a>
</body>
</html>
