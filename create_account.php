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
            if((isset($_POST['username']) && !empty($_POST['username'])) && (isset($_POST['email']) && !empty($_POST['email'])) && (isset($_POST['password']) && !empty($_POST['password'])))
              PageRenderer::renderRegistrationResponse($db, $_POST['username'], $_POST['email'], $_POST['password']);
            else
              PageRenderer::renderRegistrationForm();
            ?>
        </div>
      </div>
    </p>
  </div>
</div>
<?php
PageRenderer::renderFooter();
?>