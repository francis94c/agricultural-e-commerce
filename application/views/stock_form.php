<p class="w3-margin"><?=$message?></p>
<?php echo form_open_multipart($action);?>
<form class="input-form" action="" method="">
  <div class="input-group w3-margin margin-bottom-sm">
    <span class="input-group-addon"><i class="fa fa-camera-o fa-fw"></i></span>
    <input class="form-control" name="image" type="file" placeholder="Image"/>
  </div>
  <?php if ($params["image"] != "") { ?>
    <img class="w3-margin" width="250" height="250" src="<?=base_url("images/" . $params["image"] . ".jpg")?>"/>
  <?php }?>
  <div class="input-group w3-margin margin-bottom-sm">
    <span class="input-group-addon"><i class="fa fa-user-o fa-fw"></i></span>
    <input class="form-control" name="name" type="text" placeholder="Name" value="<?=$params['name']?>"/>
  </div>
  <div class="input-group w3-margin margin-bottom-sm">
    <span class="input-group-addon"><i class="fa fa-user-o fa-fw"></i></span>
    <input class="form-control" name="unit_price" type="text" placeholder="Unit Price" value="<?=$params['unit_price']?>">
  </div>
  <div class="input-group w3-margin margin-bottom-sm">
    <span class="input-group-addon"><i class="fa fa-user-o fa-fw"></i></span>
    <input class="form-control" name="quantity" type="text" placeholder="Quantity" value="<?=$params['quantity']?>">
  </div>
  <div class="input-group w3-margin margin-bottom-sm">
    <span class="input-group-addon"><i class="fa fa-user-o fa-fw"></i></span>
    <select class="form-control" name="category">
      <?php
      $ci =& get_instance();
      $ci->load->model("Fields");
      $categories = $ci->Fields->getCategories();
      $c = count($categories);
      for ($x = 0; $x < $c; $x++) {
        if ($params["category"] == $categories[$x]["id"]) {
          echo "<option selected value=\"" . $categories[$x]["id"] . "\">" . $categories[$x]["name"] . "</option>";
        } else {
          echo "<option value=\"" . $categories[$x]["id"] . "\">" . $categories[$x]["name"] . "</option>";
        }
      }
      ?>
    </select>
  </div>
  <?php
  echo form_hidden("id", $params["id"]);
  echo form_hidden("image", $params["image"]);
  ?>
  <input class="w3-right" type="submit" value="Submit"/>
</form>
