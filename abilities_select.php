<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Manager</title>
		<link rel="stylesheet" href="css/style0.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	</head>

	<body>
		<form action="/abilities_edit.php" method="post" name="current_abilities">
			<?php
			  $worker_id = $_POST['workeridButton'];
				require 'configDB.php';

				$query = $pdo->query('SELECT `s.service_name` FROM `workers_services` AS `ws`,`workers` AS `w`,`services` AS `s` WHERE `ws.worker_id` = '.worker_id.' AND `ws.service_id` = `s.service_id` ORDER BY `worker_id` DESC');
				echo $query;
				while($row = $query->fetch(PDO::FETCH_OBJ)) {
					echo '<button type="submit" value="'.$row->service_name.'" name="serviceButton" class="button_worker">'.$row->service_name.'</button>';
				}
				header('Location: /abilities_edit.php');
			?>
		</form>
	</body>

</html>
