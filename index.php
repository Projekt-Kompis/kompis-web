<?php
include "incl/connection.php";
include "incl/pageRenderer.php";
PageRenderer::renderHeader($db);
?>
    <p>vítejte v kompiši lol</p>
<?php
PageRenderer::renderFooter();
?>