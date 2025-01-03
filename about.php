<?php
    include 'connection.php';
    session_start();
    $admin_id = $_SESSION['user_name'];

    if (isset($_POST['logout'])) {
        session_destroy();
        header('location:login.php');
    }
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <style type="text/css">
    <?php include 'main2.css';?>
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Про нас - Магазин</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="main2.css"> <!-- Включаємо основний CSS-файл -->
    <?php /* Інші мета-теги і підключення скриптів */ ?>
</head>
<body>
    <?php
    session_start();
    include 'header.php'; // Включаємо ваш header.php
    ?>
    <style>
    .content-box {
        width: 65%; /* Задаємо ширину текстового блоку */
        padding: 20px;
    }

</style>
    <div class="line2"></div>
    <div class="about-us">
        <div class="row">
            <div class="box">
               <div class="title">
                    <span>.</span>
                </div>
                <p>Це мережа фермерських магазинів та онлайн-платформа з асортиментом понад 5000 натуральних продуктів та товарів. У нас ви знайдете свіжі сезонні овочі та фрукти, молочні продукти від місцевих фермерів, мед та натуральні солодощі, а також органічні м'ясні і рибні вироби. Ми підтримуємо місцевих виробників і збільшуємо доступність якісних продуктів для наших клієнтів. Наша місія - це популяризувати здоровий спосіб життя та забезпечити українських споживачів свіжими та екологічно чистими продуктами.</p>
            </div>
            <div class="img-box">
                <img src="image/ми.png">
            </div>
        </div>
    </div>
    <?php include 'footer.php';?>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
