<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Услуги</title>
  <link rel="stylesheet" href="css/schedule.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
  <div class="work_area">
    <h1>Запись</h1>
    <form action="/add.php" method="post">
      <?php
        $id = $_GET['id'];
        $new_date = $_GET['new_date'];
        $N = $_GET['N'];
        $worker_id = $_GET['worker_id'];
        $worker_name = $_GET['worker_name'];
        $client_id = $_GET['client_id'];
        $service_id = $_GET['service_id'];
        $price = $_GET['price'];
        $selected_client_id = $_GET['selected_client_id'];
        $selected_service_id = $_GET['selected_service_id'];
        $selected_price = $_GET['selected_price'];
        $service_name = $_GET['service_name'];
        $duration = $_GET['duration'];
        $time_start = $_GET['time_start'];
        $time_end = $_GET['time_end'];
        // echo $time_end.'<br>';
        // echo $duration.'<br>';
        $end = strtotime($time_end)-strtotime($duration);
        // $result = strtotime($time_start)-strtotime("00:00:00");
        // echo $end.'<br>';
        // echo $result;
        echo '<input type="text" hidden="true" readonly="true" name="id" id="id" value="'.$id.'" class="form-control">
              <input type="text" hidden="true" readonly="true" name="worker_id" id="worker_id" value="'.$worker_id.'" class="form-control">
              <input type="text" hidden="true" readonly="true" name="client_id" id="client_id" value="'.$client_id.'" class="form-control">
              <input type="text" hidden="true" readonly="true" name="service_id" id="service_id" value="'.$service_id.'" class="form-control">
              <input type="text" hidden="true" readonly="true" name="price" id="price" value="'.$price.'" class="form-control">
              <input type="text" hidden="true" readonly="true" name="selected_client_id" id="selected_client_id" value="'.$selected_client_id.'" class="form-control">
              <input type="text" hidden="true" readonly="true" name="selected_service_id" id="selected_service_id" value="'.$selected_service_id.'" class="form-control">
              <input type="text" hidden="true" readonly="true" name="time_start" id="time_start" value="'.$time_start.'" class="form-control">
              <input type="text" hidden="true" readonly="true" name="time_end" id="time_end" value="'.$time_end.'" class="form-control">
              <input type="text" hidden="true" readonly="true" name="duration" id="duration" value="'.$duration.'" class="form-control">
        <table class="assign_table">
        <tr>
          <td id="description"><p class="assign">Дата</p></td>
        <td ><input type="text" readonly="true" name="new_date" id="new_date" value="'.$new_date.'" class="form-control"></td></tr>
        <tr>
          <td id="description"><p class="assign">Услуга</p></td>
        <td ><input type="text" readonly="true" name="service_name" id="service_name" value="'.$service_name.'" class="form-control"></td></tr>
        <tr>
          <td id="description"><p class="assign">Цена</p></td>
        <td ><input type="text" readonly="true" name="selected_price" id="selected_price" value="'.$selected_price.'" class="form-control"></td></tr>
        <tr>
          <td id="description"><p class="assign">Мастер</p></td>
        <td ><input type="text" readonly="true" name="worker_name" id="worker_name" value="'.$worker_name.'" class="form-control"></td></tr>
        <tr>
          <td id="description"><p class="assign">Время</p></td>
        <td >
        <select name="time" id="time" class="form-control">';
        // $secs = strtotime($duration)-strtotime("00:00:00");
        // $end = date("H:i:s",strtotime($time_end)-$secs);
        $result = strtotime($time_start)-strtotime("00:00:00");
        $string = date("H:i:s", $result);
        while ($result <= $end) {
          echo '<option value="'.$string.'">'.$string.'</option>';
          $secs = strtotime("00:30:00")-strtotime("00:00:00");
          $result = $result+$secs;
          $string = date("H:i:s", $result);
        }
        // echo '<option>'.$time_end.'</option>
        echo '</select>
        </td></tr>
        </table>
        <br>
        <button type="submit" value="time_select" name="sendButton" class="btn btn-success">Оформить визит</button>';
       ?>

    </form>
  </div>
</body>
</html>
