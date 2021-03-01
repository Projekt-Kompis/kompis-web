<?php
include "incl/all.php";
PageRenderer::renderHeader($db);
?>
<div class="container d-flex flex-column">
  <div class="row fill d-flex justify-content-start content buffer">
    <?php PageRenderer::renderAssemblies($db) ?>
  </div>
</div>
<?php
PageRenderer::renderFooter();
?>