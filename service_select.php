<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Управление салоном</title>
		<link rel="stylesheet" href="css/schedule.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	</head>

	<body>
		<div class="work_area">
			<div class="shadowbox">
				<h1>Выбор услуги</h1>
				<div class="shadowbox_column"><h2>Список услуг</h2>
					<?php
						$client_id = $_GET['client_id'];
	          require 'configDB.php';
	          // $worker_id = $_GET['worker_id'];

            echo '<table class="schedule_table">';
      	    echo '
      				<tr>
      				<th>Услуга</th>
      				<th>Цена</th>
      				<th>Длительность</th>
              <th>Действие</th>
      				</tr>';

	          $query = $pdo->query('SELECT * FROM services ORDER BY service_id ASC');
						// $query = $pdo->query('SELECT worker_id FROM schedule WHERE assigned_date = "'.$new_date.'" ORDER BY worker_id ASC');
	          while($row = $query->fetch(PDO::FETCH_OBJ)) {
	              // echo '<br><a href="/schedule.php?worker_id='.$row->worker_id.'&date='.$new_date.'&action=delete" name="workeridButton" class="button_worker">'.$row->worker_name.' >></a><br>';
                echo '<tr><td>'.$row->service_name.'</td><td>'.$row->price.'</td><td>'.$row->duration.'</td><td><a href="/worker_select.php?client_id='.$client_id.'&service_id='.$row->service_id.'&service_name='.$row->service_name.'&price='.$row->price.'&duration='.$row->duration.'"><button>Выбор</button></a></td>';
	          }
            echo '</table>';
	        ?>
				</div>
				<div class="shadowbox_column"><h2>История</h2>
					<?php
						$client_id = $_GET['client_id'];
						require 'configDB.php';
						// $worker_id = $_GET['worker_id'];

						echo '<table class="schedule_table">';
						echo '
							<tr>
							<th>Услуга</th>
							<th>Цена</th>
							<th>Дата</th>
							<th>Действие</th>
							</tr>';

						$query = $pdo->query('SELECT * FROM worker_schedule AS ws, services AS s WHERE ws.service_id = s.service_id AND client_id = "'.$client_id.'" ORDER BY assigned_date DESC');
						// $query = $pdo->query('SELECT worker_id FROM schedule WHERE assigned_date = "'.$new_date.'" ORDER BY worker_id ASC');
						while($row = $query->fetch(PDO::FETCH_OBJ)) {
								// echo '<br><a href="/schedule.php?worker_id='.$row->worker_id.'&date='.$new_date.'&action=delete" name="workeridButton" class="button_worker">'.$row->worker_name.' >></a><br>';
								echo '<tr><td>'.$row->service_name.'</td><td>'.$row->price.'</td><td>'.$row->assigned_date.'</td>';
								if($row->status_id != 3) {
									echo '<td><a href="/worker_select.php?client_id='.$client_id.'&service_id='.$row->service_id.'&service_name='.$row->service_name.'&price='.$row->price.'&duration='.$row->duration.'"><button>Отменить запись</button></a></td>';
								}
								else {
									echo '<td></td>';
								}
						}
						echo '</table>';
	        ?>
				</div>
			</div>
		</div>
	</body>

</html>
