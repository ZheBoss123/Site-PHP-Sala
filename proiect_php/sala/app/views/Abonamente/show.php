<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>Abonament Details</title>
</head>
<body>
    <h1>Abonament: <?= htmlspecialchars($abonament['abonament_name']) ?></h1>

    <p><strong>Preț:</strong> <?= number_format($abonament['total_price'], 2) ?> RON</p>

    <p><strong>Facilități:</strong>
        <?= !empty($abonament['facilitati']) ? implode(', ', $abonament['facilitati']) : 'Nicio facilitate' ?>
    </p>

    <p><strong>Data achiziției:</strong>
        <?= !empty($abonament['purchase_date']) ? htmlspecialchars($abonament['purchase_date']) : '—' ?>
    </p>

    <p><a href="/proiect_php/sala/Abonamente/index">Back</a></p>
</body>
</html>
