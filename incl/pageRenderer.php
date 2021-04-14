<?php
class PageRenderer {

	public static function renderHeader($db){
		session_start();
		?>

			<!doctype html>
			<html lang="cs">
			  <head>
			    <!-- Required meta tags -->
			    <meta charset="utf-8">
			    <meta name="viewport" content="width=device-width, initial-scale=1">

			    <!-- Bootstrap CSS -->
			    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

			    <!-- Font Awesome -->
				<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

				<!-- Kompis CSS -->
			    <link href="style/style.css" rel="stylesheet">

			    <title>Kompiš</title>
			  </head>
			  <body>
			    <nav class="navbar navbar-expand-lg navbar-light bg-light">
			      <div class="container-fluid">
			        <a class="navbar-brand" href="#">Kompiš</a>
			        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			          <span class="navbar-toggler-icon"></span>
			        </button>
			        <div class="collapse navbar-collapse" id="navbarSupportedContent">
			          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
			            <li class="nav-item">
			              <a class="nav-link active" aria-current="page" href="index.php">Domů</a>
			            </li>
			            <li class="nav-item">
			              <a class="nav-link" href="create_assembly.php">Vytvořit sestavu</a>
			            </li>
			            <li class="nav-item">
			              <a class="nav-link" href="browse_assemblies.php">Procházet sestavy</a>
			            </li>
			          </ul>
			          <ul class="navbar-nav d-flex">
			          	<?php
			          	if(AccountManager::isAccountLoggedIn()){?>
			          		<li class="nav-item dropdown">
					          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					            <?= AccountManager::getLoggedinUsername($db) ?>
					          </a>
					          <div class="dropdown-menu dropdown-menu-end">
								  <a class="dropdown-item" href="#">Můj účet</a>
								  <a class="dropdown-item" href="#">Moje sestavy</a>
								  <div class="dropdown-divider"></div>
								  <a class="dropdown-item" href="logout.php">Odhlásit se</a>
								  <!--<a class="dropdown-item" href="#">Zapomenuté heslo</a>-->
								</div>
					        </li>
			          	<?php }else{?>
				            <li class="nav-item dropdown">
					          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					            Přihlásit se
					          </a>
					          <div class="dropdown-menu dropdown-menu-end">
								  <form class="px-4 py-3" method="post" action="login.php">
								    <div class="mb-3">
								      <label for="usernameForm" class="form-label">Uživatelské jméno</label>
								      <input type="text" class="form-control" id="usernameForm" placeholder="Uživatelské jméno" name="username">
								    </div>
								    <div class="mb-3">
								      <label for="passwordForm" class="form-label">Heslo</label>
								      <input type="password" class="form-control" id="passwordForm" placeholder="Heslo" name="password">
								    </div>
								    <div class="mb-3">
								      <!--<div class="form-check">
								        <input type="checkbox" class="form-check-input" id="dropdownCheck">
								        <label class="form-check-label" for="dropdownCheck">
								          Remember me
								        </label>
								      </div>-->
								    </div>
								    <button type="submit" class="btn btn-primary">Přihlásit se</button>
								  </form>
								  <div class="dropdown-divider"></div>
								  <a class="dropdown-item" href="create_account.php">Nový uživatel? Vytvořit účet</a>
								  <!--<a class="dropdown-item" href="#">Zapomenuté heslo</a>-->
								</div>
					        </li>
					    <?php } ?>
			          </ul>
			        </div>
			      </div>
			    </nav>
			    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
					
					<?php
	}
	public static function renderFooter(){
					?>

			  </body>
			</html>
		
		<?php
	}
	public static function renderViewButton($type){
		echo "<form action=\"view_listings.php\"><button type=\"submit\" class=\"btn btn-primary\" name=\"type\" value=\"$type\">Přidat komponentu</button></form>";
	}
	public static function renderAddButton($id){
		echo "<form action=\"add_listing.php\"><button type=\"submit\" class=\"btn btn-primary\" name=\"id\" value=\"$id\">Přidat</button></form>";
	}
	public static function renderRemoveButton($id){
		echo "<form action=\"remove_listing.php\"><button type=\"submit\" class=\"btn btn-primary\" name=\"id\" value=\"$id\">Odebrat</button></form>";
	}
	public static function renderCreateButtons(){ ?>
		<div class="form-group">
		<form class='form-float' action="save_assembly.php">
		<button type="submit" class="btn btn-primary">Sdílet sestavu</button>
		</form>
		<form class='form-float' action="delete_listings.php">
		<button type="submit" class="btn btn-primary">Začít odznova</button>
		</form>
		</div>
	<?php }
	public static function renderListingsHeader($type){
		?>

			<table class="table">
			      <thead>
			        <tr>
			          <th scope="col">Komponenta</th>
			          <th scope="col">Stav</th>
			          <th scope="col">Cena</th>
			          <th scope="col">Obchod</th>
			          <th scope="col">Místo</th>
			          <?php PageRenderer::renderListingsHeaderTypeSpecific($type) ?>
			          <th scope="col">Informace</th>
			          <th scope="col"></th>
			        </tr>
			      </thead>
			      <tbody>

		<?php
	}
	public static function renderListingsHeaderTypeSpecific($type){
		switch($type){
			case 'case':
				echo '<th scope="col">Druh základní desky</th>';
				break;
			case 'cpu':
				echo '<th scope="col">Socket</th>';
				echo '<th scope="col">Frekvence</th>';
				echo '<th scope="col">Počet jader</th>';
				echo '<th scope="col">Potřebný výkon zdroje</th>';
				echo '<th scope="col">Cena/výkon</th>';
				break;
			case 'gpu':
				echo '<th scope="col">Paměť</th>';
				echo '<th scope="col">Potřebný výkon zdroje</th>';
				echo '<th scope="col">Cena/výkon</th>';
				break;
			case 'cooler':
				echo '<th scope="col">Socket</th>';
				break;
			case 'motherboard':
				echo '<th scope="col">Druh základní desky</th>';
				echo '<th scope="col">Socket</th>';
				echo '<th scope="col">Druh paměti RAM</th>';
				break;
			case 'optical':
				echo '<th scope="col">Druh mechaniky</th>';
				break;
			case 'os':
				echo '<th scope="col">S fakturou</th>';
				break;
			case 'psu':
				echo '<th scope="col">Výkon</th>';
				break;
			case 'ram':
				echo '<th scope="col">Velikost</th>';
				echo '<th scope="col">Druh paměti RAM</th>';
				echo '<th scope="col">Rychlost</th>';
				break;
			case 'storage':
				echo '<th scope="col">Druh úložiště</th>';
				echo '<th scope="col">Velikost</th>';
				echo '<th scope="col">Konektor</th>';
				break;
		}
	}
	public static function renderListing($listing){
		$renderablePrice = PageRenderer::preparePrice($listing['price']);
		echo "<tr>
			<th scope=\"row\">{$listing['model']}";
		if($listing['model'] != $listing['name'])
			echo "<br>({$listing['name']})";
		echo "</th>
	          <td>{$listing['item_condition']}</td>
	          <td>{$renderablePrice}</td>
	          <td>{$listing['store']}</td>
	          <td>{$listing['location']}</td>";
	    switch($listing['type']){
			case 'case':
				echo "<td>{$listing['motherboard_form_factor']}</td>";
				break;
			case 'cpu':
				echo "<td>{$listing['cpu_socket']}</td>
					<td>{$listing['frequency']}</td>
					<td>{$listing['core_count']}</td>
					<td>{$listing['tdp']}</td>
					<td>{$listing['listing_score']}</td>";
				break;
			case 'cooler':
				echo "<td>{$listing['cpu_socket']}</td>";
				break;
			case 'gpu':
				if($listing['price'] != 0)
					$ratio = round(($listing['userbenchmark_score'] / $listing['price']) * 1000);
				else
					$ratio = 0;
				echo "<td>{$listing['vram']}</td>
				<td>{$listing['tdp']}</td>
				<td>{$ratio}</td>";
				break;
			case 'motherboard':
				echo "<td>{$listing['motherboard_form_factor']}</td>
					<td>{$listing['cpu_socket']}</td>
					<td>DDR{$listing['ddr_version']}</td>";
				break;
			case 'optical':
				echo "<td>{$listing['optical_type']}";
				break;
			case 'os':
				if($listing['invoice'])
					echo '<td>Ano</td>';
				else
					echo '<td>Ne</td>';
				break;
			case 'psu':
				echo "<td>{$listing['wattage']} W</td>";
				break;
			case 'ram':
				echo "<td>"; PageRenderer::renderSizeWithUnit($listing['size']); echo "</td>
					<td>DDR{$listing['ddr_version']}</td>
					<td>{$listing['speed']} MHz</td>";
				break;
			case 'storage':
				echo "<td>{$listing['storage_type']}</td>
					<td>"; PageRenderer::renderSizeWithUnit($listing['size']); echo "</td>
					<td>{$listing['connector']}</td>";
				break;
		}
	    //echo "<td><a href=\"view_listing.php?id={$listing['id']}\">&gt;&gt;</td>";
	    echo "<td><a href=\"{$listing['store_url']}\">&gt;&gt;</td>";
	    echo "<td>";
	    PageRenderer::renderAddButton($listing['id']);
	    echo "</tr>";
	}
	public static function renderListingsInfo($db, $type, $incompatible){
		foreach(MainLib::getListings($db, $type, $incompatible) as &$listing){
			PageRenderer::renderListing($listing);
		}
	}
	public static function renderListings($db, $type, $incompatible){
		

		PageRenderer::renderListingsHeader($type);
		PageRenderer::renderListingsInfo($db, $type, $incompatible);
		?>

		      </tbody>
		    </table>

		<?php
	}
	public static function renderChoice($db, $type, $choiceArray = null, $renderButtons = true, $isReplacement = false){
		$readablePartTypes = ['case' => 'Skříň', 'cooler' => 'Chladič', 'cpu' => 'Procesor', 'gpu' => 'Grafická karta', 'motherboard' => 'Základní deska', 'optical' => 'Mechanika', 'os' => 'Operační systém', 'psu' => 'Zdroj', 'ram' => 'Paměť RAM', 'storage' => 'Úložiště'];
		if(!isset($choiceArray))
			$choiceArray = MainLib::getCurrentChoiceArray($type);
		$choices = MainLib::getChoicesInfo($db, $type, $choiceArray);
		foreach($choices as &$listing){
			$renderablePrice = PageRenderer::preparePrice($listing['price']);
			if($listing['is_invalid'] == 1){
				$replacement = MainLib::getReplacementChoice($db, $listing['id']);
				if($replacement)
					PageRenderer::renderChoice($db, $type, [$replacement], $renderButtons, true);
				$trClass = ' class="choice-invalid"';
			}
			elseif($isReplacement)
				$trClass = ' class="choice-replacement"';
			else
				$trClass = '';
			echo "<tr{$trClass}>
          <th scope=\"row\">{$readablePartTypes[$type]}</th>
          <td>{$listing['model']}</td>
          <td>{$renderablePrice}</td>
          <td>{$listing['store']}</td>
          <td><a href=\"{$listing['store_url']}\">&gt;&gt;</a></td>
          <td>";
          if($renderButtons == true)
          	PageRenderer::renderRemoveButton($listing['id']);
          echo "</td></tr>";
		}
		if((empty($choices) || in_array($type, ['optical', 'storage', 'ram'])) && $renderButtons){ //TODO: check similar ram
			echo "<tr>
	          <th scope=\"row\">{$readablePartTypes[$type]}</th>
	          <td>";
	          	PageRenderer::renderViewButton($type);
		        echo "</td>
		          <td></td>
		          <td></td>
		          <td></td>
		          <td></td>
		        </tr>";
	    }
          
	}
	public static function renderSizeWithUnit($size){
		if($size >= 1048576){
			$size = round($size / 1048576);
			echo "{$size} TB";
		}
		elseif($size >= 1024){
			$size = round($size / 1024);
			echo "{$size} GB";
		}
		else
			echo "{$size} MB";
	}
	public static function renderRegistrationForm(){ ?>
		<form method="post">
            <div class="mb-3">
              <label for="usernameForm" class="form-label">Uživatelské jméno</label>
              <input type="text" class="form-control" id="usernameForm" name="username">
            </div>
            <div class="mb-3">
              <label for="emailForm" class="form-label">E-mailová adresa</label>
              <input type="email" class="form-control" id="emailForm" aria-describedby="emailHelp" name="email">
              <div id="emailHelp" class="form-text">Vaše e-mailová adresa nebude s nikým sdílena.</div>
            </div>
            <div class="mb-3">
              <label for="passwordForm" class="form-label">Heslo</label>
              <input type="password" class="form-control" id="passwordForm" aria-describedby="passwordHelp" name="password">
              <div id="passwordHelp" class="form-text">Doporučujeme něco bezpečného. (např. ABCabc123)</div>
            </div>
            <button type="submit" class="btn btn-primary">Vytvořit účet</button>
          </form>
	<?php } //TODO (for below): render warnings - assembly empty, assembly incomplete, incompatible parts etc.
	public static function renderSaveForm(){ ?>
		<form method="post">
            <div class="mb-3">
              <label for="nameForm" class="form-label">Název sestavy</label>
              <input type="text" class="form-control" id="nameForm" name="name">
            </div>
            <div class="mb-3">
	            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
				  <input type="radio" class="btn-check" name="visibility" id="publicRadio" autocomplete="off" value="public" checked>
				  <label class="btn btn-outline-primary" for="publicRadio">Veřejná</label>

				  <input type="radio" class="btn-check" name="visibility" id="unlistedRadio" autocomplete="off" value="unlisted">
				  <label class="btn btn-outline-primary" for="unlistedRadio">Neveřejná</label>

				  <!--<input type="radio" class="btn-check" name="visibility" id="privateRadio" autocomplete="off" value="private">
				  <label class="btn btn-outline-primary" for="privateRadio">Soukromá</label>-->
				</div>
			</div>
			<?php if(!AccountManager::isAccountLoggedIn()){ ?>
            <div class="mb-3">
				<div class="card">
			        <div class="card-body">
			          <b>Varování:</b> Nejste přihlášeni, sestavu již nebude možné upravit.
			        </div>
		      </div>
		  </div>
		<?php } ?>
          <div class="mb-3">
            <button type="submit" class="btn btn-primary">Sdílet sestavu</button>
        </div>
          </form>
	<?php }
	public static function renderRegistrationResponse($db, $username, $email, $password){
		switch(AccountManager::createAccount($db, $username, $email, $password)){
			case 0:
				echo "Účet úspěšně vytvořen.";
				break;
			case -2:
				echo "Toto uživatelské jméno je již použité.";
				break;
			case -3:
				echo "Tato emailová adresa je již použitá.";
				break;
			default:
				echo "Něco se zvrtlo.";
				break;
		}

	}
	public static function renderSaveResponse($db, $name, $visibility, $parts){
		$assemblyID = AssemblyManager::createAssembly($db, $name, $visibility, $parts);
		if($assemblyID <= 0){
			echo "Něco se zvrtlo.";
			return false;
		}
		else{
			echo "Vaše sestava je nyní dostupná <a href=\"view_assembly.php?id={$assemblyID}\">zde</a>.";
			return true;
		}

	}
	public static function renderAssembly($db, $assemblyID){
		PageRenderer::renderAssemblyRevision($db, AssemblyManager::getLatestRevisionID($db, $assemblyID));
	}
	public static function renderAssemblyRevision($db, $assemblyRevisionID){
		$assemblyRevision = new AssemblyRevision($db, $assemblyRevisionID);
		$assemblyName = $assemblyRevision->getAssemblyName();
		$assemblyListings = $assemblyRevision->getListingList();
		$assemblyUsername = $assemblyRevision->getUsername();
		$assemblyID = $assemblyRevision->getAssemblyID();
		$renderableStars = PageRenderer::prepareStarRating($assemblyRevision->getPointsAverage());
		if(AccountManager::isAccountLoggedIn())
			$renderableStars .= " (Vaše hodnocení: " . PageRenderer::prepareStarRating($assemblyRevision->getAccountRating(AccountManager::getLoggedinAccountID()), true, $assemblyRevision->getAssemblyID()) . ")";
		echo "<h2 class=\"mt-2\">{$assemblyName}</h2><p><b>Autor:</b> <a href=\"#\" class=\"author-link\">{$assemblyUsername}</a><br><b>Hodnocení:</b> {$renderableStars}</b></p>";
		PageRenderer::renderChoiceHeader();
		foreach(MainLib::getPartTypes() as &$type){
			PageRenderer::renderChoice($db, $type, $assemblyListings, false);
		}
		PageRenderer::renderChoiceFooter();
		echo "<h3>Komentáře</h3>";
		PageRenderer::renderAssemblyCommentBox($assemblyID);
		PageRenderer::renderAssemblyComments($db, $assemblyID);
	}
	public static function renderAssemblyComments($db, $assemblyID){
		$comments = CommentManager::getAssemblyComments($db, $assemblyID);
		foreach($comments as &$comment){ ?>
		    	<div class="card p-3 m-2">
				        <div class="comment-heading">
				            <!--<div class="comment-voting">
				                <button type="button">
				                    <span aria-hidden="true">&#9650;</span>
				                    <span class="sr-only">Hlasovat pro</span>
				                </button>
				                <button type="button">
				                    <span aria-hidden="true">&#9660;</span>
				                    <span class="sr-only">Hlasovat proti</span>
				                </button>
				            </div>-->
				            <div class="comment-info">
				                <a href="#" class="author-link"><?= AccountManager::getUsername($db, $comment['account_id']) ?></a>
				                <p class="m-0">
				                    <!--1 bod &bull; --><?= $comment['time_created'] ?>
				                </p>
				            </div>
				        </div>

				        <div class="comment-body">
				            <p>
				                <?= htmlspecialchars($comment['text']) ?>
				            </p>
				            <!--<button type="button" class="btn btn-primary">Odpovědět</button>-->
				        </div>
			    </div>
		<?php }
	}
	public static function renderAssemblyCommentBox($assemblyID){
		if(AccountManager::isAccountLoggedIn()){ ?>
		    	<div class="card p-3 m-2">
				        <form method="post" action="add_comment.php">
          				  <input type="hidden" name="id" value="<?php echo $assemblyID; ?>">
						  <div class="form-group p-2">
						    <label for="contentForm"><b>Přidat komentář</b></label>
						    <textarea class="form-control" id="contentForm" rows="3" name="content"></textarea>
						  </div>
						  <div class="p-2">
						  	<button type="submit" class="btn btn-primary">Přidat</button>
						  </div>
						</form>
			    </div>
		<?php }
	}
	public static function renderChoiceHeader(){ ?>
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
	<?php }
	public static function renderChoiceFooter(){ ?>
      </tbody>
    </table>
	<?php }
	public static function renderAssemblies($db){
		

		PageRenderer::renderAssembliesHeader();
		PageRenderer::renderAssembliesInfo($db);
		?>

		      </tbody>
		    </table>

		<?php
	}
	public static function renderAssembliesHeader(){
		?>

			<table class="table">
			      <thead>
			        <tr>
			          <th scope="col">Název</th>
			          <th scope="col">Autor</th>
			          <th scope="col">Vytvořeno</th>
			          <th scope="col">Hodnocení</th>
			          <th scope="col">Cena</th>
			        </tr>
			      </thead>
			      <tbody>

		<?php
	}
	public static function renderAssembliesInfo($db){
		foreach(AssemblyManager::getAssemblyList($db) as &$assemblyID){
			PageRenderer::renderAssemblyInfo($db, $assemblyID);
		}
	}
	public static function renderAssemblyInfo($db, $assemblyID){
		$assemblyRevision = new AssemblyRevision($db, AssemblyManager::getLatestRevisionID($db, $assemblyID));
		$renderableStars = PageRenderer::prepareStarRating($assemblyRevision->getPointsAverage());
		$renderablePrice = PageRenderer::preparePrice($assemblyRevision->getPrice());
		echo "<tr>
	          <td><a href=\"view_assembly.php?id={$assemblyRevision->getAssemblyID()}\">{$assemblyRevision->getAssemblyName()}</a></td>
	          <td>{$assemblyRevision->getUsername()}</td>
	          <td>{$assemblyRevision->getTimeCreated()}</td>
	          <td>{$renderableStars}</td>
	          <td>{$renderablePrice}</td>
	        </tr>";
	}
	public static function prepareStarRating($points, $clickable = false, $assemblyID = 0){
		$rating = "";
		for($i = 1; $i <= 5; $i++){
			$checked = "";
			if($points >= $i)
				$checked = "checked";
			if($clickable)
				$rating .= "<a href=\"rate_assembly.php?id={$assemblyID}&points={$i}\" class=\"star-clickable-link\"><span class=\"fa fa-star star-clickable {$checked}\"></span></a>";
			else
				$rating .= "<span class=\"fa fa-star {$checked}\"></span>";
		}
		return $rating;
	}
	public static function preparePrice($price){
		if($price > 10)
			return "{$price} Kč";
		else
			return "<i>Dohodou</i>";
	}
}