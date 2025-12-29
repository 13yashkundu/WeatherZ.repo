<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeatherZ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,200..800;1,200..800&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Oswald:wght@200..700&display=swap');
    </style>
    <link rel="stylesheet" href="weather2.css">
</head>


<?php
function getweather()
{

    $city = isset($_GET['search']) ? trim($_GET['search']) : "";

    if (!$city) {
        echo "Please enter city name";
        return;
    }

    $apiUrl = "https://api.weatherapi.com/v1/current.json?key=5681555b3ddb46be902105138251911&q=" . urlencode($city) . "&aqi=no";
    $data = json_decode(file_get_contents($apiUrl), true);

    if (!$data || isset($data["error"])) {
        echo "Invalid city name!";
        return;
    }

    $cityName   = $data["location"]["name"];
    $icon       = $data["current"]["condition"]["icon"];
    $tempC      = $data["current"]["temp_c"];
    $tempF      = $data["current"]["temp_f"];
    $humidity   = $data["current"]["humidity"];
    $cloud      = $data["current"]["cloud"];
    $feelsLike  = $data["current"]["feelslike_c"];
    $gust       = $data["current"]["gust_kph"];
    $windSpeed  = $data["current"]["wind_kph"];
    $windDegree = $data["current"]["wind_degree"];
    $windDir    = $data["current"]["wind_dir"];

    echo "
        <h1 class='city'>$cityName</h1>
        <div class='weather-container'>

            <div class='card'>
                <div class='card-head'>Temperature</div>
                <h1 class='degree'>$tempC °C</h1>
                <img src='$icon' alt='Weather Icon'>
                <ul>
                    <li>Temperature: $tempC °C</li>
                    <li>Temp (F): $tempF °F</li>
                    <li>Cloud: $cloud %</li>
                </ul>
            </div>
  
            <div class='card'>
                <div class='card-head'>Humidity</div>
                <h1 class='degree'>$humidity %</h1>
                <ul>
                <img src='$icon' alt='Weather Icon'>
                    <li>Humidity: $humidity %</li>
                    <li>Feels Like: $feelsLike °C</li>
                    <li>Wind Degree: $windDegree</li>
                </ul>
            </div>

            <div class='card'>
                <div class='card-head'>Wind Speed</div>
                <h1 class='degree'>$windSpeed km/h</h1>
                <ul>
                <img src='$icon' alt='Weather Icon'>
                    <li>Wind Speed: $windSpeed km/h</li>
                    <li>Gust Speed: $gust km/h</li>
                    <li>Wind Direction: $windDir</li>
                </ul>
            </div>

        </div>
    ";
}
function forecast()
{
    $city = isset($_GET['search']) ? trim($_GET['search']) : "";

    if (!$city) {
        return;
    }

    $api = "https://api.weatherapi.com/v1/forecast.json?key=5681555b3ddb46be902105138251911&q=" . urlencode($city) . "&days=7";
    $data = json_decode(file_get_contents($api), true);

    if (!$data || isset($data["error"])) {
        return;
    }

    $forecast = $data["forecast"]["forecastday"];
    foreach ($forecast as $item) {
        $temp = $item["day"]["maxtemp_c"];
        $icon = $item["day"]["condition"]["icon"];
        $date = $item["date"];

        echo "
        <div class='fore-container'>
        <div class='forecast'>
        <h3>$temp &#176;C</h3>
        <img src='$icon' alt='Weather Icon' style='color: #fff;'>
        <p>$date</p>
        </div>
        </div>
        ";
    }
}

function getCityWeather($city)
{
    $API_KEY = "5681555b3ddb46be902105138251911";

    $url = "https://api.weatherapi.com/v1/current.json?key=$API_KEY&q=" . urlencode($city) . "&aqi=no";
    $data = json_decode(@file_get_contents($url), true);

    if (!$data || isset($data["error"])) {
        echo "<tr><td colspan='4'>Could not retrieve data for $city</td></tr>";
        return;
    }

    $tempC = $data["current"]["temp_c"];
    $humidity = $data["current"]["humidity"];
    $windSpeed = $data["current"]["wind_kph"];

    echo "
        <tr>
            <td>$city</td>
            <td>$tempC &#176;C</td>
            <td>$humidity %</td>
            <td>$windSpeed km/h</td>
        </tr>
    ";
}

$majorCities = ["Mumbai", "Delhi", "Bangalore", "Chennai", "Kolkata"];
?>




<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="weather">WeatherZ.com</div>
            <div class="search-box">
                <form id="search-form" method="get" action="">
                    <input type="text" id="search-input" name="search" placeholder="Enter city name" value="">
                    <button id="search-button" type="submit"><i class="fa-solid fa-magnifying-glass" style="color: #ffffff;"></i></button>
                </form>
            </div>
        </div>
    </nav>

    <div class="main-div">

        <?php getweather(); ?>



        <?php if (!empty($_GET['search'])): ?>
            <h1>3-Days Forecast</h1>
            <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;">
                <?php forecast(); ?>
            </div>
        <?php endif; ?>

        <div class="container-default">

            <h2>Current Weather across major cities</h2>

            <table>
                <tr>
                    <th>City</th>
                    <th>Temperature</th>
                    <th>Humidity</th>
                    <th>Wind Speed</th>
                </tr>

                <?php
                foreach ($majorCities as $city) {
                    getCityWeather($city);
                }
                ?>
            </table>

        </div>
    </div>
</body>


</html>
