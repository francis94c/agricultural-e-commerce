<?php
class Login extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->helper("form");
  }
  function index() {
    $this->load->helper("form");
    $data = array("message"=>"");
    $this->load->view("login", $data);
  }
  function process() {
    $this->load->model('authenticator');
    $result = $this->authenticator->authenticate();
    if(!$result) {
      $message = '<font color=red>Invalid Username or Password.</font><br />';
      $this->index($message);
    } else {
      if ($this->session->userdata("checking_out")) {
        redirect("home/viewCart");
      } else {
        redirect("home");
      }
    }
  }
  function signUp() {
    $this->load->helper("form");
    $data = array();
    $data["username"] = "";
    $data["email"] = "";
    $data["message"] = "";
    $data["phone_number"] = "";
    $data["first_name"] = "";
    $data["last_name"] = "";
    $data["middle_name"] = "";
    $this->load->view("signup", $data);
  }
  function processSignUp() {
    $email = $this->security->xss_clean($this->input->post('email'));
    $password = $this->security->xss_clean($this->input->post('password'));
    $confirmPassword = $this->security->xss_clean($this->input->post('confirm_password'));
    $username = $this->security->xss_clean($this->input->post('username'));
    $phoneNumber = $this->security->xss_clean($this->input->post('phone_number'));
    $firstName = $this->security->xss_clean($this->input->post('first_name'));
    $lastName = $this->security->xss_clean($this->input->post('last_name'));
    $middleName = $this->security->xss_clean($this->input->post('middle_name'));
    if ($password == $confirmPassword) {
      $data = array();
      $data["username"] = $username;
      $data["email"] = $email;
      $data["password"] = password_hash($password, PASSWORD_DEFAULT);
      $data["phone_number"] = $phoneNumber;
      $data["first_name"] = $firstName;
      $data["last_name"] = $lastName;
      $data["middle_name"] = $middleName;
      $this->load->model("users");
      if ($this->users->createUser($data)) {
        if ($this->session->userdata("checking_out")) {
          redirect("home/viewCart");
        } else {
          redirect("home");
        }
      } else {
        $data["message"] = "<font color=\"red\">Missing Fields</font>";
        $this->load->view("signup", $data);
      }
    } else {
      $data["username"] = $username;
      $data["email"] = $email;
      $data["password"] = password_hash($password, PASSWORD_DEFAULT);
      $data["phone_number"] = $phoneNumber;
      $data["first_name"] = $firstName;
      $data["last_name"] = $lastName;
      $data["middle_name"] = $middleName;
      $data["message"] = "<font color=\"red\">Password MisMatch</font>";
      $this->load->view("signup", $data);
    }
  }
}
?>
