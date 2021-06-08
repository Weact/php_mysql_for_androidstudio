<?php
class dboperation{
  #MEMBERS
  private $m_dbmode = "MYSQLI";
  private $m_debug = false;
  private $m_db;

  #CONSTRUCTORS
  function __construct(){
    require_once("dbconnect.php");
    $db = new dbconnect();
    $this->m_db = $db->connect_mysqli();
    //if($this->m_dbmode === "PDO"){$this->m_db = $db->connect_pdo();}
    //if($this->m_dbmode === "MYSQLI"){$this->m_db = $db->connect_mysqli();}
  }

  #ACCESSORS
  function set_dbmode($value){
    $this->m_dbmode = $value;
  }
  function get_dbmode(){
    return $this->m_dbmode;
  }

  function get_db(){
    return $this->m_db;
  }

  function setDebugMode($value){
    $this->m_debug = $value;
  }

  function getDebugMode(){
    return $this->m_debug;
  }

  #LOGIC

    #CREDENTIALS
  function create_user(){
    $reponse;
      if($_SERVER['REQUEST_METHOD'] == "POST"){
        $userCreation = 0;

        if(isset($_POST['user_username']) && isset($_POST['user_password']) && isset($_POST['user_email']) && isset($_POST['user_birthdate']) && isset($_POST['user_localite'])){
          $userCreation = $this->create_user_mysqli($_POST['user_username'], $_POST['user_password'], $_POST['user_email'], $_POST['user_birthdate'], $_POST['user_localite']);
          $user = $this->getUserByUsername($_POST['user_username']);

          switch($userCreation){
            case 2: //ERROR
              $reponse = array(
                'status' => true,
                'error' => false,
                'message' => "User already exist, cannot create user"
              );
              break;
            case 1:
              $reponse = array(
                'status' => true,
                'error' => false,
                'message' => "User has been created",
                'username' => $user['username'],
                'password' => $user['password'],
                'email' => $user['email'],
                'birthdate' => $user['dateNaissance'],
                'localite' => $user['Localite']
              );
              break;
            case 0:
              $reponse = array(
                'status' => false,
                'error' => true,
                'message' => "Insert Error, please try again"
              );
              break;
          }
        }else{
          $reponse = array(
            'status' => false,
            'error' => true,
            'message' => "ISSET VARIABLES ERROR"
          );
        }//isset post variables
      }else{
        $reponse = array(
          'status' => false,
          'error' => true,
          'message' => "ISSET SERVER METHOD ERROR"
        );
      }
      return $reponse;
    }

    #LOGIN CREDENTIALS

  function checkLoginCredentialsAndSubmit(){
    $reponse;
    if($_SERVER['REQUEST_METHOD'] == "POST"){

      $userLoginState = 0;

      //check if username and password are set, if yes, will try to login and return 0 for credentials error, 1 if ok, 2 if error
      $userLoginState = $this->areCredentialsSetAndLogin();
      $userResult = $this->getUserByUsername($_POST['user_username_login']);

      switch($userLoginState){
        case 0:
          $reponse = array(
            'status' => false,
            'error' => true,
            'message' => "Invalid Credentials, cannot identify user"
          );
          break;
        case 1:
          $reponse = array(
            //DATA HAS TO BE EQUAL TO THE NAME OF THE DATABASE COLUMNS
            'status' => true,
            'error' => false,
            'message' => "User has been logged in !",
            'username' => $userResult['username'],
            'password' => $userResult['password'],
            'email' => $userResult['email'],
            'birthdate' => $userResult['dateNaissance'],
            'localite' => $userResult['Localite']
          );
          break;
        case 2:
          $reponse = array(
            'status' => false,
            'error' => true,
            'message' => "Login Error, please try again"
          );
          break;
      }//SWITCH STATEMENT
    }else{
      $reponse = array(
        'status' => false,
        'error' => true,
        'message' => "ERROR SERVER REQUEST METHOD IS NOT POST"
      );
    } //$_SERVER REQUEST METHOD GET IF STATEMENT
    return $reponse;
  } //METHOD BRACKET

