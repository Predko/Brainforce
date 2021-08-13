<?php

  $title = "Main Page";

  $content = <<<END
  <section class="main-page">
    <h1>Работа с базой данных Mysql.</h1>
    <h1>Чтение данных из файла xls.</h1>
    <div id="mainpage-info">
      <form method="POST" action="login.php">
          <p>Логин: <input type="text" name="login" placeholder="brainforce"/></p>
          <p>Пароль: <input type="text" name="password" placeholder="123456"/></p>
          <input type="submit" value="Войти" />
      </form>
    </div>
  </section>
  END;

  include "templates/layout.php";

  ?>
