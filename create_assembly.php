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
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Komponenta</th>
          <th scope="col">Výběr</th>
          <th scope="col">Cena</th>
          <th scope="col">Obchod</th>
          <th scope="col">Odkaz</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <?php
        PageRenderer::renderCurrentChoice($db, 'case');
        PageRenderer::renderCurrentChoice($db, 'cpu');
        PageRenderer::renderCurrentChoice($db, 'gpu');
        PageRenderer::renderCurrentChoice($db, 'motherboard');
        PageRenderer::renderCurrentChoice($db, 'optical');
        PageRenderer::renderCurrentChoice($db, 'os');
        PageRenderer::renderCurrentChoice($db, 'psu');
        PageRenderer::renderCurrentChoice($db, 'ram');
        PageRenderer::renderCurrentChoice($db, 'storage');
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php
PageRenderer::renderFooter();
?>