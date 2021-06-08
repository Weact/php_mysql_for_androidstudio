<?php
include ("include/dboperation.php");
$dboperation = new dboperation();
$dboperation->check_credentials_and_submit();
 ?>

 <!DOCTYPE html>
  <html>
    <head>
      <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
      <br><br><br>
      <span style="font-size: 30px; color: blue; font-weight:bolder;">SIGN UP PANNEL</span>
      <form name="user_informations" id="myForm" class="user_infos" method="get " action="#">
        <div class="form-box">
          <label for="username">Nom de l'utilisateur:</label>
          <input type="text" name="user_username" id="user_username" placeholder="Entrez votre nom utilisateur *" maxlength="16">
        </div>
        <div class="form-box">
          <label for="password">Mot de passe:</label>
          <input type="password" name="user_password" id="user_password" placeholder="Entrez votre mot de passe *">
        </div>
        <div class="form-box">
          <label for="user_name">Email: </label>
          <input type="text" name="user_email" id="user_email" placeholder="Entrez votre email *" maxlength="64">
        </div>
        <div class="form-box">
          <input id="sbmbutton" type="submit" value="Envoyer"><br><br>
          <input id="searchbutton" type="submit" value="Rechercher"><br><br>
          <input id="rstbutton" type="reset" value="Réinitialiser"><br>
        </div>
      </form>

      <br>
     <span style="font-size: 30px; color: blue; font-weight:bolder;">SIGN IN PANNEL</span>

      <form name="user_login" id="user_login" class="user_login" method="get" action="v1/userLogin.php">
        <div class="form-box">
          <label for="username">Nom de l'utilisateur:</label>
          <input type="text" name="user_username_login" id="user_username" placeholder="Entrez votre nom utilisateur *" maxlength="16">
        </div>
        <div class="form-box">
          <label for="password">Mot de passe:</label>
          <input type="password" name="user_password_login" id="user_password" placeholder="Entrez votre mot de passe *">
        </div>

        <div class="form-box">
          <input id="sbmbutton" type="submit" value="Login"><br><br>
          <input id="rstbutton" type="reset" value="Réinitialiser"><br>
        </div>
      </form>
    <footer>
      <a href="test2.php" class="footer-link">Reset page</a>
    </footer>
    </body>
  </html>
