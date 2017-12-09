<?php
class Stocks extends CI_Model {
  function getStocks() {
    $this->db->order_by("name", "ASC");
    return $this->db->get("stock")->result_array();
  }
  function getStocksByCategory($cid) {
    $this->db->order_by("name", "ASC");
    return $this->db->get_where("stock", array("category" => $cid))->result_array();
  }
  function getStock($id) {
    return $this->db->get_where("stock", array("id"=>$id))->result_array()[0];
  }
  function getStockImageName($id) {
    return $this->getStock($id)["image"];
  }
  function editStock($id, $stock) {
    $this->db->where("id", $id);
    return $this->db->update("stock", $stock);
  }
  function addStock($name, $image, $quantity, $category, $unitPrice) {
    return $this->db->insert("stock", array("name"=>$name, "quantity"=>$quantity,
    "category"=>$category, "image"=>$image, "unit_price"=>$unitPrice));
  }
  function calculateTotalPrice($itemId, $quantity) {
    $unitPrice = $this->db->get_where("stocks", array("id"=>$itemId))->result_array()[0]["unit_price"];
    return $unitPrice * $quantity;
  }
  function searchStocks($keyWord) {
    $this->db->like("name", $keyWord);
    return $this->db->get("stock")->result_array();
  }
}
?>
