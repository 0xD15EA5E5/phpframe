<?php
//parse user configuration file
$config = parse_ini_file('user_config.ini');
//function to check if user exists
function check_user($username, $email){
  global $config;
  //sanitise inputs
  $username = db_escape_string($username);
  $email = db_escape_string($email);
  //query database to see if username or email exists
  $row = db_query('SELECT * FROM '.$config['dbname'].' WHERE username = '.$username);
  $res = db_fetch_array($row);
  var_dump($res);
  if($row){
    return true;
  }
  $row = db_query('SELECT * FROM '.$config['dbname'].' WHERE email = '.$email);
  if($row){
    return true;
  }
  else {
    return false;
  }
}
//register user in database
function register_new_user($username, $password, $email, $fname, $sname){
  global $config;
  //sanitise inputs and hash users password
  $username = db_escape_string($username);
  $password = db_escape_string($password);
  $password = md5($password);
  $email = db_escape_string($email);
  $fname = db_escape_string($fname);
  $sname = db_escape_string($sname);

//  db_query("INSERT INTO '.$config['dbname'].'" (username, password_hash, email, fname, sname) VALUES ($username, $password, $email, $fname, $sname));
}

 ?>
