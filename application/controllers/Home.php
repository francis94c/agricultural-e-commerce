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
    $this->session->userdata("navigation") : array("search"=>"", "category"=>0, "index"=>0);
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
      $this->fetchCategory($navigation["category"], $navigation["index"]);
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
    $this->load->view("submit_input", array("text"=>"Checkout"));
    $this->load->view("form_close");
    $this->load->view("footer");
  }
  function checkout() {
    if ($this->session->userdata("id") == null) {
      $this->session->set_userdata("checking_out", true);
      redirect("login");
    } else {
      $this->load->model("fields");
      $data = array("menu" => $this->fields->getCategories());
      $data["selected"] = 0;
      $data["title"] = "Shipping Address";
      $this->load->view("header", $data);
      $this->load->view("form_open", array("url"=>"home/proceedToPay"));
      $this->load->model("users");
      $data["shipping"] = $this->users->getUserShippingAddress($this->session->userdata("id"));
      $this->load->view("shipping_form", $data);
      $this->load->view("submit_input", array("text"=>"Proceed To Pay"));
      $this->load->view("form_close");
      $this->load->view("footer");
    }
  }
  function proceedToPay() {
    $shipping = $this->security->xss_clean($this->input->post("shipping"));
    $message = $this->security->xss_clean($this->input->post("message"));
    if ($shipping != "") {
      $this->session->set_userdata("shipping", $shipping);
      $this->session->set_userdata("message", $message);
      $this->load->model("fields");
      $data = array("menu" => $this->fields->getCategories());
      $data["selected"] = 0;
      $data["title"] = "Pay (Credit Card)";
      $this->load->view("header", $data);
      $this->load->view("form_open", array("url"=>"home/pay"));
      $this->load->view("pay_form");
      $this->load->view("submit_input", array("text"=>"Pay"));
      $this->load->view("form_close");
      $this->load->view("footer");
    } else {
      $this->load->model("fields");
      $data = array("menu" => $this->fields->getCategories());
      $data["selected"] = 0;
      $data["title"] = "Shipping Address";
      $this->load->view("header", $data);
      $this->load->view("message", array("text"=>"<font color=\"red\">No Shipping Address!</font>"));
      $this->load->view("form_open", array("url"=>"home/proceedToPay"));
      $this->load->model("users");
      $data["shipping"] = $this->users->getUserShippingAddress($this->session->userdata("id"));
      $this->load->view("shipping_form", $data);
      $this->load->view("submit_input", array("text"=>"Proceed To Pay"));
      $this->load->view("form_close");
      $this->load->view("footer");
    }
  }
  function pay() {
    $cvv2 = $this->security->xss_clean($this->input->post("cvv2"));
    $cardName = $this->security->xss_clean($this->input->post("card_name"));
    $cardNumber = $this->security->xss_clean($this->input->post("card_number"));
    $expiryDay = $this->security->xss_clean($this->input->post("expiry_day"));
    $expiryYear = $this->security->xss_clean($this->input->post("expiry_year"));
    $pin = $this->security->xss_clean($this->input->post("pin"));
    if ($pin != "" && $cvv2 != "" && $cardName != "" && $expiryDay != "" &&
    $expiryYear != "") {
      $this->load->model("orders");
      if ($this->orders->createOrder(true)) {
        $this->load->model("fields");
        $data = array("menu" => $this->fields->getCategories());
        $data["selected"] = 0;
        $data["title"] = "Pay (Credit Card)";
        $this->load->view("header", $data);
        $this->load->view("message", array("text"=>"<font color=\"green\">Payment Successful and Order Ceated.</font>"));
        $this->load->view("footer");
      } else {
        $this->load->model("fields");
        $data = array("menu" => $this->fields->getCategories());
        $data["selected"] = 0;
        $data["title"] = "Pay (Credit Card)";
        $this->load->view("header", $data);
        $this->load->view("message", array("text"=>"<font color=\"red\">Payment Successful but Order Creation Failed</font>"));
        $this->load->view("footer");
      }
    } else {
      $this->load->model("fields");
      $data = array("menu" => $this->fields->getCategories());
      $data["selected"] = 0;
      $data["title"] = "Pay (Credit Card)";
      $this->load->view("header", $data);
      $this->load->view("message", array("text"=>"<font color=\"red\">Incorrect Payment Details</font>"));
      $this->load->view("form_open", array("url"=>"home/pay"));
      $this->load->view("pay_form");
      $this->load->view("submit_input", array("text"=>"Pay"));
      $this->load->view("form_close");
      $this->load->view("footer");
    }
  }
  function fetchCategory() {
    $cid = 0;
    $index = 0;
    if (func_num_args() > 0) {
      $cid = func_get_arg(0);
      $index = func_get_arg(1);
    } else {
      $cid = $this->uri->segment(3);
      $index = $this->uri->segment(4);
    }
    if ($cid != 0) {
      $this->load->model("stocks");
      $this->load->model("fields");
      $menu = $this->fields->getCategories();
      $data = array("menu" => $menu);
      $data["selected"] = $index;
      $data["title"] = $menu[$index]["name"];
      $stocks = $this->stocks->getStocksInCategory($cid);
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
      $navigation["category"] = $cid;
      $navigation["index"] = $index;
      $this->session->set_userdata("navigation", $navigation);
    } else {
      $this->index();
    }
  }
}
?>
