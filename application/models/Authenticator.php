<?php
class Authenticator extends CI_Model {

  function authenticate() {
    // grab user input
    $username = $this->security->xss_clean($this->input->post('username'));
    $password = $this->security->xss_clean($this->input->post('password'));
    // Prep the user link.
    $this->db->or_where(array("username"=>$username, "email"=>$username));
    // Run the query
    $query = $this->db->get('users');
    if($query->num_rows() == 1) {
      $row = $query->row();
      $hash = $row->password;
      if (password_verify($password, $hash) && $row->admin == 1) {
        $data = array("validated"=>true, "admin"=>true);
        $this->session->set_userdata($data);
        return true;
      }
    }
    return false;
  }

}
?>
