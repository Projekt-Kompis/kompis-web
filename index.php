<?php
include "incl/all.php";
PageRenderer::renderHeader($db);
?>
    <div class="container d-flex flex-column">
	  <div class="row fill d-flex justify-content-start content buffer">
	    <h1>Kompiš</h1>
	    <p>Webová aplikace pro tvorbu počítačových sestav</p>
	    <p><a href="create_assembly.php">Vytvořit sestavu</a></p>
	    <p><a href="browse_assemblies.php">Procházet již vytvořené sestavy</a></p>
	  </div>
	</div>
<?php
PageRenderer::renderFooter();
?>