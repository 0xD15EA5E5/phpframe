<?php
//parse user configuration file
$config = parse_ini_file('user_config.ini');
//function to encrypt passwords
function hash_pass($password){
  global $config;
  if($config['encryption'] == 'md5'){
    $password = md5($password);
  }
  else if($config['encryption'] == 'sha1'){
    $password = sha1($password);
  }
  return $password;
}
//function to check if user exists
function check_user($username, $email){
  global $config;
  //sanitise inputs
  $username = db_escape_string($username);
  $email = db_escape_string($email);
  //query database to see if username or email exists
  $row = db_query('SELECT * FROM '.$config['dbname'].' WHERE username = '.$username);
  $res = db_fetch_array($row);
  //var_dump($res);
  if($res){
    return true;
  }
  $row = db_query('SELECT * FROM '.$config['dbname'].' WHERE email = '.$email);
  $res = db_fetch_array($row);
  if($res){
    return true;
  }
  else {
    return false;
  }
}
//register user in database
function register_new_user($username, $password, $email, $fname, $sname){
  global $config;

  //check if username or email is already in the database
  $validate = check_user($username, $email);
  //sanitise inputs
  $username = db_escape_string($username);
  $password = db_escape_string($password);
  //hash password with selected algorithm
  $password = hash_pass($password);
  $email = db_escape_string($email);
  $fname = db_escape_string($fname);
  $sname = db_escape_string($sname);
  //if user is present in the database returns false if not adds user to database and returns true
  if($validate === false){
    $sql = 'INSERT INTO '.$config['dbname'].' (username, password, email, fname, sname) VALUES ('.$username.', \''.$password.'\', '.$email.', '.$fname.', '.$sname.')';
    db_query($sql);
    return true;
  }
  else {
    return false;
  }
}
//check entered password against a user's password in the database
function check_pass($username, $password){
  global $config;
  //check if user exists
  $validate = check_user($username, '');
  if($validate === true){
    //sanitise inputs and convert password to md5
    $username = db_escape_string($username);
    $password = db_escape_string($password);
    $pass = hash_pass($password);
    $sql = 'SELECT `password` FROM '.$config['dbname'].' WHERE `username` = '.$username;
    $res = db_query($sql);
    $row = db_fetch_array($res);
    if($row['password'] === $pass){
      return true;
    }
    else{
      return false;
    }
  }
  else {
    return false;
  }
}
// login function
function user_login($username, $password){
  //sanitise inputs and md5 password
  $username = db_escape_string($username);
  $password = db_escape_string($password);
  $password = hash_pass($password);
  $sql = 'SELECT * FROM '.$config['dbname'].' WHERE username = '.$username.' AND password = '.$password;
  return $sql;
}

?>
