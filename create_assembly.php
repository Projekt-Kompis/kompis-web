<?php
include "incl/all.php";
PageRenderer::renderHeader($db);
?>
<div class="container d-flex flex-column">
  <div class="row fill d-flex justify-content-start content buffer">
    <p>
      <div class="card">
        <div class="card-body">
          <b>Kompatibilita:</b> Žádné problémy s kompatibilitou nebyly detekovány.<br>
          <b>Potřebný výkon zdroje:</b> <?php echo MainLib::getTotalTDP($db); ?> W
        </div>
      </div>
    </p>
  </div>
  <div class="row fill d-flex justify-content-start content buffer">
        <?php
        PageRenderer::renderChoiceHeader();
        foreach(MainLib::getPartTypes() as &$partType)
          PageRenderer::renderChoice($db, $partType);
        PageRenderer::renderChoiceFooter();
        ?>
    <?php PageRenderer::renderCreateButtons(); ?>
  </div>
</div>
<?php
PageRenderer::renderFooter();
?>