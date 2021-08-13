<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Сотрудники</title>
  <link rel="stylesheet" href="css/schedule.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
  <div class="work_area">
    <h1>Выбор клиента</h1>
    <form action="/add.php" method="post">
      <table class="assign_table">
        <tr>
          <td id="search"><p class="assign">Имя/номер телефона содержит:</p></td>
          <td><input type="text" name="search_bar" id="search_bar" value="<?$search_bar = $_GET['search_bar']; echo $search_bar;?>" class="form-control"></td>
        </tr>
        <tr>
          <td><button type="submit" value="search" name="sendButton" class="btn btn-success">Поиск</button></td>
          <td><a href="/add_client.php" class="btn btn-success">Новый клиент</a></td>
        </tr>
      </table>
    </form>

    <?php
      $search_bar = $_GET['search_bar'];
      require 'configDB.php';

      // echo '<p>'.$search_bar.'</p>';
      echo '<table class="schedule_table">';
	    echo '<caption>CLIENTS</caption>
				<tr>
				<th>Клиент</th>
				<th>Номер телефона</th>
				<th>Дата регистрации</th>
        <th>Действие</th>
				</tr>';
      if($search_bar == null){
        $query = $pdo->query('SELECT * FROM clients ORDER BY client_id DESC');
      }
      else{
        $pattern = '%'.$search_bar.'%';
        $query = $pdo->query('SELECT * FROM clients WHERE concat(client_name, phone_number) LIKE "'.$pattern.'" ORDER BY client_id DESC');
      }
      while($row = $query->fetch(PDO::FETCH_OBJ)) {
        echo '<tr><td>'.$row->client_name.'</td><td>'.$row->phone_number.'</td><td>'.$row->registration_date.'</td><td><a href="/service_select.php?client_id='.$row->client_id.'"><button>Выбрать</button></a></td>';
      }
      echo '</table>';
    ?>

  </div>
</body>
</html>
