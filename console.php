<?php
try {
	$dbh = new PDO('mysql:host=10.66.28.221; port=3306; dbname=x6316047DB', 'x6316047', 'tentacle');
} catch (PDOException $e) {
	var_dump($e->getMessage());
	exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
</head>
<body>
	<h1>Database</h1>
	<table border="1">
		<tr>
			<td>ID</td>
			<td>Name</td>
			<td>E-mail</td>
			<td>Password</td>
		</tr>
		<?php
			function print_table($row) {
				print('<tr>');
				foreach($row as $cell) {
					print('<td>' . $cell . '</td>');
				}
				print('</tr>');
			}	

			$sql = "select * from users";
			$stmt = $dbh->query($sql);
			foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $user) {
				print_table($user);
			}
		?>
	</table>
	<hr>

	<h1>Insert</h1>
		<form method="POST" action="console.php">
		Name: <input type="text" name=name>
		<br>
		E-mail: <input type="text" name=email>
		<br>
		Password: <input type="text" name=password>
		<br>
		<input type="submit" name="add" value="Add">
		<?php
			if(isset($_POST['add'])) {
				$name = $_REQUEST['name'];
				$email = $_REQUEST['email'];
				$password = $_REQUEST['password'];
				
				if($name && $email && $password) { 
					$stmt = $dbh->prepare("insert into users (name, email, password) values (:name, :email, :password)");
					$stmt->execute(array(":name"=>$name, ":email"=>$email, ":password"=>$password));
					header("Location: " . $_SERVER['PHP_SELF']);
				}
			}
		?>
		</form>
	<hr>

	<h1>Delete</h1>
	<form method="POST" action="console.php">
		ID: <select name="deleteID">
		<?php	
			function print_id($arr) {
				foreach($arr as $id) {
					print('<option name="id">' . $id . '</option>');
				}
			}

			$sql = "select id from users";
			$stmt = $dbh->query($sql);
			foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $user) {
				print_id($user);
			}
		?>
		</select>
		<input type="submit" name="delete" value="Delete">
		<?php
			if(isset($_POST['delete'])) {
				$id = $_REQUEST[deleteID];
				
				$stmt = $dbh->prepare("delete from users where id = :id");
				$stmt->execute(array(":id"=>$id));
				header("Location: " . $_SERVER['PHP_SELF']);
			}
		?>
	</form>
</body>
</html>

