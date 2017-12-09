<!DOCTYPE html>
<html>
<title>FUTO Farms</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?=base_url("css/w3.css");?>">
<link rel="stylesheet" href="<?=base_url("css/google-fonts.css");?>">
<link rel="stylesheet" href="<?=base_url("css/fa/font-awesome.min.css");?>">
<link rel="stylesheet" href="<?=base_url("css/bootstrap.min.css");?>">
<style>
.w3-sidebar a {font-family: "Roboto", sans-serif}
body,h1,h2,h3,h4,h5,h6,.w3-wide {font-family: "Montserrat", sans-serif;}
</style>
<script type="text/javascript" src="<?=base_url("js/jquery.js");?>"></script>
<script type="text/javascript">
function toggleSearch() {
  $("#searchInput").toggle();
  if ($("#searchInput").is(":visible")) {
    $("#actualSeachInput").focus();
  }
}
</script>
<body class="w3-content" style="max-width:1200px">

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-bar-block w3-white w3-collapse w3-top" style="z-index:3;width:250px" id="mySidebar">
  <div class="w3-green w3-container w3-display-container w3-padding-16">
    <i onclick="w3_close()" class="fa fa-remove w3-hide-large w3-button w3-display-topright"></i>
    <h3 class="w3-wide"><b>FUTO FARMS</b></h3>
  </div>
  <div class="w3-padding-64 w3-large w3-text-grey" style="font-weight:bold">
    <?php
    $c = count($menu);
    for ($x = 0; $x <$c; $x++) {
      if ($selected == $x) {
        echo "<a href=\"#\" class=\"w3-bar-item w3-gray w3-button\">" . $menu[$x]["name"] . "</a>";
      } else {
        echo "<a href=\"#\" class=\"w3-bar-item w3-button\">" . $menu[$x]["name"] . "</a>";
      }
    }
    ?>

  </div>
  <a href="#footer" class="w3-bar-item w3-button w3-padding">Contact</a>
</nav>

<!-- Top menu on small screens -->
<header class="w3-bar w3-top w3-hide-large w3-black w3-xlarge">
  <div class="w3-bar-item w3-padding-24 w3-wide">LOGO</div>
  <a href="javascript:void(0)" class="w3-bar-item w3-button w3-padding-24 w3-right" onclick="w3_open()"><i class="fa fa-bars"></i></a>
</header>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px">

  <!-- Push down content on small screens -->
  <div class="w3-hide-large" style="margin-top:83px"></div>

  <!-- Top header -->
  <header class="w3-container w3-xlarge">
    <?php echo form_open("home/searchStocks"); ?>
    <div id="searchInput" style="display:none;" class="input-group w3-margin margin-bottom-sm">
      <span class="input-group-addon"><i class="fa fa-camera-o fa-fw"></i></span>
      <input id="actualSeachInput" class="form-control" name="key-word" type="text" placeholder="Search"/>
    </div>
    <?php echo form_close(); ?>
    <p class="w3-left"><?=$title?></p>
    <p class="w3-right">
    <?php
    $ci =& get_instance();
    if ($ci->session->userdata("cart") == null) {
    ?>
      <a class="w3-button" href="#" style="text-decoration:none;"><i class="fa fa-shopping-cart w3-margin-right"></i></a>
    <?php } else {?>
      <a class="w3-button" href="<?=site_url("home/viewCart");?>" style="text-decoration:none;"><i class="fa fa-shopping-cart w3-margin-right"><span class="w3-badge w3-blue"><?=count($ci->session->userdata("cart"));?></span></i></a>
    <?php }?>
      <button onclick="toggleSearch();" class="w3-button"><i class="fa fa-search"></i></button>
    </p>
  </header>
  <div class="w3-container w3-text-grey" id="jeans">
    <?php if (isset($items)) {?>
      <p><?=$items?> items</p>
    <?php }?>
  </div>
