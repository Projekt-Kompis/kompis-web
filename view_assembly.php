<?php
include "incl/all.php";
PageRenderer::renderHeader($db);
//TODO: private assemblies
if(!isset($_GET['id']) || empty($_GET['id']))
	exit("TODO: add a better error here");

?>
<div class="container d-flex flex-column">
  <div class="row fill d-flex justify-content-start content buffer">
    <?php PageRenderer::renderAssembly($db, $_GET['id']) ?>
  </div>
</div>
<?php
PageRenderer::renderFooter();
?>