<div class="w3-col l3 s6">
  <?php if($is_admin) {?>
    <a href="<?=site_url("admin/showEditStock/$id");?>" style="text-decoration:none;">
  <?php } else {?>
    <a href="<?=site_url("home/addToCart/$id");?>" style="text-decoration:none;">
  <?php }?>
      <div class="w3-container">
      <img width="200" height="200" src="<?=$image?>" style="width:85%"/>
      <?php if ($is_admin) {?>
        <p class="w3-margin"><?=$name?><br/><b>&#x20A6;<?=number_format($price)?>:  (<?=$quantity?> in stock).</b></p>
      <?php } else {?>
        <p class="w3-margin"><?=$name?><br/><b>&#x20A6;<?=number_format($price)?></b></p>
      <?php }?>
      </div>
    </a>
</div>
