<?php
include "incl/all.php";
PageRenderer::renderHeader($db);
?>
<div class="container d-flex flex-column">
  <div class="row fill d-flex justify-content-start content buffer">
    <p>
      <div class="card" style="max-width: 400px;">
        <div class="card-body">
          <?php
            if((isset($_POST['name']) && !empty($_POST['name'])) && (isset($_POST['visibility']) && !empty($_POST['visibility']) && in_array($_POST['visibility'],['private','unlisted','public'])))
              PageRenderer::renderSaveResponse($db, $_POST['name'], $_POST['visibility'], $_SESSION['part']);
            else
              PageRenderer::renderSaveForm();
            ?>
        </div>
      </div>
    </p>
  </div>
</div>
<?php
PageRenderer::renderFooter();
?>