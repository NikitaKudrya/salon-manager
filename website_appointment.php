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
    <h1>Запись через сайт</h1>
    <form action="/add.php" method="post">
      <table class="assign_table">
        <tr>
          <td id="description"><p class="assign">Имя</p></td>
          <td><input type="text" name="client_name" id="client_name" class="form-control"></td>
        </tr>
        <tr>
          <td id="description"><p class="assign">Телефон</p></td>
          <td><input type="text" name="phone_number" id="phone_number" class="form-control"></td>
        </tr>
        <tr>
          <td><button type="submit" value="website_appointment" name="sendButton" class="btn btn-success" onclick="setInputCookies();">Далее</button></td>
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

            function setInputCookies() {
              var client_name = document.getElementById("client_name").value;
              var phone_number = document.getElementById("phone_number").value;
              // document.getElementById("demo").innerHTML = schedule_div;
              setCookie('client_name', client_name, {secure: true, 'max-age': 3600});
              setCookie('phone_number', phone_number, {secure: true, 'max-age': 3600});
            }

            function getCookie(name) {
              let matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
              ));
              return matches ? decodeURIComponent(matches[1]) : undefined;
            }
            var client_name = getCookie("client_name");
            var phone_number = getCookie("phone_number");
            document.getElementById("client_name").value = client_name;
            document.getElementById("phone_number").value = phone_number;
          </script>
        </tr>
      </table>
    </form>
  </div>
</body>
</html>
