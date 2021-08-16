<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="/Styles/Site.css" />
  <title><?= $title ?></title>
</head>
<body>
  <header class="header">
    <nav class="main-menu">
        <ul>
          <li><a href="index.php">Главная</a></li>
          <li><a href="price.php">Прайс</a></li>
          <li><a href="updateTransormerTable.php">Обновить прайс трансформаторов из xls файла</a></li>
        </ul>
    </nav>
  </header>

  <main>
  <?php
    echo $content;
  ?>
  </main>

  <footer class="footer">
      <div class="content-footer">
          <p>Разработано Предко Виктором. 2021 г.</p>
      </div>
  </footer>
  <script src="js\SubmitForm.js"></script>
</body>
 </html>
