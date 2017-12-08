<?php
class Users extends CI_Model {
  function getUserFullName($id) {
    $user = $this->db->get_where("users", array("id"=>$id))->result_array()[0];
    return $user["first_name"] . " " . $user["last_name"] . " " . $user["middle_name"];
  }
}
?>
