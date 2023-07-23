<?php
require 'google/vendor/autoload.php';

// Función para obtener el servicio de Google Sheets autenticado
function getGoogleSheetsService($credentialsPath)
{
    $credentials = json_decode(file_get_contents($credentialsPath), true);
    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
    return new Google_Service_Sheets($client);
}

// Ruta al archivo de credenciales JSON
$credentialsPath = 'credenciales.json';

// Reemplaza 'TU_NOMBRE_DE_PROYECTO' con el nombre de tu proyecto en Google Developer Console
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);

// Obtener el ID del archivo de Excel desde la URL
$fileId = $_GET['fileId'];

// Obtener el servicio de Google Sheets autenticado
$service = getGoogleSheetsService($credentialsPath);

// Obtener la lista de hojas en el archivo
try {
    $sheets = $service->spreadsheets->get($fileId)->getSheets();
    $nombreHoja = $sheets[0]->properties->title; // Usamos la primera hoja
} catch (Google_Service_Exception $e) {
    echo 'Error al acceder a la hoja de cálculo: ' . $e->getMessage();
    exit;
}

// Obtener los datos de la hoja de cálculo de Google Sheets
try {
    $response = $service->spreadsheets_values->get($fileId, $nombreHoja);
    $values = $response->getValues();
} catch (Google_Service_Exception $e) {
    echo 'Error al acceder a la hoja de cálculo: ' . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Contenido del Google Sheets - <?php echo $nombreHoja; ?></title>
</head>

<body>
    <h1>Contenido del Google Sheets - <?php echo $nombreHoja; ?></h1>
    <table border="1">
        <?php
        if (empty($values)) {
            echo '<tr><td colspan="2">No se encontraron datos en la hoja de cálculo.</td></tr>';
        } else {
            foreach ($values as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo '<td>' . htmlspecialchars($cell) . '</td>';
                }
                echo '</tr>';
            }
        }
        ?>
    </table>
</body>

</html>
