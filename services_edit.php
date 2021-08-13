<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Услуги</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
  <div class="work_area">
    <h1>Услуги</h1>
    <form action="/add.php" method="post">
      <input type="text" name="service_name" id="service_name" placeholder="Наименование услуги" class="form-control">
  	  <br>
      <input type="text" name="price" id="price" placeholder="Стоимость" class="form-control">
      <br>
      <input type="text" name="duration" id="duration" placeholder="Длительность" class="form-control">
      <br>
  	  <input type="text" name="description" id="description" placeholder="Описание" class="form-control">
  	  <br>
      <button type="submit" value="services" name="sendButton" class="btn btn-success">Добавить</button>
    </form>
      <div>
      <?php
        require 'configDB.php';

        echo '<table>';
  	    echo '<caption>SERVICES</caption>
  				<tr>
  				<th>ID</th>
  				<th>Услуга</th>
  				<th>Цена</th>
          <th>Длительность</th>
          <th>Описание</th>
          <th>Действие</th>
  				</tr>';
        $query = $pdo->query('SELECT * FROM `services` ORDER BY `service_id` DESC');
        while($row = $query->fetch(PDO::FETCH_OBJ)) {
          echo '<tr><td>'.$row->service_id.'</td><td>'.$row->service_name.'</td><td>'.$row->price.'</td><td>'.$row->duration.'</td><td>'.$row->description.'</td><td><a href="/delete.php?id='.$row->service_id.'&table=services"><button>Удалить</button></a></td>';
        }
        echo '</table>';
      ?>
    </div>
  </div>
</body>
</html>
