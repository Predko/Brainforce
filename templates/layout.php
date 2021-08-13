<!DOCTYPE html>
<html>
<head>
  <title><?= $title ?></title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="/Styles/Site.css" />
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

  <?php
    echo $content;
  ?>

  <footer class="footer">
      <div>
          <p>Разработано Предко Виктором. 2021 г.</p>
      </div>
  </footer>
</body>
 </html>
