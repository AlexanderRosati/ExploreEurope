//Returns the canvas layer of the country focused
function getCountryFocused(x, y)
{
    //TODO: Consider caching this as a global
    var mapLayers = document.getElementsByTagName("canvas");
    var countryClicked = "";

    for (var i = 0; i < mapLayers.length; i++)
    {
        pixelClicked = mapLayers[i].getContext("2d").getImageData(x,y,1,1).data;

        //If the pixel focused is not transparent (IE a country was clicked)
        if (pixelClicked[3]) 
            return mapLayers[i];
    }
    //Nothing focused:
    return null;
}


//Detects if the user clicked on a country in the map
//If not, nothing happens
//If so, send them to the page for that country
function mapClick(event)
{
    //Get mouse coordinates within the map display
    var x = event.offsetX;   var y = event.offsetY;

    var countryLayer = getCountryFocused(x, y);

    if (countryLayer != null)
    {
        var countryClicked = countryLayer.getAttribute("id");
        var countryClickedCode = countryClicked.substring(6);
        window.location.href = "country-info.php?alpha=" + countryClickedCode;
    }
}

//Displays the name of the country being hovered over
function mapHover(event)
{
    //Get mouse coordinates within the map display
    var x = event.offsetX;   var y = event.offsetY;

    var countryLayer = getCountryFocused(x, y);

    var hoverText = document.getElementById("hover_indicator");

    if (countryLayer != null)
    {
        hoverText.innerHTML = "Explore: " + countryLayer.getAttribute("name");
        document.body.style.cursor = 'pointer';
    }
    else
    {
        hoverText.innerHTML = "Explore: ...";
        document.body.style.cursor = 'default';
    }
}

function mapEnter()
{
    var hoverText = document.getElementById("hover_indicator");
    hoverText.hidden = false;
}

function mapExit()
{
    var hoverText = document.getElementById("hover_indicator");
    hoverText.hidden = true;
}

//Gets the country-codes.json info from the server
//this info will be used to interact with the html which is generated using said file
//as well as drawing the map object
function getCountryCodesJSON()
{
    var xhttpr = new XMLHttpRequest();
    var url = "data/country-codes.json";
    xhttpr.open("GET", url, true);
    xhttpr.send();

    xhttpr.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var countryCodesRaw = JSON.parse(this.responseText);
            drawMap(countryCodesRaw);
        }
    };
}

//Called once the XMLHTTPRequest is satisfied, uses the info
//retrieved by the request to draw the map using info about the html file
function drawMap(countryList)
{
    for (i in countryList)
    {
        //Grab the canvas and image objects from the html
        var canvasName = "layer_" + countryList[i].alpha3Code;
        var imageName  = "img_" + countryList[i].alpha3Code;

        var canvas = document.getElementById(canvasName);
        var image = document.getElementById(imageName);

        //Try to draw the image onto the canvas
        //TODO: Optimization: build a matrix of pixels and their associated
        //alpha3Codes to negate the need for all the canvas layere
        if (canvas == null || image == null) continue;
        else
        {
            try
            {
                var context = canvas.getContext("2d");
                context.drawImage(image, 0, 0, mapWidth, mapHeight);
            }
            catch
            {
                console.log("WARNING: Image for " + countryList[i].name + " not found.");
            }
        }
    }
}

function init()
{
    getCountryCodesJSON();
}
window.addEventListener('load', init);