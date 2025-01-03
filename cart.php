<?php
session_start();
include 'connection.php';

// Ініціалізація кошика, якщо він ще не існує
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Додавання продукту до кошика
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];

    // Перевірка, чи продукт вже в кошику
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'product_name' => $product_name,
            'product_price' => $product_price,
            'product_image' => $product_image,
            'quantity' => 1
        ];
    }
}

// Видалення продукту з кошика
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $product_id = $_GET['product_id'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    // Перепакування масиву, щоб уникнути пропущених індексів
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кошик</title>
    <link rel="stylesheet" href="main2.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            margin-top: 180px;
        }

        .cart-items {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #FCEACB;
            border: 2px solid #343701;
            border-radius: 10px;
            padding: 20px;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }

        .cart-item-details {
            flex: 1;
            margin-left: 20px;
        }

        .cart-item-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: flex-start; /* Вирівнювання вгору */
        }

        .btn {
            padding: .8rem 0;
            background: #6F7418;
            color: #FCEACB;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.5rem;
            font-family: 'Times New Roman', Times, serif;
            border: 2px solid #343701;
            width: 50%; /* Змінено ширину на 50% для зменшення удвічі */
            box-sizing: border-box;
            text-align: center;
        }

        .btn:hover {
            background: #A69B03;
        }

        .btn-remove {
            padding: .4rem .8rem; /* Підлаштування відступів */
            background: #6F7418; /* Червоний колір кнопки */
            color: #FCEACB;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.5rem; /* Розмір шрифту */
            font-family: 'Times New Roman', Times, serif;
            border: 2px solid #343701;
        }

        .btn-remove:hover {
            background: #A69B03; /* Змінений колір при наведенні */
        }

        .total {
            font-size: 1.5rem;
            text-align: right;
            color: #343701;
            margin-top: 20px;
        }
        
        .empty-cart {
            text-align: center;
            font-size: 1.5rem;
            color: #343701;
        }
        
        .continue-shopping {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="banner">
        <div class="detail">
            <h1>Кошик</h1>
            <p>Звітна сторінка кошика є місцем, де ви можете зручно управляти товарами перед оформленням замовлення. Тут ви знайдете всі додані до кошика товари, зможете переглянути їх деталі, змінити кількість одиниць або видалити непотрібні позиції. Переконайтеся, що ваше замовлення відповідає вашим очікуванням перед тим, як перейти до фінального кроку — оформлення замовлення</p>
        </div>
    </div>

    <div class="container">
        <div class="cart-items">
            <?php
            $total = 0;
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    $total += $item['product_price'] * $item['quantity'];
                    echo "<div class='cart-item'>";
                    echo "<img src='image/{$item['product_image']}' alt='{$item['product_name']}'>";
                    echo "<div class='cart-item-details'>";
                    echo "<h2>{$item['product_name']}</h2>";
                    echo "<p>Ціна: {$item['product_price']} грн</p>";
                    echo "<p>Кількість: {$item['quantity']}</p>";
                    echo "</div>";
                    echo "<div class='cart-item-actions'>";
                    echo "<a href='cart.php?action=remove&product_id={$item['product_id']}' class='btn-remove'>Видалити</a>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "<div class='total'>Загальна вартість: {$total} грн</div>";
            } else {
                echo "<p class='empty-cart'>Ви ще нічого не додали до кошика!</p>";
            }
            ?>
        </div>
        <div class="continue-shopping">
            <a href="shop.php" class="btn">Продовжити покупку</a>
            <a href="checkout.php" class="btn">До оформлення</a>
        </div>
    </div>
</body>
</html>
