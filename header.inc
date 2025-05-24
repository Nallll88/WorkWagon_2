<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?php echo htmlspecialchars($page_title); ?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="images/icon1.png" type="image/x-icon">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/layout.css">
  <link rel="stylesheet" href="css/enhancements.css">
</head>
<body id="<?php echo strtolower(str_replace(' ', '', $page_title)); ?>Body">
  <header>
    <a href="index.php" class="logo">WorkWagon</a>