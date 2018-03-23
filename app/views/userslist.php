<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Starter Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="../../css/starter-template.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">LoftSchool::DZ5</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="/user/auth">Авторизация</a></li>
                <li><a href="/user/register">Регистрация</a></li>
                <li class="active"><a href="/userslist">Список пользователей</a></li>
                <li><a href="/fileslist">Список файлов</a></li>
            </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">

      <br>
      <div class="alert alert-success" role="alert">
          <?php echo 'Вы авторизованы как&nbsp; <b>'.$data['name'].'</b>&nbsp; (логин: <b>'.$data['login'].'</b>)'; ?>
      </div>

      <?php if (isset($data['errorMessage'])) : ?>
          <br>
          <div class="alert alert-danger" role="alert">
             <?php echo $data['errorMessage']; ?>
          </div>
      <?php else : ?>

          <h2>Список пользователей</h2>
          <table class="table table-bordered">
              <tr>
                  <th>Логин</th>
                  <th>Имя</th>
                  <th>Возраст</th>
                  <th>Описание</th>
                  <th>Фотография</th>
                  <th>Действия</th>
              </tr>
              <?php foreach ($data['list'] as $user) : ?>
              <tr>
                  <td><?php echo $user['login']; ?></td>
                  <td><?php echo $user['name']; ?></td>
                  <td><?php echo $user['age']; ?></td>
                  <td><?php echo $user['description']; ?></td>
                  <td><!--<img src="http://lorempixel.com/people/200/200/" alt="">--></td>
                  <td>
                      <a href="/userslist/deleteuser" id="deletelink_<?php echo $user['id']; ?>" data-id='<?php echo $user['id']; ?>'>Удалить пользователя</a>
                  </td>
              </tr>
              <?php endforeach; ?>
          </table>

      <?php endif ?>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../../js/main.js"></script>
    <script src="../../js/bootstrap.min.js"></script>

    <script src="../../js/sweetalert2.min.js"></script>
    <script src="../../js/my.js"></script>


  </body>
</html>
