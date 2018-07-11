<?php

	$activePage = basename($_SERVER['PHP_SELF'], ".php");
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Forum</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css?family=Bangers|Roboto|Acme" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://harvesthq.github.io/chosen/chosen.css">
</head>
<body>
<div class="wrapper">
<nav class="nav">
  <ul>           
    <li class="left"><a href="home.php" class="<?= ($activePage == 'home') ? 'active':''; ?>"><i class="fas fa-home"></i>Home</a></li>
    <li><a href="forum.php" class="<?= ($activePage == 'forum') ? 'active':''; ?>">
    	<i class="fas fa-cog"></i>Forum</a></li>
    <li><a href="login.php" class="log-in <?= ($activePage == 'login') ? 'active':''; ?>">Log in</a></li>
    <li><a href="index.php?logout=true" class="logout">Log out</a></li>
  </ul>
</nav>