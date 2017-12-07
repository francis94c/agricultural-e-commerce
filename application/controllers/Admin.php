<?php
class Admin extends CI_Controller {
  function __construct() {
    parent::__construct();
    if (!$this->isValidated()) {
      redirect('AdminLogin');
    }
  }
  function index() {
    $this->load->model("stocks");
    $stocks = $this->stocks->getStocks();
    $menu = array(array("Add Stock", site_url("Admin/showAddStock")));
    $data = array();
    $data["items"] = count($stocks);
    $data["menu"] = $menu;
    $data["title"] = "Stocks";
    $data["selected"] = 0;
    $this->load->view("admin_header", $data);
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
  }
  function isValidated() {
    return $this->session->userdata('validated');
  }
  function logout() {
    $this->session->sess_destroy();
    redirect('AdminLogin');
  }
  function showEditStock() {
    $this->load->model("stocks");
    $stock = $this->stocks->getStock($this->uri->segment(3));
    $data = array();
    $data["params"]["name"] = $stock["name"];
    $data["params"]["unit_price"] = $stock["unit_price"];
    $data["params"]["quantity"] = $stock["quantity"];
    $data["params"]["image"] = $stock["image"];
    $data["params"]["id"] = $stock["id"];
    $data["params"]["category"] = $stock["category"];
    $data["selected"] = -1;
    $data["title"] = "Edit Stock";
    $data["menu"] = array(array("Delete Stock", site_url("admin/deleteStock/" . $stock["id"])));
    $data["message"] = "";
    $data["action"] = "admin/editStock";
    $this->load->view("admin_header", $data);
    $this->load->helper("form");
    $this->load->view("stock_form", $data);
  }
  function editStock() {
    $name = $this->security->xss_clean($this->input->post('name'));
    $quantity = $this->security->xss_clean($this->input->post('quantity'));
    $unitPrice = $this->security->xss_clean($this->input->post('unit_price'));
    $category = $this->security->xss_clean($this->input->post('category'));
    $image = $this->security->xss_clean($this->input->post('image'));
    $id = $this->security->xss_clean($this->input->post('id'));
    $this->load->model("stocks");
    if ($this->stocks->editStock($id, array("name"=>$name, "quantity"=>$quantity,
    "unit_price"=>$unitPrice, "category"=>$category))) {
      $config["upload_path"] = FCPATH . "images";
      $config["allowed_types"] = "gif|jpg|png";
      $config["file_name"] = $image . ".jpg";
      $config["overwrite"] = true;
      $this->load->library("upload", $config);
      $this->upload->do_upload("image");
      $stock = $this->stocks->getStock($id);
      $data = array();
      $data["params"]["name"] = $stock["name"];
      $data["params"]["unit_price"] = $stock["unit_price"];
      $data["params"]["quantity"] = $stock["quantity"];
      $data["params"]["image"] = $stock["image"];
      $data["params"]["id"] = $stock["id"];
      $data["params"]["category"] = $stock["category"];
      $data["selected"] = -1;
      $data["title"] = "Edit Stock";
      $data["menu"] = array();
      $data["message"] = "";
      $data["action"] = "admin/editStock";
      $this->load->view("admin_header", $data);
      $this->load->helper("form");
      $this->load->view("stock_form", $data);
    } else {
      $stock = $this->stocks->getStock($this->uri->segment(3));
      $data = array();
      $data["params"]["name"] = $stock["name"];
      $data["params"]["unit_price"] = $stock["unit_price"];
      $data["params"]["quantity"] = $stock["quantity"];
      $data["params"]["image"] = $stock["image"];
      $data["params"]["id"] = $stock["id"];
      $data["params"]["category"] = $stock["category"];
      $data["selected"] = -1;
      $data["title"] = "Edit Stock";
      $data["menu"] = array();
      $data["action"] = "admin/editStock";
      $data["message"] = "<font color=\"red\">There was an error modifying the stock</font>";
      $this->load->view("admin_header", $data);
      $this->load->helper("form");
      $this->load->view("stock_form", $data);
    }
  }
  function showAddStock() {
    $data["menu"] = array();
    $data["title"] = "Add Stock";
    $data["selected"] = -1;
    $data["params"]["name"] ="";
    $data["params"]["unit_price"] = "";
    $data["params"]["quantity"] = "";
    $data["params"]["image"] = "";
    $data["params"]["id"] = "";
    $data["params"]["category"] = 0;
    $data["message"] = "";
    $data["action"] = "admin/addStock";
    $this->load->view("admin_header", $data);
    $this->load->helper("form");
    $this->load->view("stock_form", $data);
  }
  function addStock() {
    $name = $this->security->xss_clean($this->input->post('name'));
    $quantity = $this->security->xss_clean($this->input->post('quantity'));
    $unitPrice = $this->security->xss_clean($this->input->post('unit_price'));
    $category = $this->security->xss_clean($this->input->post('category'));
    $this->load->helper("string");
    $image = random_string('alnum', 15);
    $this->load->model("stocks");
    $config["upload_path"] = FCPATH . "images";
    $config["allowed_types"] = "gif|jpg|png";
    $config["file_name"] = $image . ".jpg";
    $config["overwrite"] = true;
    $this->load->library("upload", $config);
    if ($this->upload->do_upload("image")) {
      if ($this->stocks->addStock($name, $image, $quantity, $category, $unitPrice)) {
        $data = array();
        $data["params"]["name"] = "";
        $data["params"]["unit_price"] = "";
        $data["params"]["quantity"] = "";
        $data["params"]["image"] = "";
        $data["params"]["id"] = "";
        $data["params"]["category"] = 0;
        $data["selected"] = -1;
        $data["title"] = "Add Stock";
        $data["menu"] = array();
        $data["message"] = "<font color=\"green\">Stock Added Successfuly</font>";
        $data["action"] = "admin/addStock";
        $this->load->view("admin_header", $data);
        $this->load->helper("form");
        $this->load->view("stock_form", $data);
      } else {
        $data = array();
        $data["params"]["name"] = $name;
        $data["params"]["unit_price"] = $unitPrice;
        $data["params"]["quantity"] = $quantity;
        $data["params"]["image"] = "";
        $data["params"]["id"] = 0;
        $data["selected"] = -1;
        $data["params"]["category"] = $category;
        $data["title"] = "Add Stock";
        $data["menu"] = array();
        $data["action"] = "admin/editStock";
        $data["message"] = "<font color=\"red\">There was an error adding the Stock</font>";
        $this->load->view("admin_header", $data);
        $this->load->helper("form");
        $this->load->view("stock_form", $data);
      }
    } else {
      $data = array();
      $data["params"]["name"] = $name;
      $data["params"]["unit_price"] = $unitPrice;
      $data["params"]["quantity"] = $quantity;
      $data["params"]["image"] = "";
      $data["params"]["id"] = 0;
      $data["selected"] = -1;
      $data["params"]["category"] = $category;
      $data["title"] = "Add Stock";
      $data["menu"] = array();
      $data["action"] = "admin/editStock";
      $data["message"] = "<font color=\"red\">There was an error adding the Stock (with file upload)</font>";
      $this->load->view("admin_header", $data);
      $this->load->helper("form");
      $this->load->view("stock_form", $data);
    }
  }
  function showOrders() {
    
  }
}
?>
