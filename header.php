<?php
session_start();
include 'connection.php';

// Ініціалізація кошика, якщо він ще не існує
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Підрахунок загальної кількості продуктів у кошику та їх загальної вартості
$total_items = 0;
$total_price = 0;

foreach ($_SESSION['cart'] as $item) {
    $total_items += $item['quantity'];
    $total_price += $item['product_price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <title>Document</title>
    <style>
        .cart-icon {
            position: relative;
            display: inline-block;
        }

        .cart-icon img {
            width: 40px;
            height: 40px;
        }

        .cart-count {
            position: absolute;
            top: -7px;
            right: -7px;
            background: #6F7418;
            color: #fff;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
        }

        .cart-total-price {
            display: block;
            margin-top: 5px;
            font-size: 14px;
            color: #343701;
        }
    
    </style>
</head>
<body>
    <header class="header">
        <div class="flex">
            <a href="index.php" class="logo"><img src="image/logo.png"></a>
            <nav class="navbar">
                <a href="index.php">Головна</a>
                <a href="about.php">Про нас</a>
                <a href="shop.php">Магазин</a>
                <a href="search.php">Пошук</a>
            </nav>
            <div class="icons">
                <i class="bi bi-person" id="user-btn"></i>
                
                <div class="cart-icon">
                    <a href="cart.php">
                        <i class="bi bi-cart"></i>
                        <sup class="cart-count"><?php echo $total_items; ?></sup>
                        <span class="cart-total-price"><?php echo $total_price; ?> грн</span>
                    </a>
                </div>
                <i class="bi bi-list" id="menu-btn"></i>
            </div>
            <div class="user-box">
                <p>Користувач : <span><?php echo $_SESSION['user_name'];?></span></p>
                <p>Email : <span><?php echo $_SESSION['user_email'];?></span></p>
                <form method="post">
                    <button type="submit" name="logout" class="logout-btn">Вийти</button>
                </form>
            </div>
        </div>
    </header>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.min.js"></script>
    <script src="script.js"></script>

    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Інформація про продукт</h2>
            <p id="productInfo"></p>
        </div>
    </div>
</body>
</html>