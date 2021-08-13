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
    <h1>Назначение сотрудника</h1>
    <form action="/add.php" method="post">
      <?php
        $worker_id = $_GET['worker_id'];
        $worker_name = $_GET['worker_name'];
        $date = $_GET['date'];
        $N = $_GET['N'];
        echo '<p class="assign">'.$worker_name.'</p>';
        echo '<input type="text" hidden="true" readonly="true" name="N" id="N" value="'.$N.'" class="form-control">';
        echo '<input type="text" hidden="true" readonly="true" name="worker_id" id="worker_id" value="'.$worker_id.'" class="form-control">';
        echo '<table class="assign_table">
        <tr>
          <td id="description"><p class="assign">Дата начала</p></td>
        <td ><input type="date" name="date_start" id="date_start" placeholder="Дата начала" value="'.$date.'" class="form-control"></td></tr>';
        echo '<tr>
                  <td id="description"><p class="assign">Дата окончания</p></td>
                  <td ><input type="date" name="date_end" id="date_end" placeholder="" value="'.$date.'" class="form-control"></td>
                </tr>';
      ?>
        <tr>
          <td id="description"><p class="assign">Время окончания</p></td>
          <td ><input type="time" name="time_start" id="time_start" placeholder="" class="form-control"></td>
        </tr>
        <tr>
          <td id="description"><p class="assign">Время окончания</p></td>
          <td ><input type="time" name="time_end" id="time_end" placeholder="" class="form-control"></td>
        </tr>
      </table>
      <button type="submit" value="assign" name="sendButton" class="btn btn-success">Назначить</button>
    </form>
  </div>
</body>
</html>
