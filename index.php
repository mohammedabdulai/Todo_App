<?php
/*Controller*/
session_start();
require('db_connection.php');
require('db.php');

$action = filter_input(INPUT_POST, "action");

if($action == NULL && !isset($_SESSION['name'], $_SESSION['user_id'], $_SESSION['isLogged']))
{
  $action = "show_login_page";
}

elseif ($action == NULL && isset($_SESSION['name'], $_SESSION['user_id'], $_SESSION['isLogged'])) {
  $result1 = getTodoItems($_SESSION['user_id'], 'pending');
  $result2 = getTodoItems($_SESSION['user_id'], 'done');
  include 'list.php';
}

if($action == "show_login_page")
{
  include('login.php');
}

else if($action == 'test_user')
{
  $email = filter_input(INPUT_POST, 'reg_email');
  $password = filter_input(INPUT_POST, 'reg_password');
  $valid_user = isUserValid($email,$password);
  if($valid_user === true)
  {
    $result = getTodoItems($_SESSION['user_id']);
    include 'list.php';
  } elseif ($valid_user === 'Email Exists'){
    echo '<h2>Email Exists, Incorrect password</h2>';
  } elseif ($valid_user === 'Email Does Not Exist'){
    echo '<h2>Account Does Not Exist, ', '<a href="register.php">Register</a></h2>';
  }
}

else if ($action=='registrar') {
    $first_name = filter_input(INPUT_POST, 'reg_first_name');
    $last_name = filter_input(INPUT_POST, 'reg_last_name');
    $phone = filter_input(INPUT_POST, 'reg_phone');
    $birthday = filter_input(INPUT_POST, 'reg_birthday');
    $gender = filter_input(INPUT_POST, 'reg_gender');
    $email = filter_input(INPUT_POST, 'reg_email');
    $password = filter_input(INPUT_POST, 'reg_password');

    $user_exists = createUser($first_name, $last_name, $phone, $birthday, $gender, $email, $password);

      if ($user_exists == true) {
        include ('user_exists.php');
      } else {
        header("Location: index.php");
       }
   /*}*/
}

else if ($action == 'add') {
  if (isset($_POST['title']) and $_POST['title'] != '') {
    addTodoItem($_SESSION['user_id'], $_POST['title']);
  }
  $result = getTodoItems($_SESSION['user_id']);
  include ('list.php');
}
else if ($action == 'edit') {
  /*Code to update the already set todo item's properties*/
}

else if($action == 'delete'){
  if(isset($_POST['item_id'])) {
    $selected = $_POST['item_id'];
    deleteTodoItem($_SESSION['user_id'], $selected);
  }
  $result = getTodoItems($_SESSION['user_id']);
  include ('list.php');
}

?>
