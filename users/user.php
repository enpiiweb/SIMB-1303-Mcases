<?php

require_once("../includes/includes.php");

$smarty = new SiteTemplate;

$action = isset($_GET['action']) ? $_GET['action'] : false;
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : false;

AssignCategories($smarty);
Authorise($smarty,"index.php","Users");


switch($action)
{
/* case 'add':
          $customer = new Customer;
          $smarty->assign('customerform_customer', $customer);
          $smarty->assign('main_displayfile', 'customerform_u.tpl');
          break;*/
  case 'edit':
          $userid= $_SESSION['auth']['user']->id;        
          
          $customers = ClassFactory::ObjectArray('Customer',true, "where user='$userid'");
          if ( is_array($customers) )
          {         $customer = current($customers);       }

          //print_r($customers);die();
          
          $smarty->assign('customerform_customer', $customer);
          $smarty->assign('main_displayfile', 'customerform_e.tpl');
          break;
  case 'save':
          
          $customer = new Customer($_POST);

          $userid= $_SESSION['auth']['user']->id;
          $customers = ClassFactory::ObjectArray('Customer',true, "where user='$userid'");
          if ( is_array($customers) )
          {         $oldcustomer = current($customers);  }

          $customer->password = $oldcustomer->password;
          $customer->email = $oldcustomer->email;
          $customer->user = $oldcustomer->user;
          $customer->status = $oldcustomer->status;
          
/*      if ($_POST['password'] != $_POST['confirmpassword'])
          {
                   $errors['confirmpassword'] = "Passwords do not match";
          }*/

          $errors = $customer->Errors();

          if ($errors)
          {

                   $smarty->assign('customerform_errors', $errors);
                   $smarty->assign('customerform_customer', $customer);
                   $smarty->assign('main_displayfile', 'customerform_e.tpl');
                   break;
          }

          if ($customer instanceof Customer)
          {         
                    $result = $customer->Save();
                    /*
                    $user = ClassFactory::ObjectNew('user',$customer->user);
                    $user->email = $customer->email;
                    $user->login = $customer->email;
                    */
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


}


$smarty->display($fileroot . 'templates/main.tpl');
//$smarty->display('main.tpl');
?>
