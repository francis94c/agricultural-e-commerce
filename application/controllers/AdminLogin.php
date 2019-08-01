<?php
class AdminLogin extends CI_Controller {
  function index() {
    $data = array("message"=>"");
    if (func_num_args() == 1) {
      $data["message"] = func_get_arg(0);
    }
    $this->load->view("admin_login", $data);
  }
  function process() {
    $this->load->model('authenticator');
    $result = $this->authenticator->authenticateAdmin();
    if(!$result) {
      $message = '<font color=red>Invalid Username or Password.</font><br />';
      $this->index($message);
    } else {
      redirect('Admin');
    }
  }
}
?>
