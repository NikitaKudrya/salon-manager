<?php
  $worker_id = $_GET['worker_id'];
  $date = $_GET['date'];
	$action = $_GET['action'];
  require 'configDB.php';
  if($action == 'delete'){
		$sql = 'DELETE FROM worker_schedule WHERE worker_id = :worker_id AND assigned_date = :assigned_date';
    $query = $pdo->prepare($sql);
    $query->execute(['worker_id' => $worker_id, 'assigned_date' => $date]);
  }
?>

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
				<h1>Выбор мастера</h1>
				<table>
					<tr>
					<td><h2 align="middle">
						<?php
							$new_date = $_GET['new_date'];
							$N = $_GET['N'];
              $client_id = $_GET['client_id'];
              $service_id = $_GET['service_id'];
              $service_name = $_GET['service_name'];
              $price = $_GET['price'];
              $duration = $_GET['duration'];
							if($new_date == null){
								$date = date("Y-m-d");
								$date = date("Y-m-d", strtotime($date . ' -1 day'));
								$N = -1;
								echo '<a href="/worker_select.php?client_id='.$client_id.'&new_date='.$date.'&N='.$N.'&service_id='.$service_id.'&service_name='.$service_name.'&price='.$price.'&duration='.$duration.'" ><button class="button_switch">  <<  </button></a>';
							}
							else{
								$date = date("Y-m-d", strtotime($new_date . ' -1 day'));
								$N = $N - 1;
								echo '<a href="/worker_select.php?client_id='.$client_id.'&new_date='.$date.'&N='.$N.'&service_id='.$service_id.'&service_name='.$service_name.'&price='.$price.'&duration='.$duration.'" ><button class="button_switch">  <<  </button></a>';
							}
						 ?></h2></td>
					<td><h2 align="middle" class="date_h2"><time>
											<?php
												$new_date = $_GET['new_date'];
												$N = $_GET['N'];
												$days = array( 1 => 'Понедельник' , 'Вторник' , 'Среда' , 'Четверг' , 'Пятница' , 'Суббота' , 'Воскресенье' );
												if($new_date == null){
													$date = date("Y-m-d ".$days[date( 'N' )]);
													// $date = date("j-n-Y ".$days[date( 'N' )], strtotime($date . ' -1 day'));
													echo $date;
												}
												else{
													$day = date("N", strtotime(' '.$N.' day'));
													echo $new_date.' '.$days[$day];
												}
												// print('Next Date ' . date('Y-m-d', strtotime('-1 day', strtotime($date_raw))));
											 ?>
										</time></h2></td>
					<td><h2 align="middle" >
						<?php
							$new_date = $_GET['new_date'];
							$N = $_GET['N'];
              $client_id = $_GET['client_id'];
              $service_id = $_GET['service_id'];
              $service_name = $_GET['service_name'];
              $price = $_GET['price'];
              $duration = $_GET['duration'];
							if($new_date == null){
								$date = date("Y-m-d");
								$date = date("Y-m-d", strtotime($date . ' +1 day'));
								$N = 1;
								echo '<a href="/worker_select.php?client_id='.$client_id.'&new_date='.$date.'&N='.$N.'&service_id='.$service_id.'&service_name='.$service_name.'&price='.$price.'&duration='.$duration.'" ><button class="button_switch">  >>  </button></a>';
							}
							else{
								$date = date("Y-m-d", strtotime($new_date . ' +1 day'));
								$N = $N + 1;
								echo '<a href="/worker_select.php?client_id='.$client_id.'&new_date='.$date.'&N='.$N.'&service_id='.$service_id.'&service_name='.$service_name.'&price='.$price.'&duration='.$duration.'" ><button class="button_switch">  >>  </button></a>';
							}
						 ?>
					</h2></td>
					</tr>

				</table>
				<div class="shadowbox_column_select"><h2>Расписание работы мастеров</h2>
					<?php
						$new_date = $_GET['new_date'];
            $N = $_GET['N'];
            $selected_client_id = $_GET['client_id'];
            $selected_service_id = $_GET['service_id'];
            $selected_price = $_GET['price'];
            $service_name = $_GET['service_name'];
            $duration = $_GET['duration'];

						if($new_date == null){
							$new_date = date("Y-m-d");
						}
						// echo '<p>'.$new_date.'</p>';
            // echo '<p>'.$service_id.'</p>';
	          require 'configDB.php';
	          // $worker_id = $_GET['worker_id'];

            echo '<table class="schedule_table">';
      	    echo '<caption>SCHEDULE</caption>
      				<tr>
      				<th>Мастер</th>
      				<th>Время начала</th>
      				<th>Время окончания</th>
              <th>Действие</th>
      				</tr>';

            // $pdo->beginTransaction();
            // $pdo->exec('LOCK TABLES worker_schedule READ');
            // $sql = "SELECT GET_LOCK('test',10) AS lock_res";
            // $getlock = $pdo->query($sql);
            // $rowlock = $getlock->fetch(PDO::FETCH_OBJ);
            // var_dump($rowlock);
            // echo "RESULT: ".$rowlock->lock_res;
            // echo "RESULT: ".$sql;

	          $query = $pdo->query('SELECT s.id, w.worker_id, w.worker_name, s.time_start, s.time_end, s.client_id, s.service_id, s.price FROM workers AS w, worker_schedule AS s, workers_services AS ws WHERE s.client_id IS NULL AND w.worker_id = s.worker_id AND w.worker_id = ws.worker_id AND s.assigned_date = "'.$new_date.'" AND ws.service_id = "'.$service_id.'" ORDER BY w.worker_id, s.time_start ASC');
						// $query = $pdo->query('SELECT worker_id FROM schedule WHERE assigned_date = "'.$new_date.'" ORDER BY worker_id ASC');
	          while($row = $query->fetch(PDO::FETCH_OBJ)) {
	              // echo '<br><a href="/schedule.php?worker_id='.$row->worker_id.'&date='.$new_date.'&action=delete" name="workeridButton" class="button_worker">'.$row->worker_name.' >></a><br>';
                if($row->client_id == null & $row->service_id == null & $row->price == null){
                  $client_id = 'NULL';
                  $service_id = 'NULL';
                  $price = 'NULL';
                }
                echo '<tr><td>'.$row->worker_name.'</td><td>'.$row->time_start.'</td><td>'.$row->time_end.'</td><td><a href="/time_select.php?client_id='.$client_id.'&service_id='.$service_id.'&price='.$price.'&selected_client_id='.$selected_client_id.'&id='.$row->id.'&worker_id='.$row->worker_id.
                '&worker_name='.$row->worker_name.'&new_date='.$new_date.'&N='.$N.'&selected_service_id='.$selected_service_id.'&service_name='.$service_name.'&selected_price='.$selected_price.'&duration='.$duration.'&time_start='.$row->time_start.
                '&time_end='.$row->time_end.'"><button>Выбрать</button></a></td>';
	          }
            echo '</table>';

            // $sql = "SELECT RELEASE_LOCK('test') AS release_res";
            // $getlock = $pdo->query($sql);
            // $rowlock = $getlock->fetch(PDO::FETCH_OBJ);
            // var_dump($rowlock);
            // echo "RESULT: ".$rowlock->release_res;
            // $pdo->commit();
            // $pdo->exec('UNLOCK TABLES');
	        ?>
				</div>

			</div>
		</div>
	</body>

</html>
