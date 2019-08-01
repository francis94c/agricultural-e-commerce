<script type="text/javascript">
function calculateTotalPrice(id) {
  var unit = parseInt($("#unit-" + id).html());
  var quantity = parseInt($("#box-" + id).val());
  var totalPrice = unit * quantity;
  $("#price-" + id).html(totalPrice);
  total();
}
function total() {
  var ids = JSON.parse($("#item-ids").val());
  var total = 0;
  for (x = 0; x < ids.length; x++) {
    total += parseInt($("#price-" + ids[x]).html());
  }
  $("#price-0").html(total);
}
</script>
