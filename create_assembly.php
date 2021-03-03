<?php
include "incl/all.php";
PageRenderer::renderHeader($db);
?>
<div class="container d-flex flex-column">
  <div class="row fill d-flex justify-content-start content buffer">
    <p>
      <div class="card">
        <div class="card-body">
          <b>Kompatibilita:</b> Žádné problémy s kompatibilitou nebyly detekovány. (protože to ještě neumíme lol)<br>
          <b>TDP:</b> <?php echo MainLib::getTotalTDP($db); ?> W
        </div>
      </div>
    </p>
  </div>
  <div class="row fill d-flex justify-content-start content buffer">
        <?php
        PageRenderer::renderChoiceHeader();
        PageRenderer::renderChoice($db, 'case');
        PageRenderer::renderChoice($db, 'cpu');
        PageRenderer::renderChoice($db, 'gpu');
        PageRenderer::renderChoice($db, 'motherboard');
        PageRenderer::renderChoice($db, 'optical');
        PageRenderer::renderChoice($db, 'os');
        PageRenderer::renderChoice($db, 'psu');
        PageRenderer::renderChoice($db, 'ram');
        PageRenderer::renderChoice($db, 'storage');
        PageRenderer::renderChoiceFooter();
        ?>
    <?php PageRenderer::renderCreateButtons(); ?>
  </div>
</div>
<?php
PageRenderer::renderFooter();
?>