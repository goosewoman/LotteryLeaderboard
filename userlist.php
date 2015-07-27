<?php
  require_once "Database.php";
  header( "content-type: text/plain");
  $db = Database::getInstance();
  $users = $db->getUsers();
  $result = array();
  foreach($users as $user)
  {
    $result[count($result)] = $user['username'];
  }
echo(json_encode($result));