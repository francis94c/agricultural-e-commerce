<?php
/*
status codes
0-unprocessed
1-paid
2-processed
 */
class Orders extends CI_Model {
  /**
   * [getOrders description]
   * @return [type] [description]
   */
  function getOrders() {
    return $this->db->get("orders")->result_array();
  }
  /**
   * [markOrderAsProcessed description]
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  function markOrderAsProcessed($id) {
    $this->db->where("id", $id);
    return $this->db->update("orders", array("status"=>2));
  }
  /**
   * [markOrderAsPaid description]
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  function markOrderAsPaid($id) {
    $this->db->where("id", $id);
    return $this->db->update("orders", array("status"=>1));
  }
  /**
   * [markOrderAsUnProcessed description]
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  function markOrderAsUnProcessed($id) {
    $this->db->where("id", $id);
    return $this->db->update("orders", array("status"=>0));
  }
  /**
   * [createOrder description]
   * @param  [type] $uid   [description]
   * @param  [type] $items [description]
   * @return [type]        [description]
   */
  function createOrder($uid, $items) {
    $this->load->helper("string");
    $orderFileName = random_string('alnum', 10);
    $totalPrice = 0;
    $this->load->model("stocks");
    for ($x = 0; $x < count($items); $x++) {
      $totalPrice += $this->stocks->calculateTotalPrice($items[$x]["id"], $items[$x]["quantity"]);
    }
    $data = array();
    $data["user_id"] = $uid;
    $data["items"] = $orderFileName;
    $data["total_price"] = $totalPrice;
    if ($this->db->insert("orders", $data)) {
      $this->load->helper("file");
      write_file(FCPATH . "orders/" . $orderFileName, json_encode($items));
      return true;
    }
    return false;
  }
  /**
   * [getOrder description]
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  function getOrder($id) {
    return $this->db->get_where("orders", array("id"=>$id))->result_array()[0];
  }
  /**
   * [deleteOrder description]
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  function deleteOrder($id) {
    $this->db->where("id", $id);
    return $this->db->delete("orders");
  }
}
?>
