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
			            <li class="nav-item">
			              <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Přihlásit se</a>
			            </li>
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
	public static function renderRemoveButton($type){
		echo "<form action=\"remove_listing.php\"><button type=\"submit\" class=\"btn btn-primary\" name=\"type\" value=\"$type\">Odebrat</button></form>";
	}
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
				echo '<th scope="col">TDP</th>';
				echo '<th scope="col">Cena/výkon</th>';
				break;
			case 'gpu':
				echo '<th scope="col">Paměť</th>';
				echo '<th scope="col">TDP</th>';
				echo '<th scope="col">Cena/výkon</th>';
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
		echo "<tr>
			<th scope=\"row\">{$listing['model']}";
		if($listing['model'] != $listing['name'])
			echo "<br>({$listing['name']})";
		echo "</th>
	          <td>{$listing['item_condition']}</td>
	          <td>{$listing['price']}</td>
	          <td>{$listing['store']}</td>
	          <td>{$listing['location']}</td>";
	    switch($listing['type']){
			case 'case':
				echo "<td>{$listing['motherboard_form_factor']}</td>";
				break;
			case 'cpu':
				if($listing['price'] != 0)
					$ratio = round(($listing['userbenchmark_score'] / $listing['price']) * 1000);
				else
					$ratio = 0;
				echo "<td>{$listing['cpu_socket']}</td>
					<td>{$listing['frequency']}</td>
					<td>{$listing['core_count']}</td>
					<td>{$listing['tdp']}</td>
					<td>{$ratio}</td>";
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
	    echo "<td><a href=\"view_listing.php?id={$listing['id']}\">&gt;&gt;</td>";
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
	public static function renderCurrentChoice($db, $type){
		$choice = MainLib::getCurrentChoiceID($type);
		echo "<tr>
          <th scope=\"row\">{$type}</th>
          <td>";
          if(!$choice){
          	PageRenderer::renderViewButton($type);
	        echo "</td>
	          <td></td>
	          <td></td>
	          <td></td>
	          <td></td>
	        </tr>";
          }else{
          	$listing = MainLib::getBasicListingInfo($db, $choice);
          	echo "{$listing['model']}</td>
          <td>{$listing['price']} Kč</td>
          <td>{$listing['store']}</td>
          <td><a href=\"{$listing['store_url']}\">&gt;&gt;</a></td>
          <td>";
          PageRenderer::renderRemoveButton($type);
          echo "</td></tr>";
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
}