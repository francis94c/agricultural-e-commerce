<?php
class Users extends CI_Model {
  function getUserFullName($id) {
    $user = $this->db->get_where("users", array("id"=>$id))->result_array()[0];
    return $user["first_name"] . " " . $user["last_name"] . " " . $user["middle_name"];
  }
  function getUserName($id) {
    return $this->db->get_where("users", array("id"=>$id))->result_array()[0]["username"];
  }
  function createUser($user) {
    return $this->db->insert("users", $user);
  }
  function getUserShippingAddress($id) {
    return $this->db->get_where("users", array("id"=>$id))->result_array()[0]["shipping_address"];
  }
}
?>
