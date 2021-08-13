<?php
  $worker_id = $_GET['worker_id'];
  $service_id = $_GET['service_id'];
  $action = $_GET['action'];
  require 'configDB.php';
  if($action == 'add'){
    $sql = 'INSERT INTO workers_services(worker_id, service_id) VALUES(:worker_id, :service_id)';
    $query = $pdo->prepare($sql);
    $query->execute(['worker_id' => $worker_id, 'service_id' => $service_id]);
  }
  elseif($action == 'delete') {
    $sql = 'DELETE FROM workers_services WHERE worker_id = :worker_id AND service_id = :service_id';
    $query = $pdo->prepare($sql);
    $query->execute(['worker_id' => $worker_id, 'service_id' => $service_id]);
  }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Сотрудники</title>
  <link rel="stylesheet" href="css/style0.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
  <div class="work_area">
    <h1>Навыки</h1>
    <div class="shadowbox">
        <h2>Сотрудники</h2>
        <?php
          require 'configDB.php';
          $worker_id = $_GET['worker_id'];
          if($worker_id == null){
            $worker_id = 2;
          }

          $query = $pdo->query('SELECT * FROM `workers` ORDER BY `worker_id` ASC');
          while($row = $query->fetch(PDO::FETCH_OBJ)) {
            if($row->worker_id == $worker_id){
              echo '<br><a href="/abilities_edit.php?worker_id='.$row->worker_id.'" name="workeridButton" class="button_worker_selected">'.$row->worker_name.'</a><br>';
            }
            else {
              echo '<br><a href="/abilities_edit.php?worker_id='.$row->worker_id.'" name="workeridButton" class="button_worker">'.$row->worker_name.'</a><br>';
            }
          }
        ?>
    </div>
    <div class="shadowbox">
      <h2>Назначенные навыки</h2>
      <?php
			  $worker_id = $_GET['worker_id'];
        if($worker_id == null){
          $worker_id = 2;
        }
				require 'configDB.php';

				// $query = $pdo->query('SELECT * FROM `workers_services` AS `ws`,`services` AS `s` WHERE `ws.worker_id` = '.worker_id.' AND `ws.service_id` = `s.service_id` ORDER BY `ws.worker_id` DESC');
        $query = $pdo->query('SELECT s.service_id,s.service_name FROM workers_services AS ws,services AS s WHERE ws.worker_id = '.$worker_id.' AND ws.service_id = s.service_id ORDER BY ws.worker_id DESC');
        if($worker_id != null){
          while($row = $query->fetch(PDO::FETCH_OBJ)) {
            echo '<br><a href="/abilities_edit.php?worker_id='.$worker_id.'&service_id='.$row->service_id.'&action=delete" name="serviceButton" class="button_worker">'.$row->service_name.' >></a><br>';
          }
        }
        // echo '<p>'.$worker_id.'</p>';

			?>
    </div>
    <div class="shadowbox">
      <h2>Неназначенные навыки</h2>
        <?php
          $worker_id = $_GET['worker_id'];
          if($worker_id == null){
            $worker_id = 2;
          }
          require 'configDB.php';

          $query = $pdo->query('SELECT service_id,service_name FROM services LEFT JOIN (SELECT s.service_name FROM workers_services AS ws,services AS s WHERE ws.worker_id = '.$worker_id.' AND ws.service_id = s.service_id ORDER BY ws.worker_id DESC) AS t2 USING (service_name) WHERE t2.service_name IS NULL ORDER BY service_id DESC');

          while($row = $query->fetch(PDO::FETCH_OBJ)) {
            echo '<br><a href="/abilities_edit.php?worker_id='.$worker_id.'&service_id='.$row->service_id.'&action=add" name="serviceButton" class="button_worker"><< '.$row->service_name.'</a><br>';
          }
        ?>
    </div>
    <!-- <ul id="abilities_edit" class="hr">
      <li></li>
      <li></li>
      <li></li>
    </ul> -->
<!--
    <?php
      require 'configDB.php';
    ?> -->

  </div>
</body>
</html>
