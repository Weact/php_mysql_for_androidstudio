<?php
include ("include/dbconnect.php");
//include ("css/style.css");
define('DBMODE', 'PDO');

$dbconnect = new dbconnect();
$dbconnect = connect_to_database($dbconnect);


var_dump($dbconnect);
var_dump($dbconnect->get_conn());

echo dirname(__FILE__);
echo "<br><br>";
check_credentials_and_submit($dbconnect->get_conn());

/*function connect_to_database($db){
  if(DBMODE === "PDO"){
    $db -> connect_pdo();
  }
  if(DBMODE === "MYSQLI"){
    $db -> connect_mysqli();
  }

  return $db;
}*/

/*function check_credentials_and_submit($conn){
  echo "<span style='color: gold; font-size: 26px; font-weight: bolder;'>SERVER METHOD : " . $_SERVER['REQUEST_METHOD'] . "</span>";
  if(isset($_GET['user_username']) && (!empty($_GET['user_username']) && !empty($_GET['user_password']) && !empty($_GET['user_email']))){

    if(DBMODE === "PDO"){create_user_pdo($conn, $_GET['user_username'], $_GET['user_password'], $_GET['user_email']);};
    if(DBMODE === "MYSQLI"){create_user_mysqli($conn, $_GET['user_username'], $_GET['user_password'], $_GET['user_email']);};

  }
  if(isset($_GET['user_username']) && (empty($_GET['user_username']) || empty($_GET['user_password']) || empty($_GET['user_email']))){
    $string_alert =  "<span style='color: red; font-weight: bolder; font-size: 14px;'> No user entries found or one or more fields need to be filled.";

    if(empty($_GET['user_username'])){$string_alert .= "<br>Username is empty";}
    if(empty($_GET['user_password'])){$string_alert .= "<br>Password is empty";}
    if(empty($_GET['user_email'])){$string_alert .= "<br>Email is empty";}

    $string_alert .= "</span>";
    echo $string_alert;
  }

  if(isset($_GET['user_username_login']) && (!empty($_GET['user_username_login']) && !empty($_GET['user_password_login']))){
    $userLogin = login($conn, $_GET['user_username_login'], $_GET['user_password_login']);
    if($userLogin == 0){
      echo "<span style='color: red; font-size: 24px; font-weight: bolder;'>WELCOME ".$_GET['user_username_login']." !</span>";
    }else{
      echo "<span style='color: red; font-size: 24px; font-weight: bolder;'>ERROR, WRONG CREDENTIALS FOR SIGN IN</span>";
    }
  }
  if(isset($_GET['user_username_login']) && (empty($_GET['user_username_login']) || empty($_GET['user_password_login']))){
    $string_alert =  "<span style='color: red; font-weight: bolder; font-size: 14px;'> No user entries found or one or more fields need to be filled.";

    if(empty($_GET['user_username_login'])){$string_alert .= "<br>Username Login is empty";}
    if(empty($_GET['user_password_login'])){$string_alert .= "<br>Password Login is empty";}

    $string_alert .= "</span>";
    echo $string_alert;
  }
}*/

/*function create_user_pdo($conn, $username, $psw, $email){
    echo "<span style='color: gray; font-size: 18px; font-weight: bolder;'>Username : " . $username . "</span><br>";
    echo "<span style='color: gray; font-size: 18px; font-weight: bolder;'>Password : " . $psw . "</span><br>";
    echo "<span style='color: gray; font-size: 18px; font-weight: bolder;'>Email : " . $email . "</span><br>";

    $doesUserExist;
    if(DBMODE === "PDO"){$doesUserExist = userExist_pdo($conn, $username, $email);}
    if(DBMODE === "MYSQLI"){$doesUserExist = userExist_mysqli($conn, $username, $email);}

    if($doesUserExist > 0){
      echo "<span class='conn_failed'>[PDO] CANNOT INSERT USER. THIS USER ALREADY EXISTS.</span><br><br>";
      return 2;
    }

    //pdo
    try{
      $stmt = $conn->prepare('INSERT INTO user (username, password, email) VALUES (?, ?, ?);');
    }catch(Exception $e){
      echo "<span class='conn_failed'>[PDO] STMT PREPARE failed : " . $e->getMessage() . "</span><br><br>";
    }

    $psw = md5($psw);

    $stmt->bindParam(1, $username, PDO::PARAM_STR, 16);
    $stmt->bindParam(2, $psw, PDO::PARAM_STR, 16);
    $stmt->bindParam(3, $email, PDO::PARAM_STR, 16);

    try{
      $stmt->execute();
      return 1;
    }catch(Exception $e){
      echo "<span class='conn_failed'>[PDO] STMT EXECUTE failed : " . $e->getMessage() . "</span><br><br>";
    }
}*/

