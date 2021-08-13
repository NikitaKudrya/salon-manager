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
				<h1>Составление расписания</h1>
				<table>
					<tr>
					<td><h2 align="middle">
						<?php
							$new_date = $_GET['new_date'];
							$N = $_GET['N'];
							if($new_date == null){
								$date = date("Y-m-d");
								$date = date("Y-m-d", strtotime($date . ' -1 day'));
								$N = -1;
								echo '<a href="/schedule.php?new_date='.$date.'&N='.$N.'" ><button class="button_switch">  <<  </button></a>';
							}
							else{
								$date = date("Y-m-d", strtotime($new_date . ' -1 day'));
								$N = $N - 1;
								echo '<a href="/schedule.php?new_date='.$date.'&N='.$N.'" ><button class="button_switch">  <<  </button></a>';
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
							if($new_date == null){
								$date = date("Y-m-d");
								$date = date("Y-m-d", strtotime($date . ' +1 day'));
								$N = 1;
								echo '<a href="/schedule.php?new_date='.$date.'&N='.$N.'" ><button class="button_switch">  >>  </button></a>';
							}
							else{
								$date = date("Y-m-d", strtotime($new_date . ' +1 day'));
								$N = $N + 1;
								echo '<a href="/schedule.php?new_date='.$date.'&N='.$N.'" ><button class="button_switch">  >>  </button></a>';
							}
						 ?>
					</h2></td>
					</tr>

				</table>
        <table class="table_filter">
          <tr>
            <?php
              require 'configDB.php';
              $filters = array( 0 => 'Все' , 'Не подтвержденные' , 'Подтвержденные' , 'Исполненные' , 'Свободные');
              $filter = $_GET['filter'];
              // echo $filter;
              if($filter == null){
                $filter = 0;
              }
              $new_date = $_GET['new_date'];
              $N = $_GET['N'];
              if($new_date == null){
                $new_date = date("Y-m-d");
              }

              switch ($filter) {
                  case 0:
                    $query2 = $pdo->query('SELECT ws.price FROM workers AS w, worker_schedule AS ws, clients AS c, services AS s
                      WHERE w.worker_id = ws.worker_id AND ws.client_id = c.client_id AND ws.service_id = s.service_id AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
                    break;
                  case 1:
                    $query2 = $pdo->query('SELECT ws.price FROM workers AS w, worker_schedule AS ws, clients AS c, services AS s
                      WHERE w.worker_id = ws.worker_id AND ws.client_id = c.client_id AND ws.status_id = 1 AND ws.service_id = s.service_id AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
                    break;
                  case 2:
                    $query2 = $pdo->query('SELECT ws.price FROM workers AS w, worker_schedule AS ws, clients AS c, services AS s
                      WHERE w.worker_id = ws.worker_id AND ws.client_id = c.client_id AND ws.status_id = 2 AND ws.service_id = s.service_id AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
                    break;
                  case 3:
                    $query2 = $pdo->query('SELECT ws.price FROM workers AS w, worker_schedule AS ws, clients AS c, services AS s
                      WHERE w.worker_id = ws.worker_id AND ws.client_id = c.client_id AND ws.status_id = 3 AND ws.service_id = s.service_id AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
                    break;
                  case 4:
                    $query2 = $pdo->query('SELECT ws.price FROM workers AS w, worker_schedule AS ws, clients AS c, services AS s
                      WHERE w.worker_id = ws.worker_id AND ws.client_id = c.client_id AND ws.status_id = 0 AND ws.service_id = s.service_id AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
                    break;
              }

              while($row2 = $query2->fetch(PDO::FETCH_OBJ)) {
                $sum_price += $row2->price;
              }

              echo '<td style="white-space: nowrap;">Режим отображения:</td>';

              for ($i = 0; $i <= 4; $i++) {
                if($i == $filter){
                  // echo '<br><a href="/abilities_edit.php?worker_id='.$row->worker_id.'" name="workeridButton" class="button_worker_selected">'.$row->worker_name.'</a><br>';
                  echo '<td><a href="/schedule.php?new_date='.$new_date.'&N='.$N.'&filter='.$i.'" name="workeridButton" class="button_filter_selected">'.$filters[$i].'</a></td>';
                }
                else {
                  echo '<td><a href="/schedule.php?new_date='.$new_date.'&N='.$N.'&filter='.$i.'" name="workeridButton" class="button_filter">'.$filters[$i].'</a></td>';
                }
              }

              if($sum_price > 0){
                echo '<td style="white-space: nowrap;"><b>Суммарная стоимость услуг: '.$sum_price.'</b></td>';
              }
            ?>
            <!-- <td><a href="/abilities_edit.php?worker_id=" name="workeridButton" class="button_filter">Все</a></td>
            <td><a href="/abilities_edit.php?worker_id=" name="workeridButton" class="button_filter">Не подтвержденные</a></td>
            <td><a href="/abilities_edit.php?worker_id=" name="workeridButton" class="button_filter">Подтвержденные</a></td>
            <td><a href="/abilities_edit.php?worker_id=" name="workeridButton" class="button_filter">Исполненные</a></td>
            <td><a href="/abilities_edit.php?worker_id=" name="workeridButton" class="button_filter">Свободные</a></td> -->
          </tr>
        </table>
				<div class="shadowbox_column_right" id="schedule_div" onscroll="scollPos();"><h2>Назначенные сотрудники</h2>
          <!-- <p id="demo"></p> -->
          <script>
          function setCookie(name, value, options = {}) {

              options = {
                path: '/',
                // при необходимости добавьте другие значения по умолчанию
                ...options
              };

              if (options.expires instanceof Date) {
                options.expires = options.expires.toUTCString();
              }

              let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

              for (let optionKey in options) {
                updatedCookie += "; " + optionKey;
                let optionValue = options[optionKey];
                if (optionValue !== true) {
                  updatedCookie += "=" + optionValue;
                }
              }

              document.cookie = updatedCookie;
            }

            function scollPos() {
              var schedule_div = document.getElementById("schedule_div").scrollTop;
              // document.getElementById("demo").innerHTML = schedule_div;
              setCookie('scroll_pos', schedule_div, {secure: true, 'max-age': 3600});
            }
          </script>
					<?php
						$new_date = $_GET['new_date'];
            $N = $_GET['N'];
            if($N == null){
              $N = 0;
            }
						if($new_date == null){
							$new_date = date("Y-m-d");
						}
            $filter = $_GET['filter'];
            if($filter == null){
              $filter = 0;
            }
						// echo '<p>'.$new_date.'</p>';
	          require 'configDB.php';
	          // $worker_id = $_GET['worker_id'];

            echo '<table class="schedule_table">';
      	    echo '<caption>worker_schedule</caption>
      				<tr>
      				<th>Мастер</th>
      				<th>Время начала</th>
      				<th>Время окончания</th>
              <th>Клиент</th>
              <th>Телефон</th>
              <th>Услуга</th>
              <th>Цена</th>
              <th>Действие</th>
      				</tr>';

            switch ($filter) {
                case 0:
                  $query1 = $pdo->query('SELECT ws.id, ws.worker_id, w.worker_name, ws.time_start, ws.time_end, ws.client_id, ws.service_id, s.status_id, s.status_name, ws.price FROM workers AS w, worker_schedule AS ws, status AS s
                    WHERE w.worker_id = ws.worker_id AND ws.status_id = s.status_id AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
                  $query2 = $pdo->query('SELECT ws.id, w.worker_id, c.client_name, c.phone_number, s.service_name, s.price FROM workers AS w, worker_schedule AS ws, clients AS c, services AS s
                    WHERE w.worker_id = ws.worker_id AND ws.client_id = c.client_id AND ws.service_id = s.service_id AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
                  break;
                case 1:
                  $query1 = $pdo->query('SELECT ws.id, ws.worker_id, w.worker_name, ws.time_start, ws.time_end, ws.client_id, ws.service_id, s.status_id, s.status_name, ws.price FROM workers AS w, worker_schedule AS ws, status AS s
                    WHERE w.worker_id = ws.worker_id AND ws.status_id = s.status_id AND ws.status_id = 1 AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
                  $query2 = $pdo->query('SELECT ws.id, w.worker_id, c.client_name, c.phone_number, s.service_name, s.price FROM workers AS w, worker_schedule AS ws, clients AS c, services AS s
                    WHERE w.worker_id = ws.worker_id AND ws.client_id = c.client_id AND ws.status_id = 1 AND ws.service_id = s.service_id AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
                  break;
                case 2:
                  $query1 = $pdo->query('SELECT ws.id, ws.worker_id, w.worker_name, ws.time_start, ws.time_end, ws.client_id, ws.service_id, s.status_id, s.status_name, ws.price FROM workers AS w, worker_schedule AS ws, status AS s
                    WHERE w.worker_id = ws.worker_id AND ws.status_id = s.status_id AND ws.status_id = 2 AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
                  $query2 = $pdo->query('SELECT ws.id, w.worker_id, c.client_name, c.phone_number, s.service_name, s.price FROM workers AS w, worker_schedule AS ws, clients AS c, services AS s
                    WHERE w.worker_id = ws.worker_id AND ws.client_id = c.client_id AND ws.status_id = 2 AND ws.service_id = s.service_id AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
                  break;
                case 3:
                  $query1 = $pdo->query('SELECT ws.id, ws.worker_id, w.worker_name, ws.time_start, ws.time_end, ws.client_id, ws.service_id, s.status_id, s.status_name, ws.price FROM workers AS w, worker_schedule AS ws, status AS s
                    WHERE w.worker_id = ws.worker_id AND ws.status_id = s.status_id AND ws.status_id = 3 AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
                  $query2 = $pdo->query('SELECT ws.id, w.worker_id, c.client_name, c.phone_number, s.service_name, s.price FROM workers AS w, worker_schedule AS ws, clients AS c, services AS s
                    WHERE w.worker_id = ws.worker_id AND ws.client_id = c.client_id AND ws.status_id = 3 AND ws.service_id = s.service_id AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
                  break;
                case 4:
                  $query1 = $pdo->query('SELECT ws.id, ws.worker_id, w.worker_name, ws.time_start, ws.time_end, ws.client_id, ws.service_id, s.status_id, s.status_name, ws.price FROM workers AS w, worker_schedule AS ws, status AS s
                    WHERE w.worker_id = ws.worker_id AND ws.status_id = s.status_id AND ws.status_id = 0 AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
                  $query2 = $pdo->query('SELECT ws.id, w.worker_id, c.client_name, c.phone_number, s.service_name, s.price FROM workers AS w, worker_schedule AS ws, clients AS c, services AS s
                    WHERE w.worker_id = ws.worker_id AND ws.client_id = c.client_id AND ws.status_id = 0 AND ws.service_id = s.service_id AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
                  break;
            }
						// $query = $pdo->query('SELECT worker_id FROM schedule WHERE assigned_date = "'.$new_date.'" ORDER BY worker_id ASC');
	          while($row = $query1->fetch(PDO::FETCH_OBJ)) {
	              // echo '<br><a href="/schedule.php?worker_id='.$row->worker_id.'&date='.$new_date.'&action=delete" name="workeridButton" class="button_worker">'.$row->worker_name.' >></a><br>';
                $worker_id = $row->worker_id;
                if($row->client_id == null & $row->service_id == null & $row->price == null){
                  $client_id = 0;
                  $service_id = 0;
                  $price = 0;
                }
                else{
                  $client_id = $row->client_id;
                  $service_id = $row->service_id;
                  $price = $row->price;
                }
                echo '<tr><td>'.$row->worker_name.'</td><td>'.$row->time_start.'</td><td>'.$row->time_end.'</td>';
                if($row->client_id != null & $row->service_id != null) {
                    $row2 = $query2->fetch(PDO::FETCH_OBJ);
                    // echo "WORKER: ".$row->worker_id;

                    echo '<td>'.$row2->client_name.'</td><td>'.$row2->phone_number.'</td><td>'.$row2->service_name.'</td><td>'.$row->price.'</td>
                    <td><table class="status_table"><tr>';
                    if($row->status_id == 1){
                      echo '<td><a href="/add.php?id='.$row->id.'&table=status_not_approved&status_id='.$row->status_id.'&new_date='.$new_date.'&N='.$N.'" class="button_filter_selected">Не подтверждено</a></td>';
                    }
                    else {
                      echo '<td><a href="/add.php?id='.$row->id.'&table=status_not_approved&status_id='.$row->status_id.'&new_date='.$new_date.'&N='.$N.'" class="button_filter">Не подтверждено</a></td>';
                    }
                    if($row->status_id == 2){
                      echo '<td><a href="/add.php?id='.$row->id.'&table=status_approved&new_date='.$new_date.'&N='.$N.'" class="button_filter_selected">Подтверждено</a></td>';
                    }
                    else {
                      echo '<td><a href="/add.php?id='.$row->id.'&table=status_approved&new_date='.$new_date.'&N='.$N.'" class="button_filter">Подтверждено</a></td>';
                    }
                    if($row->status_id == 3){
                      echo '<td><a href="/add.php?id='.$row->id.'&table=status_done&new_date='.$new_date.'&N='.$N.'" class="button_filter_selected">Исполнено</a></td>';
                    }
                    else {
                      echo '<td><a href="/add.php?id='.$row->id.'&table=status_done&new_date='.$new_date.'&N='.$N.'" class="button_filter">Исполнено</a></td>';
                    }
                    echo '<td><a href="/delete.php?id='.$row->id.'&table=schedule&new_date='.$new_date.'&N='.$N.'&worker_id='.$worker_id.'&client_id='.$client_id.'&service_id='.$service_id.'&price='.$price.'&time_start='.$row->time_start.
                    '&time_end='.$row->time_end.'&status_id='.$row->status_id.'" class="button_filter">Удалить</a></td></tr></table></td><tr>';
                }
                else{
                    echo '<td></td><td></td><td></td><td></td>
                    <td><a href="/delete.php?id='.$row->id.'&table=schedule&new_date='.$new_date.'&N='.$N.'&worker_id='.$worker_id.'&client_id='.$client_id.'&service_id='.$service_id.'&price='.$price.'&time_start='.$row->time_start.
                    '&time_end='.$row->time_end.'&status_id='.$row->status_id.'" class="button_filter">Удалить</a></td><tr>';

                }
	          }
            // $query = $pdo->query('SELECT ws.id, w.worker_id, c.client_name, s.service_name, s.price FROM workers AS w, worker_schedule AS ws, clients AS c, services AS s
            //   WHERE w.worker_id = ws.worker_id AND ws.client_id = c.client_id AND ws.service_id = s.service_id AND ws.assigned_date = "'.$new_date.'" ORDER BY w.worker_id, ws.time_start ASC');
            // while($row = $query->fetch(PDO::FETCH_OBJ)) {
            //     echo '<td>'.$row->client_name.'</td><td>'.$row->service_name.'</td><td>'.$row->price.'</td>
            //     <td><a href="/delete.php?id='.$row->id.'&table=schedule&new_date='.$new_date.'&N='.$N.'"><button>Удалить</button></a></td><tr>';
            // }
            echo '</table>';
	        ?>
          <script>
            // var schedule_div = document.getElementById("schedule_div");
            // schedule_div.scrollTop = 100;
            function getCookie(name) {
              let matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
              ));
              return matches ? decodeURIComponent(matches[1]) : undefined;
            }
            var scroll_pos = getCookie("scroll_pos");
            document.getElementById("schedule_div").scrollTop = scroll_pos;
          </script>
				</div>
				<div class="shadowbox_column_left"><h2>Неназначенные сотрудники</h2>
					<?php
						$new_date = $_GET['new_date'];
            $N = $_GET['N'];
            if($N == null){
              $N = 0;
            }
						if($new_date == null){
							$new_date = date("Y-m-d");
						}
	          require 'configDB.php';
	          // $worker_id = $_GET['worker_id'];

						// $query = $pdo->query('SELECT service_id,service_name FROM services LEFT JOIN (SELECT s.service_name FROM workers_services AS ws,services AS s WHERE ws.worker_id = '.$worker_id.' AND ws.service_id = s.service_id ORDER BY ws.worker_id DESC) AS t2 USING (service_name) WHERE t2.service_name IS NULL ORDER BY service_id DESC');
	          $query = $pdo->query('SELECT * FROM workers LEFT JOIN (SELECT w.worker_id FROM workers AS w, worker_schedule AS s WHERE w.worker_id = s.worker_id AND s.assigned_date = "'.$new_date.'" ORDER BY w.worker_id ASC) AS t2 USING (worker_id) WHERE t2.worker_id IS NULL ORDER BY worker_id ASC');
	          while($row = $query->fetch(PDO::FETCH_OBJ)) {
	              echo '<br><a href="/assign.php?worker_id='.$row->worker_id.'&worker_name='.$row->worker_name.'&date='.$new_date.'&N='.$N.'" name="workeridButton" class="button_worker"><< '.$row->worker_name.'</a><br>';
	          }
	        ?>
				</div>
			</div>
		</div>
	</body>

</html>
