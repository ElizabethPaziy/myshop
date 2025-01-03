<?php
include 'connection.php';
session_start();

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
        echo "<p>Такого продукту немає</p>";
    }
}

// Отримання category_id з URL параметра
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

// Перевірка, чи переданий category_id
if ($category_id === 0) {
    die('Відсутній ідентифікатор категорії.');
}

// Отримання фільтрів з форми
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : '';
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : '';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : '';

// Формування SQL-запиту з урахуванням фільтрів та сортування
$query = "SELECT * FROM products WHERE category_id = $category_id";

if ($min_price !== '') {
    $query .= " AND price >= $min_price";
}

if ($max_price !== '') {
    $query .= " AND price <= $max_price";
}

if ($sort_order === 'price_asc') {
    $query .= " ORDER BY price ASC";
} elseif ($sort_order === 'price_desc') {
    $query .= " ORDER BY price DESC";
}

$result = mysqli_query($conn, $query) or die('Запит не виконано: ' . mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Категорія</title>
    <link rel="stylesheet" href="main2.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            margin-top: 180px; /* Доданий відступ зверху */
        }

        .filter-title, .sort-title {
            font-size: 35px;
            color: #FCEACB;
            margin-bottom: 10px;
        }

        .filter-form, .sort-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .filter-form input, .sort-form select {
            padding: 10px;
            font-size: 16px;
        }

        .filter-form .btn, .sort-form .btn {
            padding: 10px 20px;
            background: #6F7418;
            color: #fff;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            border: 2px solid #343701;
        }

        .filter-form .btn:hover, .sort-form .btn:hover {
            background: #A69B03;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .product {
            position: relative;
            background-color: #FCEACB;
            border: 2px solid #343701;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            width: calc(25% - 40px);
            box-sizing: border-box;
        }

        .product-name {
            font-size: 20px;
            color: #343701;
            margin-bottom: 10px;
        }

        .product-image {
            width: 100%;
            height: 280px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .product-price {
            font-size: 18px;
            color: #6F7418;
            margin-bottom: 10px;
        }

        .product-detail {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background-color: rgba(252, 234, 203, 0.5);
            color: #343701;
            padding: 10px;
            border-radius: 5px;
            z-index: 1;
        }

        .product-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .product-form .btn {
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
        }

        .product-form .btn:hover {
            background: #A69B03;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="filter-title">Фільтрація за ціною</div>
        <form method="get" action="category.php" class="filter-form">
            <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
            <input type="number" name="min_price" placeholder="Вкажіть мінімальну ціну:" step="0.01" value="<?php echo htmlspecialchars($min_price); ?>">
            <input type="number" name="max_price" placeholder="Вкажіть максимальну ціну:" step="0.01" value="<?php echo htmlspecialchars($max_price); ?>">
            <button type="submit" class="btn">Фільтрувати</button>
        </form>

        <div class="sort-title">Сортувати за ціною:</div>
        <form method="get" action="category.php" class="sort-form">
            <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
            <input type="hidden" name="min_price" value="<?php echo htmlspecialchars($min_price); ?>">
            <input type="hidden" name="max_price" value="<?php echo htmlspecialchars($max_price); ?>">
            <select name="sort_order">
                <option value="price_asc" <?php if ($sort_order === 'price_asc') echo 'selected'; ?>>За зростанням</option>
                <option value="price_desc" <?php if ($sort_order === 'price_desc') echo 'selected'; ?>>За спаданням</option>
            </select>
            <button type="submit" class="btn">Сортувати</button>
        </form>

        <div class="products">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='product'>";
                echo "<h2 class='product-name'>{$row['name']}</h2>";
                echo "<img src='image/{$row['image']}' alt='{$row['name']}' class='product-image'><br>";
                echo "<p class='product-price'>Ціна: {$row['price']} грн</p>";
                echo "<form method='post' action='cart.php' class='product-form'>";
                echo "<input type='hidden' name='product_id' value='{$row['id']}'>";
                echo "<input type='hidden' name='product_name' value='{$row['name']}'>";
                echo "<input type='hidden' name='product_price' value='{$row['price']}'>";
                echo "<input type='hidden' name='product_image' value='{$row['image']}'>";
                echo "<button type='button' class='btn show-details'>Детальніше</button>";
                echo "<div class='product-detail'>{$row['product_detail']}</div>";
                echo "<button type='submit' name='add_to_cart' class='btn'>Додати в кошик</button>";
                echo "</form>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const detailButtons = document.querySelectorAll('.show-details');

            detailButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const productDetail = this.nextElementSibling;
                    productDetail.style.display = productDetail.style.display === 'block' ? 'none' : 'block';
                });
            });
        });
    </script>
</body>
</html>