    #CREATE USER
  // function create_user_pdo($username, $psw, $email){
  //     $doesUserExist;
  //     $doesUserExist = $this->userExist_pdo($username, $email);
  //     if($doesUserExist > 0){
  //       if($this->m_debug){echo "<span class='conn_failed'>[PDO] CANNOT INSERT USER. THIS USER ALREADY EXISTS.</span><br><br>";}
  //       return 0; //USER EXIST
  //     }
  //
  //     try{
  //       $stmt = $this->get_db()->prepare('INSERT INTO user (username, password, email) VALUES (?, ?, ?);');
  //     }catch(Exception $e){
  //       echo "<span class='conn_failed'>[PDO] STMT PREPARE failed : " . $e->getMessage() . "</span><br><br>";
  //       return 2; //ERROR FAIL
  //     }
  //
  //     $psw = md5($psw);
  //
  //     $stmt->bindParam(1, $username, PDO::PARAM_STR, 16);
  //     $stmt->bindParam(2, $psw, PDO::PARAM_STR, 16);
  //     $stmt->bindParam(3, $email, PDO::PARAM_STR, 16);
  //
  //     try{
  //       $stmt->execute();
  //       return 1;
  //     }catch(Exception $e){
  //       echo "<span class='conn_failed'>[PDO] STMT EXECUTE failed : " . $e->getMessage() . "</span><br><br>";
  //       return 2; //ERROR FAIL
  //     }
  //
  //     return 1; //USER CREATED
  // }

// 0 : ERROR ; 1 : SUCCESS ; 2 : USER EXISTS
  function create_user_mysqli($username, $psw, $email, $birthdate, $localite){
      if (mysqli_connect_errno()) {
        printf("Échec de la connexion : %s\n", mysqli_connect_error());
        exit();
      }

      $doesUserExist;
      $doesUserExist = $this->userExist_mysqli($username);
      if($doesUserExist){
        return 2; //USER ALREADY EXIST
      }

      //mysqli
      //STR_TO_DATE("21.04.2001", "%d.%m.%Y")
      $req = "INSERT INTO user (username,password,email,dateNaissance,localite) VALUES (?, ?, ?, STR_TO_DATE(?,'%d/%m/%Y'), ?)";
      if($stmt = $this->get_db()->prepare($req)){
        $psw = md5($psw); //ENCRYPT PASSWORD TO MD5

        $stmt->bind_param("sssss", $username, $psw, $email, $birthdate, $localite);
        $stmt->execute();
        return 1;
      }else{
        return 0;
      }
  }

    #CHECK IF USER ALREADY EXIST
  // function userExist_pdo($username){
  //   try {
  //     $stmt = $this->get_db()->prepare('SELECT COUNT(*) FROM user WHERE username = ?');
  //   } catch (Exception $e) {
  //     echo "<span class='conn_failed'>[PDO] STMT PREPARE failed : " . $e->getMessage() . "</span><br><br>";
  //   }
  //
  //   $stmt->bindParam(1, $username, PDO::PARAM_STR, 16);
  //
  //   try {
  //     $stmt->execute();
  //   } catch (Exception $e) {
  //       echo "<span class='conn_failed'>[PDO] STMT PREPARE failed : " . $e->getMessage() . "</span><br><br>";
  //   }
  //
  //   $result = $stmt->fetchColumn();
  //   return $result;
  // }

  function userExist_mysqli($username){
    try {
      $stmt = $this->get_db()->prepare('SELECT * FROM user WHERE username = ?');
    } catch (Exception $e) {
      return 0; //ERROR
    }

    $stmt->bind_param('s', $username);

    try {
      $stmt->execute();
      $stmt->store_result();
    } catch (Exception $e) {
      return 0; //ERROR
    }

    $result = $stmt->num_rows;
    return $result > 0;
  }

  function login($username, $psw){
    $userResult = $this->getUserByUsername($username);
    $userResult_password = $userResult['password'];

    if(md5($psw) === $userResult_password){
      return 1; //OK, LOGIN
    }else{
      return 0; //CREDENTIALS ARE NOT VALID
    }
  }

