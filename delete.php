<?php
  require 'configDB.php';
  $table = $_GET['table'];
  switch ($table) {
    case "workers":
      $id = $_GET['id'];

      $sql = 'DELETE FROM workers WHERE worker_id = ?';
      $query = $pdo->prepare($sql);
      $query->execute([$id]);

      header('Location: /workers_edit.php');
      break;
    case "services":
      $id = $_GET['id'];

      $sql = 'DELETE FROM services WHERE service_id = ?';
      $query = $pdo->prepare($sql);
      $query->execute([$id]);

      header('Location: /services_edit.php');
      break;
    case "schedule":
      $id = $_GET['id'];
      $worker_id = $_GET['worker_id'];
      echo "WORKER ".$worker_id;
      $new_date = $_GET['new_date'];
      $time_start = $_GET['time_start'];
      $time_end = $_GET['time_end'];
      $client_id = $_GET['client_id'];
      $service_id = $_GET['service_id'];
      $price = $_GET['price'];
      $status_id = $_GET['status_id'];
      $N = $_GET['N'];

      $sql = "CALL delete_from_schedule(".$id.", ".$worker_id.", '".$new_date."', '".$time_start."', '".$time_end."', ".$client_id.", ".$service_id.", ".$price.", ".$status_id.", @result_delete)";
      echo $sql;

      $query = $pdo->prepare($sql);
      $query->execute();
      $query->closeCursor();
      $row = $pdo->query("SELECT @result_delete AS result_delete")->fetch(PDO::FETCH_OBJ);
      // var_dump($row);
      echo "RESULT: ".$row->result_delete;
      if($row->result_delete == 0){
        echo '<script>
                alert("Удаляемая запись уже не существует или изменилась. Расписание будет обновлено.");
                window.location.href="/schedule.php?new_date='.$new_date.'&N='.$N.'";
              </script>';
        // header('Location: /schedule.php');
      }
      else {
        header('Location: /schedule.php?new_date='.$new_date.'&N='.$N);
      }
      // $query = $pdo->prepare($sql);
      // $query->execute();

      // header('Location: /schedule.php?new_date='.$new_date.'&N='.$N);
      break;
  }
?>
