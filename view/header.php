<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= $title ?> | Bbc MVC</title>

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link href="/css/style.css" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
  <div class="header-bar">
    <form class="search-form" action="/user/search" method="post">
      <div>
        <input id="" class="input-search" type="text" name="searchbar" placeholder="Search">
        <!--<div>TODO Search icon</div>-->
      </div>
    </form>
    <button id="header-button" onclick="location='/user/logout'">Logout</button>
    <a class="settings glyphicon glyphicon-cog" href="/user/user_settings"></a>
  </div>
  <div class="main">
    <div class="header-column">
      <a href="/user"><div class="logo"><img src="/images/run.png" width="150"></div> </a>
      <div class="properties"></div>
    </div>
    <div class="container">