/*function create_user_mysqli($conn, $username, $psw, $email){
    echo "<span style='color: gray; font-size: 18px; font-weight: bolder;'>Username : " . $username . "</span><br>";
    echo "<span style='color: gray; font-size: 18px; font-weight: bolder;'>Password : " . $psw . "</span><br>";
    echo "<span style='color: gray; font-size: 18px; font-weight: bolder;'>Email : " . $email . "</span><br>";

    //mysqli
    try{
      $stmt = $conn->prepare('INSERT INTO user (username, password, email) VALUES (?, ?, ?);');
    }catch(Exception $e){
      echo "<span class='conn_failed'>[PDO] STMT PREPARE failed : " . $e->getMessage() . "</span><br><br>";
    }

    $stmt->bind_param('sss', $username, $psw, $email);

    try{
      $stmt->execute();
      echo "<span class='conn_success'>[PDO] Insert Success<br><br>";
      echo $stmt->affected_rows . "</span>";
      $conn->close();
    }catch(Exception $e){
      echo "<span class='conn_failed'>[PDO] STMT EXECUTE failed : " . $e->getMessage() . "</span><br><br>";
    }
}*/

/*function userExist_pdo($conn, $username, $email){
  try {
    $stmt = $conn->prepare('SELECT  COUNT(*) FROM user WHERE username = ? OR email = ?');
  } catch (Exception $e) {
    echo "<span class='conn_failed'>[PDO] STMT PREPARE failed : " . $e->getMessage() . "</span><br><br>";
  }

  $stmt->bindParam(1, $username, PDO::PARAM_STR, 16);
  $stmt->bindParam(2, $email, PDO::PARAM_STR, 64);

  try {
    $stmt->execute();
  } catch (Exception $e) {
      echo "<span class='conn_failed'>[PDO] STMT PREPARE failed : " . $e->getMessage() . "</span><br><br>";
  }

  $result = $stmt->fetchColumn();
  return $result;
}*/

/*function userExist_mysqli($conn, $username, $email){
  try {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM user WHERE username = ? OR email = ?');
  } catch (Exception $e) {
    echo "<span class='conn_failed'>[PDO] STMT PREPARE failed : " . $e->getMessage() . "</span><br><br>";
  }

  $stmt->bind_param('ss', $username, $email);

  try {
    $stmt->execute();
    echo "<span class='conn_success'>[PDO] Insert Success<br><br>";
    echo $stmt->affected_rows . "</span>";
    $conn->close();
  } catch (Exception $e) {
    echo "<span class='conn_failed'>[PDO] STMT EXECUTE failed : " . $e->getMessage() . "</span><br><br>";
  }

  $result = $stmt->mysqli_field_count();
  return $result;
}*/

/*function login($conn, $username, $psw){
  try {
    $stmt = $conn->prepare('SELECT * FROM user WHERE username = ?');
  } catch (Exception $e) {
    echo "<span class='conn_failed'>[PDO] STMT PREPARE failed : " . $e->getMessage() . "</span><br><br>";
  }

  $stmt->bindParam(1, $username, PDO::PARAM_STR, 16);

  try {
    $stmt->execute();
  } catch (Exception $e) {
    echo "<span class='conn_failed'>[PDO] STMT EXECUTE failed : " . $e->getMessage() . "</span><br><br>";
  }

  $userResult = $stmt->fetch(PDO::FETCH_OBJ);
  $userResult_username = $userResult->username;
  $userResult_password = $userResult->password;

  if(md5($psw) === $userResult_password){
    return 0;
  }else{
    return 2;
  }
}*/

 ?>

<!DOCTYPE html>
 <html>
   <head>
     <link rel="stylesheet" href="css/style.css">
   </head>
   <body>
     <br><br><br>
     <span style="font-size: 30px; color: blue; font-weight:bolder;">SIGN UP PANNEL</span>;
     <form name="user_informations" id="myForm" class="user_infos" method="get" action="#">
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
    <span style="font-size: 30px; color: blue; font-weight:bolder;">SIGN IN PANNEL</span>;

     <form name="user_login" id="user_login" class="user_login" method="get" action="#">
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
     <a href="test.php" class="footer-link">Reset page</a>
   </footer>
   </body>
 </html>
