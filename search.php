<?php
include 'connection.php';
session_start();

$product_not_found = false; // Змінна для відстеження відсутності продукту

// Перевірка наявності параметра products_name
if (isset($_GET['products_name'])) {
    $products_name = mysqli_real_escape_string($conn, $_GET['products_name']);
    
    // Виконуємо запит для пошуку продукту за назвою
    $query = "SELECT category_id FROM products WHERE name = '$products_name' LIMIT 1";
    $result = mysqli_query($conn, $query) or die('Запит не виконано: ' . mysqli_error($conn));

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $category_id = $row['category_id'];
        // Перенаправляємо користувача на сторінку категорії
        header("Location: category.php?category_id=$category_id");
        exit;
    } else {
        $product_not_found = true; // Встановлюємо змінну на true, якщо продукт не знайдений
    }
}


?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пошук продукту</title>
    <link rel="stylesheet" href="main2.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            margin-top: 180px; /* Доданий відступ зверху */
            text-align: center; /* Центрування контенту */
        }

        .search-title {
            font-size: 35px;
            color: #FCEACB;
            margin-bottom: 10px;
        }

        .search-form {
            display: flex;
            justify-content: center; /* Центрування форми */
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-form input {
            padding: 10px;
            font-size: 16px;
        }

        .search-form .btn {
            padding: 10px 20px;
            background: #6F7418;
            color: #fff;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            border: 2px solid #343701;
        }

        .search-form .btn:hover {
            background: #A69B03;
        }

        .product-not-found {
            font-size: 20px;
            color: #FF0000; /* Червоний колір для повідомлення */
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="search-title">Пошук продукту</div>
        <form method="get" action="search.php" class="search-form">
            <input type="text" name="products_name" placeholder="Введіть назву продукту">
            <button type="submit" class="btn">Пошук</button>
        </form>
        <?php
        if ($product_not_found) {
            echo "<div class='product-not-found'>Такого продукту немає</div>";
        }
        ?>
    </div>
</body>
</html>
