<?php
  $sendButton = $_POST['sendButton'];
  require 'configDB.php';

  switch ($sendButton) {
    case "workers":
        $worker_name = $_POST['worker_name'];
        $phone_number = $_POST['phone_number'];
        if($worker_name == '') {
          echo 'Поля должны быть заполнены';
          exit();
        }

        $sql = 'INSERT INTO workers(worker_name, phone_number) VALUES(:worker_name, :phone_number)';
        $query = $pdo->prepare($sql);
        $query->execute(['worker_name' => $worker_name, 'phone_number' => $phone_number]);

        header('Location: /workers_edit.php');
        print_r($_POST);
        break;
    case "services":
        $service_name = $_POST['service_name'];
        $price = $_POST['price'];
        $duration = $_POST['duration'];
        $description = $_POST['description'];
        if($service_name == '') {
          echo 'Поля должны быть заполнены';
          exit();
        }

        $sql = 'INSERT INTO services(service_name, price, duration, description) VALUES(:service_name, :price, :duration, :description)';
        $query = $pdo->prepare($sql);
        $query->execute(['service_name' => $service_name, 'price' => $price, 'duration' => $duration, 'description' => $description]);

        header('Location: /services_edit.php');
        print_r($_POST);
        break;
    case "clients":
        $client_name = $_POST['client_name'];
        $phone_number = $_POST['phone_number'];
        $registration_date = $_POST['registration_date'];
        if($client_name == '') {
          echo 'Поля должны быть заполнены';
          exit();
        }

        $sql = 'INSERT INTO clients(client_name, phone_number, registration_date) VALUES(:client_name, :phone_number, :registration_date)';
        $query = $pdo->prepare($sql);
        $query->execute(['client_name' => $client_name, 'phone_number' => $phone_number, 'registration_date' => $registration_date]);

        header('Location: /clients_edit.php');
        print_r($_POST);
        break;
    case "assign":
        $worker_id = $_POST['worker_id'];
        echo $worker_id.' id ';
        $date_start = $_POST['date_start'];
        echo $date_start.' date start ';
        $date_end = $_POST['date_end'];
        echo $date_end.' date end ';
        $time_start = $_POST['time_start'];
        $time_end = $_POST['time_end'];
        $N = $_POST['N'];
        $amount = (strtotime($date_end) - strtotime($date_start))/60/60/24;

        echo "Difference between two dates: "
            . $amount;

        if($time_start == '') {
          $date = date("Y-m-d ", strtotime($date_start . ' +1 day'));
          echo "Difference between two dates: "
              . $amount.$date;
          echo 'Поля должны быть заполнены';
          exit();
        }

        for ($i = 0; $i <= $amount; $i++) {
          $sql = 'INSERT INTO worker_schedule(worker_id, assigned_date, time_start, time_end, status_id) VALUES(:worker_id, :assigned_date, :time_start, :time_end, :status_id)';
          $query = $pdo->prepare($sql);
          $date = date("Y-m-d ", strtotime($date_start . ' +'.$i.' day'));
          $query->execute(['worker_id' => $worker_id, 'assigned_date' => $date, 'time_start' => $time_start, 'time_end' => $time_end, 'status_id' => 0]);
        }

        header('Location: /schedule.php?new_date='.$date_start.'&N='.$N);
        break;
    case "search":
        $search_bar = $_POST['search_bar'];
        header('Location: /clients_edit.php?search_bar='.$search_bar);
        break;
    case "website_appointment":
        $client_name = $_POST['client_name'];
        $phone_number = $_POST['phone_number'];
        $registration_date = date("Y-m-d");
        $sql = 'SELECT client_id FROM clients WHERE client_name = "'.$client_name.'" AND phone_number = '.$phone_number;
        echo "<br>".$sql;
        $query = $pdo->prepare($sql);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_OBJ);
        $client_id = $row->client_id;
        if($client_id == null){
          $sql = 'INSERT INTO clients(client_name, phone_number, registration_date) VALUES("'.$client_name.'", '.$phone_number.', "'.$registration_date.'")';
          echo $sql;
          $query = $pdo->prepare($sql);
          $query->execute();
          $sql = 'SELECT client_id FROM clients WHERE client_name = "'.$client_name.'" AND phone_number = '.$phone_number;
          echo "<br>".$sql;
          $query = $pdo->prepare($sql);
          $query->execute();
          $row = $query->fetch(PDO::FETCH_OBJ);
          $client_id = $row->client_id;
        }
        echo "<br>".$client_id;
        header('Location: /service_select.php?client_id='.$row->client_id);
        break;
    case "time_select":
        $id = $_POST['id'];
        echo $id.'<br>';
        $worker_id = $_POST['worker_id'];
        $new_date = $_POST['new_date'];
        $time_start = $_POST['time_start'];
        $time_end = $_POST['time_end'];
        $selected_client_id = $_POST['selected_client_id'];
        $selected_service_id = $_POST['selected_service_id'];
        $selected_price = $_POST['selected_price'];
        $client_id = $_POST['client_id'];
        $service_id = $_POST['service_id'];
        $price = $_POST['price'];
        $duration = $_POST['duration'];
        echo $duration.'<br>';
        $service_name = $_POST['service_name'];
        $worker_name = $_POST['worker_name'];
        $time = $_POST['time'];

        // $sqlTEST = "CALL test(".$id.", ".$worker_id.", '".$new_date."', '".$time_start."', '".$time_end."', ".$client_id.", ".$service_id.", ".$price.", '".$time."', @result_assign)";
        $sqlTEST = "CALL register_appointment(".$worker_id.", '".$new_date."', '".$time_start."', ".$selected_client_id.", ".$selected_service_id.", '".$time."', @result_assign)";
        echo $sqlTEST;
        $queryTEST = $pdo->prepare($sqlTEST);
        $queryTEST->execute();
        $queryTEST->closeCursor();
        $row = $pdo->query("SELECT @result_assign AS result_assign")->fetch(PDO::FETCH_OBJ);
        // var_dump($row);
        echo "RESULT: ".$row->result_assign;
        if($row->result_assign == 0){
          echo '<script>
              		alert("Не удалось записать клиента. Попробуйте снова.");
                  window.location.href="/schedule.php";
                </script>';
          // header('Location: /schedule.php');
        }
        else {
          header('Location: /schedule.php');
        }

        // $sql = "CALL register_appointment(".$id.", ".$selected_client_id.", ".$selected_service_id.", '".$time."')";
        // $query = $pdo->prepare($sql);
        // $query->execute();


        break;
}
  $table = $_GET['table'];
  switch ($table) {
    case "status_approved":
        $id = $_GET['id'];
        echo $id;
        $new_date = $_GET['new_date'];
        $N = $_GET['N'];
        $sql = 'UPDATE worker_schedule SET status_id = 2 WHERE id = '.$id;
        $query = $pdo->prepare($sql);
        $query->execute();

        header('Location: /schedule.php?new_date='.$new_date.'&N='.$N);
        break;
    case "status_not_approved":
        $id = $_GET['id'];
        $status_id = $_GET['status_id'] - 1;
        echo $status_id;
        $new_date = $_GET['new_date'];
        $N = $_GET['N'];
        $sql = 'UPDATE worker_schedule SET status_id = 1 WHERE id = '.$id;
        $query = $pdo->prepare($sql);
        $query->execute();

        header('Location: /schedule.php?new_date='.$new_date.'&N='.$N);
        break;
    case "status_done":
        $id = $_GET['id'];
        $new_date = $_GET['new_date'];
        $N = $_GET['N'];
        $sql = 'UPDATE worker_schedule SET status_id = 3 WHERE id = '.$id;
        $query = $pdo->prepare($sql);
        $query->execute();

        header('Location: /schedule.php?new_date='.$new_date.'&N='.$N);
        break;
  }

?>
