<?php
class Home extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->helper("form");
  }
  function index() {
    $this->load->model("fields");
    $data = array("menu" => $this->fields->getCategories());
    $data["selected"] = 0;
    $data["title"] = "All";
    $this->load->model("stocks");
    $stocks = $this->stocks->getStocks();
    $data["items"] = count($stocks);
    $this->load->view("header", $data);
    $pages = ceil(count($stocks) / 8);
    $page = 1;
    $lastIndex = 0;
    while ($page <= $pages) {
        $this->load->view("product_grid_header");
        for($x = $lastIndex; $x < $lastIndex + 8; $x++) {
          $stock = array();
          if ($x < count($stocks)) {
            $stock["image"] = base_url("images/" . $stocks[$x]["image"] . ".jpg");
            $stock["name"] = $stocks[$x]["name"];
            $stock["price"] = $stocks[$x]["unit_price"];
            $stock["id"] = $stocks[$x]["id"];
            $stock["quantity"] = $stocks[$x]["quantity"];
            $stock["is_admin"] = false;
            $this->load->view("product_array", $stock);
          } else {
            $this->load->view("product_grid_footer");
            break;
          }
        }
        if ($page != $pages) {
          $this->load->view("product_grid_footer");
        }
        $lastIndex += 8;
        ++$page;
    }
    $this->load->view("footer");
    $navigation = $this->session->userdata("navigation") != null ?
    $this->session->userdata("navigation") : array("search"=>"", "category"=>0);
    $navigation["search"] = "";
    $navigation["category"] = 0;
    $this->session->set_userdata("navigation", $navigation);
  }
  function searchStocks() {
    $this->load->model("fields");
    $data = array("menu" => $this->fields->getCategories());
    $data["selected"] = 0;
    $this->load->model("stocks");
    $keyWord = "";
    if (func_num_args() == 1) {
      $keyWord = func_get_arg(0);
    } else {
      $keyWord = $this->security->xss_clean($this->input->post("key-word"));
    }
    $data["title"] = "Search '$keyWord'";
    $stocks = $this->stocks->searchStocks($keyWord);
    $data["items"] = count($stocks);
    $this->load->view("header", $data);
    $pages = ceil(count($stocks) / 8);
    $page = 1;
    $lastIndex = 0;
    while ($page <= $pages) {
        $this->load->view("product_grid_header");
        for($x = $lastIndex; $x < $lastIndex + 8; $x++) {
          $stock = array();
          if ($x < count($stocks)) {
            $stock["image"] = base_url("images/" . $stocks[$x]["image"] . ".jpg");
            $stock["name"] = $stocks[$x]["name"];
            $stock["price"] = $stocks[$x]["unit_price"];
            $stock["id"] = $stocks[$x]["id"];
            $stock["quantity"] = $stocks[$x]["quantity"];
            $stock["is_admin"] = false;
            $this->load->view("product_array", $stock);
          } else {
            $this->load->view("product_grid_footer");
            break;
          }
        }
        if ($page != $pages) {
          $this->load->view("product_grid_footer");
        }
        $lastIndex += 8;
        ++$page;
    }
    $this->load->view("footer");
    $navigation = $this->session->userdata("navigation") != null ?
    $this->session->userdata("navigation") : array("search"=>"", "category"=>0);
    $navigation["search"] = $keyWord;
    $this->session->set_userdata("navigation", $navigation);
  }
  function addToCart() {
    $id = $this->uri->segment(3);
    $this->load->model("stocks");
    $stock = $this->stocks->getStock($id);
    $cart = $this->session->userdata("cart") != null ? $this->session->userdata("cart") : array();
    if (!$this->hasAddedItemToCart($id, $cart)) {
      $cart[] = array("id"=>$stock["id"], "quantity"=>1, "unit_price"=>$stock["unit_price"]);
      $this->session->set_userdata("cart", $cart);
    }
    $this->session->mark_as_temp("cart", 86400);
    $navigation = $this->session->userdata("navigation") != null ?
    $this->session->userdata("navigation") : array("search"=>"", "category"=>0);
    if ($navigation["search"] != "") {
      $this->searchStocks($navigation["search"]);
    } elseif ($navigation["category"] > 0) {
      // load category
    } else {
      $this->index();
    }
  }
  private function hasAddedItemToCart($id, $cart) {
    for ($x = 0; $x < count($cart); $x++) {
      if ($id == $cart[$x]["id"]) {
        return true;
      }
    }
    return false;
  }
  function viewCart() {
    $this->load->model("fields");
    $data = array("menu" => $this->fields->getCategories());
    $data["selected"] = 0;
    $data["title"] = "My Cart";
    $this->load->view("header", $data);
    $this->load->model("stocks");
    $cart = $this->session->userdata("cart");
    $this->load->view("form_open", array("url"=>"home/checkout"));
    $this->load->view("table_start");
    $this->load->view("cart_header");
    for ($x = 0; $x < count($cart); $x++) {
      $data = array();
      $stock = $this->stocks->getStock($cart[$x]["id"]);
      $data["name"] = $stock["name"];
      $data["quantity"] = $cart[$x]["quantity"];
      $data["unit_price"] = $cart[$x]["unit_price"];
      $data["total_price"] = $cart[$x]["quantity"] * $cart[$x]["unit_price"];
      $data["id"] = $cart[$x]["id"];
      $this->load->view("cart_item", $data);
    }
    $totalPrice = 0;
    for ($x = 0; $x < count($cart); $x++) {
      $totalPrice += $cart[$x]["quantity"] * $cart[$x]["unit_price"];
    }
    $data["name"] = "Total";
    $data["quantity"] = "";
    $data["unit_price"] = "";
    $data["total_price"] = $totalPrice;
    $data["id"] = 0;
    $this->load->view("cart_item", $data);
    $ids = array();
    for ($x = 0; $x < count($cart); $x++) {
      $ids[] = (int) $cart[$x]["id"];
    }
    $this->load->view("hidden_input", array("id"=>"item-ids", "name"=>"ids", "value"=>json_encode($ids)));
    $this->load->view("table_end");
    $this->load->view("scripts/cart");
    $this->load->view("submit_checkout");
    $this->load->view("form_close");
  }
}
?>
