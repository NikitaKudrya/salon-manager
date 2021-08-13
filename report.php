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
				<h1>Отчет за неделю</h1>
				<table>
					<tr>
					<td><h2 align="middle">
						<?php
              $date_week_start = $_GET['date_week_start'];
              $date_week_end = $_GET['date_week_end'];

							if($date_week_start == null & $date_week_start == null){

                $date = date("Y-m-d ");
                $offset = date( 'N' ) - 1;
                $date_week_start = date("Y-m-d", strtotime($date . ' -'.$offset.' day'));
                $date_week_end = date("Y-m-d", strtotime($date_week_start . ' +6 day'));

                $date_week_start = date("Y-m-d", strtotime($date_week_start . ' -7 day'));
                $date_week_end = date("Y-m-d", strtotime($date_week_end . ' -7 day'));

								echo '<a href="/report.php?date_week_start='.$date_week_start.'&date_week_end='.$date_week_end.'" ><button class="button_switch">  <<  </button></a>';
							}
							else{
                $date_week_start = date("Y-m-d", strtotime($date_week_start . ' -7 day'));
                $date_week_end = date("Y-m-d", strtotime($date_week_end . ' -7 day'));

								echo '<a href="/report.php?date_week_start='.$date_week_start.'&date_week_end='.$date_week_end.'" ><button class="button_switch">  <<  </button></a>';
							}
						 ?></h2></td>
					<td><h2 align="middle" class="date_h2"><time>
											<?php
												$date_week_start = $_GET['date_week_start'];
                        $date_week_end = $_GET['date_week_end'];

												if($date_week_start != null & $date_week_end != null){
                          echo 'Неделя '.$date_week_start.' - '.$date_week_end;
												}
												else{
                          $date = date("Y-m-d ");
                          $offset = date( 'N' ) - 1;
                          $date_week_start = date("Y-m-d", strtotime($date . ' -'.$offset.' day'));
                          $date_week_end = date("Y-m-d", strtotime($date_week_start . ' +6 day'));
                          echo 'Неделя '.$date_week_start.' - '.$date_week_end;
												}
												// print('Next Date ' . date('Y-m-d', strtotime('-1 day', strtotime($date_raw))));
											 ?>
										</time></h2></td>
					<td><h2 align="middle" >
						<?php
              $date_week_start = $_GET['date_week_start'];
              $date_week_end = $_GET['date_week_end'];

              if($date_week_start == null & $date_week_start == null){

                $date = date("Y-m-d ");
                $offset = date( 'N' ) - 1;
                $date_week_start = date("Y-m-d", strtotime($date . ' -'.$offset.' day'));
                $date_week_end = date("Y-m-d", strtotime($date_week_start . ' +6 day'));

                $date_week_start = date("Y-m-d", strtotime($date_week_start . ' +7 day'));
                $date_week_end = date("Y-m-d", strtotime($date_week_end . ' +7 day'));

                echo '<a href="/report.php?date_week_start='.$date_week_start.'&date_week_end='.$date_week_end.'" ><button class="button_switch">  >>  </button></a>';
              }
              else{
                $date_week_start = date("Y-m-d", strtotime($date_week_start . ' +7 day'));
                $date_week_end = date("Y-m-d", strtotime($date_week_end . ' +7 day'));

                echo '<a href="/report.php?date_week_start='.$date_week_start.'&date_week_end='.$date_week_end.'" ><button class="button_switch">  >>  </button></a>';
              }
						 ?>
					</h2></td>
					</tr>

				</table>
				<div class="shadowbox_column_select"><h2></h2>
					<?php
						// ini_set('display_errors', 1);
						$new_date = $_GET['new_date'];
						$date_week_start = $_GET['date_week_start'];
						$date_week_end = $_GET['date_week_end'];
						if($date_week_start == null & $date_week_end == null){
							$date = date("Y-m-d ");
							$offset = date( 'N' ) - 1;
							$date_week_start = date("Y-m-d", strtotime($date . ' -'.$offset.' day'));
							$date_week_end = date("Y-m-d", strtotime($date_week_start . ' +6 day'));
						}

						if($new_date == null){
							$new_date = date("Y-m-d");
						}
						// echo '<p>'.$new_date.'</p>';
            // echo '<p>'.$service_id.'</p>';
	          require 'configDB.php';
	          // $worker_id = $_GET['worker_id'];

            // echo '<table class="schedule_table">';
      	    // echo '<caption>SCHEDULE</caption>
      			// 	<tr>
      			// 	<th>Мастер</th>
      			// 	<th>Понедельник</th>
            //   <th>Вторник</th>
            //   <th>Среда</th>
            //   <th>Четверг</th>
            //   <th>Пятница</th>
      			// 	<th>Суббота</th>
            //   <th>Воскресенье</th>
      			// 	</tr>';
						//
						// $sql = "SELECT w.worker_name, ws.worker_id, ws.assigned_date, SUM(ws.price) AS sum_price FROM worker_schedule AS ws, workers AS w WHERE w.worker_id = ws.worker_id AND ws.assigned_date >= '".$date_week_start."' AND ws.assigned_date <= '".$date_week_end."' GROUP BY ws.worker_id, ws.assigned_date";
						// // echo $sql;
						// $query = $pdo->query($sql);
						// // $query = $pdo->query('SELECT worker_id FROM schedule WHERE assigned_date = "'.$new_date.'" ORDER BY worker_id ASC');
						// $day = 1;
						// $change_flag = 0;
						//
						// $row = $query->fetch(PDO::FETCH_OBJ);
						// echo '<tr><td>'.$row->worker_name.'</td><td>'.$row->sum_price.'</td>';
						// $prev_id = $row->worker_id;
						// $prev_day = $row->assigned_date;
						// // $datediff = date_diff(date_create($date_week_start), date_create($date_week_end));
						// // $datediff = date_diff(date_create($date_week_start), date_create($date_week_end))->format('%a');
						// // echo "DIFF: ".$datediff;
						//
						//
	          // while($row = $query->fetch(PDO::FETCH_OBJ)) {
	          //     // echo '<br><a href="/schedule.php?worker_id='.$row->worker_id.'&date='.$new_date.'&action=delete" name="workeridButton" class="button_worker">'.$row->worker_name.' >></a><br>';
						// 		$datediff = date_diff(date_create($prev_day), date_create($row->assigned_date))->format('%a');
						// 		if($prev_id == $row->worker_id){
						// 			if($datediff != 1){
						// 				for ($i = 1; $i < $datediff; $i++) {
						// 					echo '<td></td>';
						// 					$day = $day + 1;
						// 				}
						// 			}
						// 			echo '<td>'.$row->sum_price.'</td>';
						// 			// if($day == 7){
						// 			// 	echo '</tr>';
						// 			// }
            //     }
						// 		else {
						// 			$tail = 7 - $day;
						// 			for ($i = 1; $i <= $tail; $i++) {
						// 				echo '<td></td>';
						// 			}
						// 			echo '<tr><td>'.$row->worker_name.'</td>';
						// 			$datediff = date_diff(date_create($date_week_start), date_create($row->assigned_date))->format('%a');
						// 			$day = 0;
						// 			for ($i = 1; $i <= $datediff; $i++) {
						// 				echo '<td></td>';
						// 				$day = $day + 1;
						// 			}
						// 			echo '<td>'.$row->sum_price.'</td>';
						// 		}
						// 		$day = $day + 1;
						// 		$prev_id = $row->worker_id;
						// 		$prev_day = $row->assigned_date;
	          // }
						// for ($i = 1; $i <= 7-$day; $i++) {
						// 	echo '<td></td>';
						// }
            // echo '</table>';

						echo '<table class="schedule_table">';
      	    echo '<caption>SCHEDULE</caption>
      				<tr>
      				<th>Мастер</th>
      				<th>Понедельник</th>
              <th>Вторник</th>
              <th>Среда</th>
              <th>Четверг</th>
              <th>Пятница</th>
      				<th>Суббота</th>
              <th>Воскресенье</th>
							<th><b>Сумма по мастеру</b></th>
      				</tr>';


						$days[0] = $date_week_start;
						for ($i=1; $i < 7; $i++) {
							$days[$i] = date("Y-m-d", strtotime($days[$i - 1] . ' +1 day'));
							// echo $days[$i]."<br>";
						}

						$sql = "SELECT w.worker_name, ws.worker_id, ws.assigned_date, SUM(ws.price) AS sum_price FROM worker_schedule AS ws, workers AS w WHERE w.worker_id = ws.worker_id AND ws.assigned_date >= '".$date_week_start."' AND ws.assigned_date <= '".$date_week_end."' GROUP BY ws.worker_id, ws.assigned_date";
						$query = $pdo->query($sql);
						// $report = array(array());
						while($row = $query->fetch(PDO::FETCH_OBJ)) {
							$report[$row->worker_name][$row->assigned_date] = $row->sum_price;
							$reportWorker[$row->worker_name] += $row->sum_price;
							$reportWeek[$row->assigned_date] += $row->sum_price;
						}

						// $selected_day = "2021-08-02";
						//
						// usort($report, "compare");
						//
						// function compare($v1,$v2){
						// 	if($v1[$selected_day] == $v2[$selected_day]) return 0;
						// 	return ($v1[$selected_day] < $v2[$selected_day])? -1: 1;
						// }

						foreach ($report as $key => $value) {
							echo " <tr><td>".$key."</td>";
							for ($i=0; $i < count($days); $i++) {
								if($value[$days[$i]] != null){
									echo "<td>".$value[$days[$i]]."</td>";
								}
								else {
									echo "<td></td>";
								}
							}
							echo "<td><b>".$reportWorker[$key]."</b></td>";
							echo "</tr>";
						}
						echo "<tr><td><b>Сумма по дню</b></td>";
						for ($i=0; $i < count($days); $i++) {
							if($reportWeek[$days[$i]] != null){
								echo "<td><b>".$reportWeek[$days[$i]]."</b></td>";
								$sum +=  $reportWeek[$days[$i]];
							}
							else {
								echo "<td></td>";
							}
						}
						echo "<td><b>$sum</b></td>";
						echo '</tr></table>';

	        ?>
				</div>

			</div>
		</div>
	</body>

</html>
