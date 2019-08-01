<tr>
  <td><?=$name?></td>
  <td><?=$id != 0 ? "<input onclick=\"calculateTotalPrice($id)\" type=\"number\" min=\"0\" name=\"item-$id\" id=\"box-$id\" value=\"$quantity\"/>" : "";?></td>
  <td id="unit-<?=$id?>"><?=$unit_price?></td>
  <td id="price-<?=$id?>"><?=$total_price?></td>
  <td>
    <a class="w3-button" style="text-decoration:none;" href="<?php echo $id != 0 ? site_url("admin/viewOrder/$id") : "#"?>">View</a>
    <a class="w3-button" style="text-decoration:none;" href="<?php echo $id != 0 ? site_url("admin/deleteOrder/$id") : "#"?>">Remove</a>
  </td>
</tr>
