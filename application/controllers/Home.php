<?php
class Home extends CI_Controller {
  function index() {
    $this->load->model("fields");
    $data = array("menu" => $this->fields->getCategories());
    $this->load->view("header", $data);
    $this->load->view("footer");
  }
}
?>
