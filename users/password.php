<?php

 

require_once("../includes/includes.php");

 

$smarty = new SiteTemplate;

 

$action = isset($_GET['action']) ? $_GET['action'] : false;

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : false;

 

AssignCategories($smarty);

Authorise($smarty,"index.php","Users");

 

switch($action)

{

/*  case 'edit':

          $userid= $_SESSION['auth']['user']->id;

          $customers = ClassFactory::ObjectArray('customer',true, "where id='$userid'");

          if ( is_array($customers) )

          {         $customer = current($customers);       }

 

         

          $smarty->assign('customerform_customer', $customer);

          $smarty->assign('main_displayfile', 'customerform_u.tpl');

          break;*/

  case 'save':

          $userid= $_SESSION['auth']['user']->id;

 

          $users = ClassFactory::ObjectArray('user',true, "where id='$userid'");

          if ( is_array($users) )

          {         $user = current($users);            }

 

         // print_r($user);

 

          if (md5($_POST['oldpassword']) != $user->password)         

          {

                   $errors['oldpassword'] = "Old password does not match current password";

          }

         

         

          if ( strlen($_POST['password']) < 1  )

          {

                   $errors['password'] = "Must supply a password";

          }

 

          if (isset($errors) )

          {

 

                   $smarty->assign('password_errors', $errors);

                   $smarty->assign('main_displayfile', 'password.tpl');

                   break;

          }

 

          $user->password = $_POST['password'];

 

          if ($user instanceof user)

          {        

                   $user->save_password = true;

                    $result = $user->Save();

                   

                   $smarty->assign("main_displayfile","displaymessage.tpl");

                   $smarty->assign("displaymessage_return","index.php");

                   $smarty->assign("displaymessage_message","Update Complete");

          }

          else

          {

                   $smarty->assign("main_displayfile","displaymessage.tpl");

                   $smarty->assign("displaymessage_return","index.php");

                   $smarty->assign("displaymessage_message","A problem has occurred");

          }

          break;

  default:

  //    $customer = ClassFactory::ObjectArray('Customer');

          $smarty->assign('main_displayfile', 'password.tpl');

          break;

 

}

 

 

$smarty->display($fileroot . 'templates/main.tpl');

?>