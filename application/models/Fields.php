<?php
class Fields extends CI_Model {
  function getCategories() {
    $this->db->order_by("name", "ASC");
    $query = $this->db->get("categories");
    return $query->result_array();
  }
}
?>
