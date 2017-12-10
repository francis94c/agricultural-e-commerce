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
   * [statusToString description]
   * @param  [type] $code [description]
   * @return [type]       [description]
   */
  function statusToString($code) {
    switch ($code) {
      case 0:
        return "<font color=\"gray\">Unprocessed</font>";
      case 1:
        return "<font color=\"#789669\">Paid</font>";
      case 2:
        return "<font color=\"green\">Processed</font>";
    }
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
  function createOrder($paid) {
    $this->load->helper("string");
    $orderFileName = random_string('alnum', 10);
    $totalPrice = 0;
    $items = $this->session->userdata("cart");
    $this->load->model("stocks");
    for ($x = 0; $x < count($items); $x++) {
      $totalPrice += $this->stocks->calculateTotalPrice($items[$x]["id"], $items[$x]["quantity"]);
    }
    $data = array();
    $data["user_id"] = $this->session->userdata("id");
    $data["items"] = $orderFileName;
    $data["total_price"] = $totalPrice;
    $data["shipping_address"] = $this->session->userdata("shipping");
    $data["additional_message"] = $this->session->userdata("message");
    $data["status"] = $paid == true ? 1 : 0;
    if ($this->db->insert("orders", $data)) {
      $this->load->helper("file");
      write_file(FCPATH . "orders/" . $orderFileName, json_encode($items));
      $this->db->where("id", $this->session->userdata("id"));
      $this->db->update("users", array("shipping_address"=>$this->session->userdata("shipping")));
      $this->session->unset_userdata("cart");
      $this->session->unset_userdata("shipping");
      $this->session->unset_userdata("message");
      $this->session->unset_userdata("checking_out");
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
  function getTotalItemCountById($id) {
    $fileName = $this->db->get_where("orders", array("id"=>$id))->result_array()[0]["items"];
    $this->load->helper("file");
    $orders = json_decode(read_file(FCPATH . "orders/" . $fileName), true);
    $count = 0;
    for ($x = 0; $x < count($orders); $x++) {
      $count += $orders[$x]["quantity"];
    }
    return $count;
  }
  function getOrderObject($fileName) {
    $this->load->helper("file");
    return json_decode(read_file(FCPATH . "orders/" . $fileName), true);
  }
  function getTotalItemCountByFileName($fileName) {
    $this->load->helper("file");
    $orders = json_decode(read_file(FCPATH . "orders/" . $fileName), true);
    $count = 0;
    for ($x = 0; $x < count($orders); $x++) {
      $count += $orders[$x]["quantity"];
    }
    return $count;
  }
  function getCustomerFullName($id) {
    $uid = $this->db->get_where("orders", array("id"=>$id))->result_array()[0]["user_id"];
    $user = $this->db->get_where("users", array("id"=>$uid))->result_array()[0];
    return $user["first_name"] . " " . $user["last_name"] . " " . $user["middle_name"];
  }
}
?>
