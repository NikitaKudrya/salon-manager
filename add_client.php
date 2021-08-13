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
    <h1>Добавление клиента</h1>
    <form action="/add.php" method="post">
      <input type="text" name="client_name" id="client_name" placeholder="Имя клиента" value="" class="form-control">
      <br>
      <input type="text" name="phone_number" id="phone_number" placeholder="Номер телефона" value="" class="form-control">
      <br>
      <input type="text" onfocus="(this.type='date')" name="registration_date" id="registration_date" placeholder="Дата регистрации" value="" class="form-control">
      <br>
      <button type="submit" value="clients" name="sendButton" class="btn btn-success">Добавить</button>
    </form>
  </div>
</body>
</html>
