<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Сотрудники</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
  <div class="work_area">
    <h1>Сотрудники</h1>
    <form action="/add.php" method="post">
      <input type="text" name="worker_name" id="worker_name" placeholder="Имя сотрудника" class="form-control">
	  <br>
	  <input type="text" name="phone_number" id="phone_number" placeholder="Номер телефона" class="form-control">
	  <br>
      <button type="submit" value="workers" name="sendButton" class="btn btn-success">Добавить</button>
    </form>

    <?php
      require 'configDB.php';

      echo '<table id="db_table">';
	    echo '<caption>WORKERS</caption>
				<tr>
				<th>ID</th>
				<th>Мастер</th>
				<th>Номер телефона</th>
        <th>Действие</th>
				</tr>';
      $query = $pdo->query('SELECT * FROM `workers` ORDER BY `worker_id` DESC');
      while($row = $query->fetch(PDO::FETCH_OBJ)) {
        echo '<tr><td>'.$row->worker_id.'</td><td>'.$row->worker_name.'</td><td>'.$row->phone_number.'</td><td><a href="/delete.php?id='.$row->worker_id.'&table=workers"><button>Удалить</button></a></td>';
      }
      echo '</table>';
    ?>

  </div>
</body>
</html>
