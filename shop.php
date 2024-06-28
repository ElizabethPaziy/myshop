<?php
// Підключення до бази даних та ініціалізація сесії
include 'connection.php';

// Запит до бази даних для отримання всіх категорій
$query = "SELECT id, name, image FROM categories";
$result = mysqli_query($conn, $query);

// Перевірка наявності результатів
if (mysqli_num_rows($result) > 0) {
    echo '<div class="dropdown">';
    // Виведення посилань на категорії
    while ($row = mysqli_fetch_assoc($result)) {
        $category_id = $row['id'];
        $category_name = $row['name'];
        $category_image = $row['image'];
        
        
        echo "<a href='category.php?category_id={$category_id}'>";
        echo "<img src='image/{$category_image}' alt='{$category_name}' style='width: 280px; height: 280px;'>";
        echo "<span style='font-size: 28px;'>{$category_name}</span>"; // приклад додавання розмірів
        echo "</a>";
    }
    echo '</div>';
} else {
    echo 'Немає доступних категорій.';
}

// Не закривайте з'єднання тут, якщо ще плануєте використовувати $conn в інших частинах сторінки

?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин</title>
    <link rel="stylesheet" href="main2.css">
    <!-- Інші мета-теги і підключення скриптів -->
</head>
<body>
    <?php
    session_start();
    include 'header.php'; // Включаємо ваш header.php
    ?>
    <!-- Вміст сторінки shop.php -->
    <div class="container">
        <!-- Ваш вміст -->
    </div>
    <!-- Інші скрипти і HTML -->
</body>
</html>