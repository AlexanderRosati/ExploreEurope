<?php
    $mapWidth = 800;
    $mapHeight = 800;
?>

<script type="text/javascript">
    const mapWidth = <?php echo $mapWidth;?>;
    const mapHeight = <?php echo $mapHeight;?>;
</script>

<style>
    #map, .mapLayer 
        {width:<?php echo $mapWidth;?>; 
         height:<?php echo $mapHeight;?>;}
</style>

<html lang="en">
	<head>
		<title>Explore Europe - Map</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="styles/normalize.css">
		<link rel="stylesheet" href="styles/map.css">
		<script src="scripts/mapHandler.js"></script>
	</head>

    
    <?php include('includes/header.php'); ?>


	<body>        
        <div id="map" onclick="mapClick(event)" onmousemove="mapHover(event)"
                      onmouseover="mapEnter()" onmouseout="mapExit()">
            
            <?php
                //Create individual canvas objects for each country that we will be supporting
                //This is used to create a clickable map
                $countryCodes = file_get_contents('data/country-codes.json');
                $countryCodes = json_decode($countryCodes, true);
                foreach ($countryCodes as $country)
                {
                    echo "\n" . '<canvas name="' . $country['name'] . 
                                      '" id="layer_' .$country['alpha3Code'] . 
                                      '" width="' . $mapWidth .
                                      '" height="' . $mapHeight .
                                      '" class="mapLayer">';

                    echo "\n     " . '<img id="img_' . $country['alpha3Code'] .
                                        '" src="img/' .$country['alpha3Code'] . '.png' .
                                        '" width="'  . $mapWidth .
                                        '" height="' . $mapHeight .'">';

                    echo "\n" . '</canvas>' . "\n";
                }
            ?>

            <div id="hover_indicator" hidden>
                
            </div>
        </div>
        
        
	</body>
    <?php include('includes/footer.php'); ?>
</html>