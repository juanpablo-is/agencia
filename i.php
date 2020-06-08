<?PHP
$hostname_localhost = "localhost";
$database_localhost = "ecomhome_batallas_rap";
$username_localhost = "ecomhome";
$password_localhost = "ecomhome9910";

$conexion = mysqli_connect($hostname_localhost, $username_localhost, $password_localhost, $database_localhost);
mysqli_set_charset($conexion, "utf8");
$consulta = "SELECT * FROM `canales_yt`";
$resultado = mysqli_query($conexion, $consulta);

$key = "AIzaSyB9WDIuDLk553-c_8yud1RwQ6PpbV21jR0";
$resultadoBatallas['canalesYoutube'] = array();

$con = 0;
$conB = 0;
while ($registro = mysqli_fetch_assoc($resultado)) {
    $idYT = $registro['id_yt'];

    $json_url = 'https://www.googleapis.com/youtube/v3/search?key=' . $key . '&channelId=' . $idYT . '&part=snippet,id&order=date&maxResults=3';
    $ch = curl_init($json_url);

    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Content-type: application/json'
        )
    );

    curl_setopt_array($ch, $options);

    $result = curl_exec($ch);
    $json_output = json_decode($result, true);
    foreach ($json_output["items"] as $contador => $elemento) {
        $resultadoBatallas['batallas'][$conB]['idVideo'] = $elemento['id']['videoId'];
        $resultadoBatallas['batallas'][$conB]['nombre'] = $elemento['snippet']['title'];
        $resultadoBatallas['batallas'][$conB]['imagen'] = $elemento['snippet']['thumbnails']['high']['url'];
        $resultadoBatallas['batallas'][$conB++]['canal'] = $elemento['snippet']['channelTitle'];
    }
}

//shuffle($resultadoBatallas);
echo json_encode($resultadoBatallas);

mysqli_close($conexion);
