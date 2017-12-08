<tr>
  <td><?=$name?></td>
  <td><?=$items_count?></td>
  <td><?=$total_price?></td>
  <td><?=$status?></td>
  <td>
    <a class="w3-button" style="text-decoration:none;" href="<?=site_url("admin/viewOrder/$id")?>">View</a>
    <a class="w3-button" style="text-decoration:none;" href="<?=site_url("admin/deleteOrder/$id")?>">Delete</a>
  </td>
</tr>
