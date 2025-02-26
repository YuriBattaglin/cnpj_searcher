<?php

header('Content-Type: application/json; charset=UTF-8');

include '../ST/ACBrConsultaCNPJST.php';

if(ValidaFFI() != 0)
exit;

$dllPath = CarregaDll();

if($dllPath == -10)
exit;

$importsPath = CarregaImports();

if($importsPath == -10)
exit;

$iniPath = CarregaIniPath();

try {
    $processo = "file_get_contents";
    $ffi = CarregaContents($importsPath, $dllPath);

    $processo = "CNPJ_Inicializar";
    if (Inicializar($ffi, $iniPath) != 0)
        exit;

        $processo = "CNPJ_ConfigGravarValor";

        $prov = intval($_POST['prov']);
        $usuario = $_POST['usuario'];
        $senha = $_POST['senha'];

        if(ConfigGravarValor($ffi, "ConsultaCNPJ", "Provedor", (string)$prov) != 0) exit;
        if(ConfigGravarValor($ffi, "ConsultaCNPJ", "Usuario", (string)$usuario) != 0) exit;
        if(ConfigGravarValor($ffi, "ConsultaCNPJ", "Senha", (string)$senha) != 0) exit;

} catch (Exception $e) {
    $erro = $e->getMessage();
    echo json_encode(["mensagem" => "Exceção[$processo]: $erro"]);
    exit;
}

try {
    if($processo != "CNPJ_Inicializar"){
        $processo = "CNPJ_Inicializar";
        if(Finalizar($ffi) != 0)
        exit;
    }
} catch (Exception $e) {
    $erro = $e->getMessage();
    echo json_encode(["mensagem" => "Exceção[$processo]: $erro"]);
    exit;
}

echo json_encode(["mensagem" => "Configurações salvas com sucesso."]);
?>