  function getUserByUsername($username){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      if($this->get_db() != null){
        if(isset($username)){
          try {
            $stmt = $this->get_db()->prepare('SELECT * FROM user WHERE username = ?');
          } catch (Exception $e) {
            return 2;//ERROR
          }

          $stmt->bind_param('s', $username);

          try {
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
          } catch (Exception $e) {
            return 2;//ERROR
          }

          return $user;
        }else{
          return 2; //ERROR
        }//isset username
      }else{
        return 2; //ERROR
      } //ISSET DB
    }else{
      return 2;//ERROR
    }//SERVER SET
  }//FUNCTION

  function areCredentialsSetAndLogin(){
    if(isset($_POST['user_username_login']) && isset($_POST['user_password_login'])){
      //login
      $credentialsState = $this->login($_POST['user_username_login'], $_POST['user_password_login']);
      //WILL RETURN 0 IF CREDENTIALS ERROR, 1 IF USER CAN LOGIN, 2 IF ERROR

      //check if username is empty, if yes then display message and set userLoginState to 2 for ERROR CODE
      if(empty($_POST['user_username_login'])){
        return 2; //ERROR
      }
      //check if password is empty, if yes then display message and set userLoginState to 2 for ERROR CODE
      if(empty($_POST['user_password_login'])){
        return 2; // ERROR
      }
    }else{
      return 2; //ERROR
    } //isset username password IF STATEMENT

    return $credentialsState;
  }

  //-2 : SERVER REQUEST METHOD IS NOT POST
  //-1 : ERROR USER DOESNT EXISTS
  //0 : ERROR USERNAME IS UNSEST
  //1 : Success
  //2 : ERROR SYSTEM EXCEPTION
  function removeUserByUsername($username){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      if(isset($_POST['user_username'])){
        if($this->userExist_mysqli($username)){
          try {
            $req = "DELETE FROM user WHERE username = ?";
            $stmt = $this->get_db()->prepare($req);
          } catch (Exception $e) {
            return 2; //ERROR SYSTEM
          }

          $stmt->bind_param('s', $username);

          try {
            $stmt->execute();
          } catch (Exception $e) {
            return 2; //ERROR SYSTEM
          }

          return 1; //SUCCESS

        }else{
          return -1; //ERROR USER DOESNT EXIST
        }
      }else{
        return 0; //ERROR USERNAME POST IS UNEST
      }
    }else{
      return -2; //METHOD REQUEST IS NOT POST
    }
  }

  function updateUsername(){
    //-3 : SERVER REQUEST METHOD IS NOT POST
    //-2 : ERROR USER DOESNT EXISTS
    //-1 : ERROR NONE OF THE REQUIRED FIELDS ARE SET OR FILLED
    //0 : ERROR USERNAME IS UNSEST
    //1 : Success
    //2 : ERROR SYSTEM EXCEPTION

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      if(isset($_POST['user_baseUsername']) && !empty($_POST['user_baseUsername'])){
        if($this->userExist_mysqli($_POST['user_baseUsername'])){
          $req = ""; //UPDATE `user` SET `username`=[value-1],`password`=[value-2],`email`=[value-3],`dateNaissance`=[value-4],`Localite`=[value-5] WHERE 1
          $bind_param_type = '';
          $bind_param_type_array = array();
          $bind_param_vars = array();

          $new_username = false;
          $new_password = false;
          $new_email = false;
          $new_birthdate = false;
          $new_locality = false;

          if(isset($_POST['user_newUsername']) && !empty($_POST['user_newUsername'])){
            //NEW USERNAME IS SET AND NOT EMPTY
            //SO WE BELIEVE USER WANTS TO CHANGE ITS USERNAME
            $req = "UPDATE user SET username=?";
            $new_username = true;
            $bind_param_type .= 's';
            array_push($bind_param_vars, $_POST['user_newUsername']);
          }
          if(isset($_POST['user_newPassword']) && !empty($_POST['user_newPassword'])){
            //NEW PASSWORD IS SET AND NOT EMPTY
            //SO WE BELIEVE USER WANTS TO CHANGE ITS PASSWORD
            if($new_username){ //DOES USER ALSO CHANGE ITS USERNAME ?
              $req .= ", password=?"; //THEN ADD PASSWORD TO THE REQUEST
            }else{ //DOES USER ONLY CHANGE PASSWORD ?
              $req = "UPDATE user SET password=?"; //SET THE REQUEST
            }
            $new_password = true;
            $bind_param_type .= 's';
            $newpass = md5($_POST['user_newPassword']);
            array_push($bind_param_vars, $newpass);
          }
          if(isset($_POST['user_newEmail']) && !empty($_POST['user_newEmail'])){
            //NEW EMAIL IS SET AND NOT EMPTY
            //SO WE BELIEVE USER WANTS TO CHANGE ITS EMAIL
            if($new_username || $new_password){ //DOES USER ALSO CHANGED USERNAME OR PASSWORD ?
              $req .= ", email=?"; //ONE OF THE TWO FIELDS WISHES TO BE CHANGED SO WE JUST ADD EMAIL TO THE REQUEST
            }else{ //USER ONLY CHANGE THE EMAIL
              $req = "UPDATE user SET email=?"; //SET THE REQUEST TO EMAIL
            }
            $new_email = true;
            $bind_param_type .= 's';
            array_push($bind_param_vars, $_POST['user_newEmail']);
          }
          if(isset($_POST['user_newBirthdate']) && !empty($_POST['user_newBirthdate'])){
            //NEW BIRTHDATE IS SET AND NOT EMPTY
            //SO WE BELIEVE USER WANTS TO CHANGE ITS BIRTHDATE
            //STR_TO_DATE("21.04.2001", "%d.%m.%Y")
            if($new_username || $new_password || $new_email){ //DOES USER ALSO CHANGED USERNAME, PASSWORD OR EMAIL ?
              $req .= ", dateNaissance=STR_TO_DATE(?, '%d/%m/%Y')"; //ONE OF THE THREE FIELDS WISHES TO BE CHANGED SO WE JUST ADD BIRTHDATE TO THE REQUEST
            }else{ //USER ONLY CHANGE BIRTHDATE
              $req = "UPDATE user SET dateNaissance=STR_TO_DATE(?, '%d/%m/%Y')"; //SET THE REQUEST TO BIRTHDATE
            }
            $new_birthdate = true;
            $bind_param_type .= 's';

            array_push($bind_param_vars, $_POST['user_newBirthdate']);
          }
          if(isset($_POST['user_newLocality']) && !empty($_POST['user_newLocality'])){
            //NEW LOCALITY IS SET AND NOT EMPTY
            //SO WE BELIEVE USER WANTS TO CHANGE ITS LOCALITY
            if($new_username || $new_password || $new_email || $new_birthdate){ //DOES USER ALSO CHANGED USERNAME, PASSWORD, EMAIL, OR BIRTHDATE ?
              $req .= ", Localite=?"; //ONE OF THE FOUR FIELDS WISHES TO BE CHANGED SO WE JUST ADD LOCALITY TO THE REQUEST
            }else{ //USER ONLY CHANGE LOCALITY
              $req = "UPDATE user SET Localite=?"; //SET THE REQUEST TO LOCALIY
            }
            $new_locality = true;
            $bind_param_type .= 's';
            array_push($bind_param_vars, $_POST['user_newLocality']);
          }

          //DOES USER WISH TO CHANGE ANY OF ITS INFORMATIONS ?
          if($new_username == true || $new_password == true || $new_email == true || $new_birthdate == true || $new_locality == true){
            //YES
            $req .= " WHERE username=?"; //ADD WHERE CONDITIONS AT THE END OF THE REQUEST
            $bind_param_type .= 's';
            $bind_param_type_array = array($bind_param_type);
            array_push($bind_param_vars, $_POST['user_baseUsername']); //WHERE username=$_POST['user_baseUsername']
          }else{
            //NO
            //echo "none of the field are set or filled";
            return -1; //RETURN USER DID NOT WISHES TO CHANGE ANY OF ITS INFORMATION
          }

          try {
            //echo $req;
            $stmt = $this->get_db()->prepare($req);
          } catch (Exception $e) {
            return 2;
          }

          //CREATE AN ARRAY FROM MERGING TWO OTHER ARRAY
          $params = array_merge($bind_param_type_array, $bind_param_vars);

          //GET THE REFERENCES OF VARIABLES INTO ANOTHER ARRAY
          $tmp_array = array();
          foreach ($params as $key => $value) {
            $tmp_array[$key] = &$params[$key];
          }

          try {
            //CALL BIND_PARAM METHOD ON $STMT WITH CALL_USER_FUNC_ARRAY
            //LAST ARGUMENT $tmp_array MUST BE PASSED BY REFERENCE
            //THIS IS WHY WE NEED TO CREATE THIS ONE BEFORE
            call_user_func_array(array($stmt, 'bind_param'), $tmp_array);
          } catch (Exception $e) {
            echo "error call user func array";
            return 2; //SYSTEM ERROR
          }

          try {
            $stmt->execute();
          } catch (Exception $e) {
            return 2; //ERROR SYSTEM
          }

          return 1; //SUCCESS
        }else{
          return -2; //USER DOESNT EXIST
        }
      }else{
        return 0; //user_baseUsername IS UNSET, RETURN 0
      }
    }else{
      return -3; //METHOD REQUEST IS NOT POST
    }
  } //method bracket
}//class bracket
 ?>
