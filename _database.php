<?php
//database connection function
function db_connect() {
  //define connection as static so that it only tries to connect once
  static $connection;
  //if there is no connection try to connect
  if(!isset($connection)){
    //load configurarion file as an array, use the actual location of the config file should be outside your document root
    $config = parse_ini_file('config.ini');
    //try to connect to the database
    $connection = mysqli_connect('Localhost',$config['username'],$config['password'],$config['dbname']);
  }
  //check to see if connection was successful if not handle the error
  if($connection === false) {
    //work out what to do if connection fails im thinking email me :)
    return mysqli_connect_error();
  }
  return $connection;
}
//database query function
function db_query($query){
  //connect to the database
  $connection = db_connect();
  //query the database
  $result = mysqli_query($connection, $query);
  //return the result
  return $result;
}
//database fetch array function
function db_fetch_array($result){
  //setup $rows array
  $rows = array();
  //iterate through the result and add rows to array
  while($row = mysqli_fetch_assoc($result)){
    $rows[] = $row;
  }
  //return the array
  return $rows;
}
//database selct function
function db_select($query){
  //setup $rows array
  $rows = array();
  $result = db_query($query);
  //if query fails then return false
  if($result === false){
    return false;
  }
  //if query is successful then iterate through and place rows in array
  while($row = mysqli_fetch_assoc($result)){
    $rows[] = $row;
  }
}
//query escape string function
function db_escape_string($value){
  //connect to database
  $connection = db_connect();
  return "'".mysqli_real_escape_string($connection,$value)."'";
}
//database error function
function db_error() {
  //connect to the database
  $connection = db_connect();
  //return the error
  return mysqli_error($connection);
}


?>
