<?php
/*                          *
*      LUDUS  ACADEMIE      *
*    OWNER : DRUCKES Lucas  *
*      WEACT     2020       *
*   PHP FILE FOR  INDEX.PHP *
*          YAPADKASS        *
*                           */
class dbconnect{

  #MEMBERS
  private $m_conn;

  #CONSTRUCTORS
  function __construct(){
  }

  #ACCESSORS
  function get_conn(){
    return $this->m_conn;
  }

  function set_conn($conn){
    $this->m_conn = $conn;
  }

  #LOGIC
  function connect_pdo(){
    require("constants.php");

    try{
      //connect with mysqli
      //return mysqli_connect_errno
      //for more informations look the following link : https://www.php.net/manual/fr/mysqli.construct.php
      //$mysqli = new mysqli(DBNAME, USERNAME, PASSWORD, DATABASE);

      //connect with PDO

      //VIA MEMBER VARIABLE
      $this->m_conn = new PDO("mysql:host=".DBHOST.";dbname=".DATABASE, USERNAME, PASSWORD);
      $this->m_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      //VIA VARIABLE ACCESSORS
      //$this->set_conn(new PDO("mysql:host=".DBHOST.";dbname=".DATABASE, USERNAME, PASSWORD));
      //$this->get_conn()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      //echo "<script> console.log('Connected successfully'); </script>";
      //echo "<span class='conn_success'>[PDO] Connection Success</span><br><br>";
    }//try
    catch(PDOException $e)
    {
      echo "<script> console.log('[PDO] Connection failed'); </script>";
      //echo "<span class='conn_failed'>[PDO] Connection failed : " . $e->getMessage() . "</span><br><br>";
    }//catch

    //VIA MEMBER VARIABLE
    return $this->m_conn;

    //VIA VARIABLE ACCESSOR
    //return $this->get_conn();
  }//function

  function connect_mysqli(){
    require("constants.php");
    try{
      /*define('DBHOST', 'localhost');
      define('USERNAME','root');
      define('PASSWORD','');
      define('DATABASE','androidmysql');*/

      $this->m_conn = new mysqli(DBHOST, USERNAME, PASSWORD, DATABASE);
      if(mysqli_connect_errno()){
          //echo "<span class='conn_failed'>[MSQLI] Connection failed : " . mysqli_connect_err() . "</span><br><br>";
          $this->m_conn = null;
      }else{
        //echo "<span class='conn_success'>[MSQLI] Connection Success</span><br><br>";
      } //else
    } //try
    catch(Exception $e){
      //echo "<span class='conn_failed'>[MSQLI EXCEPTION] Connection failed : " . $e->getMessage() . "</span><br><br>";
      $this->m_conn = null;
    }//catch
    return $this->m_conn;
  } //function

}//class
?>
