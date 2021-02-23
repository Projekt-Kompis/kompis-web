<?php
include "incl/all.php";
PageRenderer::renderHeader($db);
if(!isset($_GET['type']) || empty($_GET['type']))
  exit("TODO: add a better error here");

$type = strtolower(trim($_GET['type']));
?>
<div class="container d-flex flex-column">
  <div class="row fill d-flex justify-content-start content buffer">
    <?php PageRenderer::renderListings($db, $type) ?>
  </div>
</div>
<?php
PageRenderer::renderFooter();
?>