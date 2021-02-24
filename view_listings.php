<?php
include "incl/all.php";
PageRenderer::renderHeader($db);
if(!isset($_GET['type']) || empty($_GET['type']))
	exit("TODO: add a better error here");

$incompatible = isset($_GET['incompatible']) && !empty($_GET['incompatible']) && $_GET['incompatible'];

$type = strtolower(trim($_GET['type']));
?>
<div class="container d-flex flex-column">
  <div class="row fill d-flex justify-content-start content buffer">
    <p>
      <div class="card">
        <div class="card-body">
          <form>
          	<input type="hidden" name="type" value="<?php echo $_GET['type']; ?>">
          	<input class="form-check-input" type="checkbox" value="true" name="incompatible" id="incompatible" <?php if($incompatible) echo "checked"; ?> onchange="form.submit()">
			<label class="form-check-label" for="incompatible">
			  Zobrazit nekompatibilní součástky
			</label>
		  </form>
        </div>
      </div>
    </p>
  </div>
  <div class="row fill d-flex justify-content-start content buffer">
    <?php PageRenderer::renderListings($db, $type, $incompatible) ?>
  </div>
</div>
<?php
PageRenderer::renderFooter();
?>