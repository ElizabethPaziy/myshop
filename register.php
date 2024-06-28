<?php
	include 'connection.php';

	if (isset($_POST['submit-btn'])) {
		$filter_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
		$name = mysqli_real_escape_string($conn, $filter_name);

		$filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
		$email = mysqli_real_escape_string($conn, $filter_email);

		$filter_password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
		$password = mysqli_real_escape_string($conn, $filter_password);

		$filter_cpassword = filter_var($_POST['cpassword'], FILTER_SANITIZE_STRING);
		$cpassword = mysqli_real_escape_string($conn, $filter_cpassword);

		$select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email'") or die('Запит не виконано');

		if (mysqli_num_rows($select_user)>0) {
			$message[] = 'Такий користувач уже зареєстрований';
		}else{
			if ($password != $cpassword) {
				$message[] = 'Паролі не співпадають';
			}else{
				mysqli_query($conn, "INSERT INTO `users`(`name`, `email`, `password`) VALUES ('$name', '$email', '$password')") or die('Запит не виконано');
				$message[] = 'Реєстрація пройшла успішно';
				header('refresh:5;url=login.php');
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--box icon link-->
	<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Register</title>
</head>

<body>
	<section class="form-container">
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
		<form method="post">
			<h1>Реєстрація</h1>
			<input type="text" name="name" placeholder="Введіть ім'я" required>
			<input type="email" name="email" placeholder="Введіть email" required>
			<input type="password" name="password" placeholder="Введіть пароль" required>
			<input type="password" name="cpassword" placeholder="Підтвердіть пароль" required>
			<input type="submit" name="submit-btn" value="Зареєструватися" class="btn">
			<p>Вже маєте обліковий запис? <a href="login.php">Вхід</a></p>
		</form>
	</section>
	<script src="script.js"></script>
</body>
</html>