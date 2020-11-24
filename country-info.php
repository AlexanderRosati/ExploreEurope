<?php
    // url for web api request; $_GET['alpha'] is country code
    $url = 'https://restcountries.eu/rest/v2/alpha/' . $_GET['alpha'];
    $url .= '?fields=name;capital;subregion;population;latlng;borders;flag';

    // do request and get back JSON string
    $country_info = file_get_contents($url);

    // turn JSON string into associative array
    $country_info = json_decode($country_info, true);
?>

<html lang="en">
    <head>
        <title>Info On <?php echo $country_info['name']; ?></title>
        <meta charset="UTF-8">
    </head>
    <body>
        <h1 class="country-name">
            <img class="flag" src="<?php echo $country_info['flag']; ?>" width="50" height="30"
            alt="Image of Flag of <?php echo $country_info['name']; ?>"
            title="Flag of <?php echo $country_info['name']; ?>">
            <?php echo $country_info['name']; ?>
        </h1>
        <table>
            <tr>
                <th>Capital</th>
                <td><?php echo $country_info['capital']; ?></td>
            </tr>
            <tr>
                <th>Subregion</th>
                <td><?php echo $country_info['subregion']; ?></td>
            </tr>
            <tr>
                <th>Population</th>
                <td><?php echo $country_info['population']; ?></td>
            </tr>
            <tr>
                <th>Latitude</th>
                <td><?php echo $country_info['latlng'][0]; ?></td>
            </tr>
            <tr>
                <th>Longitude</th>
                <td><?php echo $country_info['latlng'][1]; ?></td>
            </tr>
        </table>
        <h2>Borders</h2>
        <ul>
            <?php
                // for each bordering country
                foreach ($country_info['borders'] as $country_code) {
                    // create url for request
                    $url = 'https://restcountries.eu/rest/v2/alpha/' . $country_code;
                    $url .= '?fields=name';

                    // another request to REST countries for country name
                    $country_name = file_get_contents($url);

                    // convert to associative array
                    $country_name = json_decode($country_name, true);

                    echo "<li><a href=\"country-info.php?alpha=$country_code\">" . $country_name['name'] . "</a></li>";
                }
            ?>
        </ul>
    </body>
</html>