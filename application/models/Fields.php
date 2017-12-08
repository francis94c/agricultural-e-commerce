<?php
class Fields extends CI_Model {
  function getCategories() {
    $this->db->order_by("name", "ASC");
    $query = $this->db->get("categories");
    $array = $query->result_array();
    array_splice($array, 0, 0, array(array("id" => 0, "name" => "All")));
    return $array;
  }
}
?>
