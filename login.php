<?php
	include 'connection.php';

	session_start();

	if (isset($_POST['submit-btn'])) {

		$filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
		$email = mysqli_real_escape_string($conn, $filter_email);

		$filter_password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
		$password = mysqli_real_escape_string($conn, $filter_password);

		$select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email'") or die('Запит не виконано');

		if (mysqli_num_rows($select_user) > 0) {
			$row = mysqli_fetch_assoc($select_user);
			if ($password === $row['password']) {
				$_SESSION['user_name'] = $row['name'];
	 			$_SESSION['user_email'] = $row['email'];
	 			$_SESSION['user_id'] = $row['id'];
	 				header('location:index.php');
	 			} else {
	 				$message[] = 'Неправильний email або пароль';
	 			}
			} else {
				$message[] = 'Неправильний email або пароль';
			}
		} else {
			$message[] = 'Неправильний email або пароль';
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Login</title>
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
				<h1>Вхід</h1>
				<div class="input-field">
					<label>Email</label><br>
					<input type="email" name="email" placeholder="Введіть email" required>
				</div>
				<div class="input-field">
					<label>Пароль</label><br>
					<input type="password" name="password" placeholder="Введіть пароль" required>
				</div>
				<input type="submit" name="submit-btn" value="Увійти" class="btn">
				<p>Ще не зареєстровані? <a href="register.php">Реєстрація</a></p>
			</form>
	</section>
	<script src="script.js"></script>
</body>
</html>