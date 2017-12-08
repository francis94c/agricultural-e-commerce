<?php
class Home extends CI_Controller {
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
        $lastIndex += 8;
        ++$page;
        $this->load->view("product_grid_footer");
    }
    $this->load->view("footer");
  }
}
?>
