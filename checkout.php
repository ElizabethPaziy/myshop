<?php
session_start();
include 'connection.php';

// Перевірка, чи користувач увійшов в систему
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Отримання даних з кошика
$total = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['product_price'] * $item['quantity'];
    }
}

// Отримання даних користувача з таблиці users
$user_id = $_SESSION['user_id'];
$query_user = "SELECT * FROM users WHERE id = '$user_id'";
$result_user = mysqli_query($conn, $query_user);
$user_data = mysqli_fetch_assoc($result_user);
$name = $user_data['name'];
$email = $user_data['email'];
$number = $user_data['number'];

// Обробка відправлення форми
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Отримання даних з форми
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $delivery = mysqli_real_escape_string($conn, $_POST['delivery']);
    $payment = mysqli_real_escape_string($conn, $_POST['payment']);

    // Збереження замовлення в базі даних
    $query = "INSERT INTO `order` (`user_id`, `name`, `email`, `address`, `delivery`, `number`, `payment`) 
              VALUES ('$user_id', '$name', '$email', '$address', '$delivery', '$number', '$payment')";
    mysqli_query($conn, $query) or die('Запит не виконано');
    $order_id = mysqli_insert_id($conn); // Отримуємо ID останнього вставленого запису

    // Очистка кошика після оформлення замовлення
    unset($_SESSION['cart']);

    // Перенаправлення на сторінку order.php після успішного відправлення
    echo '<script>window.location = "index.php?id=' . $index_id . '";</script>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформлення замовлення</title>
    <link rel="stylesheet" href="main.css">
    <style>
        body {
            background-color: #FEBA19; /* Колір фону */
            font-family: Arial, sans-serif; /* Шрифт */
            margin: 0; /* Видалення зовнішніх відступів */
            padding: 0; /* Видалення внутрішніх відступів */
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            margin-top: 180px;
            background-color: #fff; /* Колір фону контейнера */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Тінь контейнера */
        }
        .form-section {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        select {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 2px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: transparent;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23437B52" width="18px" height="18px"><path d="M7 10l5 5 5-5H7z"/></svg>');
            background-repeat: no-repeat;
            background-position-x: 100%;
            background-position-y: 5px;
            background-size: 12px;
            padding-right: 30px;
        }
        button[type="submit"] {
            padding: .8rem 0;
            background: #6F7418;
            color: #fff;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1rem;
            font-family: 'Times New Roman', Times, serif;
            border: 2px solid #343701;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }
        button[type="submit"]:hover {
            background: #A69B03;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h1>Оформлення замовлення</h1>
        
        <!-- Форма з контактними даними та адресою доставки -->
        <form method="post">
            <div class="form-section">
                <h2>Контактні дані та адреса доставки</h2>
                <label for="name">ПІБ:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                <label for="phone">Номер телефону:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($number); ?>" required>
                <label for="email">Електронна пошта:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <label for="address">Адреса доставки:</label>
                <input type="text" id="address" name="address" required>
            </div>

            <!-- Форма вибору способу доставки -->
            <div class="form-section">
                <h2>Спосіб доставки</h2>
                <label for="delivery">Оберіть спосіб доставки:</label>
                <select id="delivery" name="delivery" required>
                    <option value="courier">Кур'єрська доставка</option>
                    <option value="pickup">Самовивіз</option>
                    <option value="post">Поштова доставка</option>
                </select>
            </div>

            <!-- Форма вибору способу оплати -->
            <div class="form-section">
                <h2>Спосіб оплати</h2>
                <label for="payment">Оберіть спосіб оплати</label>
                <select id="payment" name="payment" required>
                    <option value="online">Онлайн-оплата</option>
                    <option value="cash">Оплата при отриманні</option>
                </select>
            </div>

            <!-- Інформація з кошика -->
            <div class="form-section">
                <h2>Інформація про замовлення</h2>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <div class="cart-items">
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <div class="cart-item">
                                <img src="image/<?php echo $item['product_image']; ?>" 
                                alt="<?php echo $item['product_name']; ?>">
                                    
                                <div class="cart-item-details">
                                    <h3><?php echo $item['product_name']; ?></h3>
                                    <p><?php echo $item['product_price']; ?> грн x <?php echo $item['quantity']; ?> шт.</p>
                                </div>
                                <div class="cart-item-actions">
                                    <p><?php echo $item['product_price'] * $item['quantity']; ?> грн</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="total">
                        <p><strong>Загальна вартість:</strong> <?php echo $total; ?> грн</p>
                    </div>
                <?php else: ?>
                    <p class="empty-cart">Ви ще нічого не додали до кошика!</p>
                <?php endif; ?>
            </div>

            <!-- Кнопка відправки форми -->
            <div class="form-actions">
                <button type="submit" name="confirm_order">Підтвердити замовлення</button>
            </div>
        </form>
    </div>
</body>
</html>
