index.php:
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
<?php
	include 'connection.php';
	session_start();
	$admin_id = $_SESSION['user_name'];
	$user_id = $_SESSION['user_id'];

	if (!isset($admin_id)) {
		header('location:login.php');
	}
	if (isset($_POST['logout'])) {
		session_destroy();
		header('location:login.php');
	}
	
	//adding product in cart
	if(isset($_POST['add_to_cart'])) {
		$product_id = $_POST['product_id'];
		$product_name = $_POST['product_name'];
		$product_price = $_POST['product_price'];
		$product_image = $_POST['product_image'];
		$product_quantity = $_POST['product_quantity'];

		$cart_num = mysqli_query($conn, "SELECT * FROM cart WHERE name='$product_name' AND user_id = '$user_id'") or die('Запит не виконано');
		if (mysqli_num_rows($cart_num)>0) {
			$message[]='Ви вже додали цей продукт до "Кошика"!';
		}else{
			mysqli_query($conn, "INSERT INTO cart(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')");
				$message[]='Цей продукт успішно додано до "Кошика"';
		}
	}
?>
<style type="text/css">
	<?php include 'main.css';?>
</style>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
	<link rel="stylesheet" href="main.css" />
	<title>Home page</title>
</head>
<body>
	<?php include 'header.php';?>
	<div class="banner">
		<div class="detail">
			<h1>Ласкаво просимо до "MyGroceryStore!"</h1>
			<p>Ми раді вітати вас у нашому магазині, де кожен знайде все необхідне для здорового та смачного харчування. У нас ви знайдете широкий асортимент свіжих продуктів, від фермерських овочів та фруктів до ексклюзивних делікатесів.</p>
		</div>
	</div>
	<div class="line4"></div>
	<?php
		if (isset($message)) {
			foreach ($message as $msg) {
				echo '
					<div class="message">
						<span>'.$msg.'</span>
						<i class="bx bx-x-circle close-msg" onclick="this.parentElement.remove()"></i>
					</div>
				';
			}
		}
	?>

	 <div 
class="line4"></div>

    <div class="line2"></div> 
    <?php include 'footer.php'; ?>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.min.js"></script>
    <script src="script.js"></script>


</body>
</html>