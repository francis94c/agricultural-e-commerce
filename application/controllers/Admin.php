<?php
class Admin extends CI_Controller {
  function __construct() {
    parent::__construct();
    if (!$this->isValidated()) {
      redirect('AdminLogin');
    }
  }
  function index() {
    $data = array("title"=>"Stocks");
    $this->load->view("admin_header", $data);
  }
  function isValidated() {
    return $this->session->userdata('validated');
  }
  function logout() {
    $this->session->sess_destroy();
    redirect('AdminLogin');
  }
}
?>
