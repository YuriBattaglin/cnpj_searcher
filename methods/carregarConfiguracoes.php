<?php

header('Content-Type: application/json; charset=UTF-8');

include '../ST/ACBrConsultaCNPJST.php';

if (ValidaFFI() != 0)
    exit;

$dllPath = CarregaDll();

if ($dllPath == -10)
    exit;

$importsPath = CarregaImports();

if ($importsPath == -10)
    exit;

$iniPath = CarregaIniPath();

$sReposta = FFI::new("char[9048]");
$esTamanho = FFI::new("long");
$esTamanho->cdata = 9048;

try {
    $processo = "file_get_contents";
    $ffi = CarregaContents($importsPath, $dllPath);

    $processo = "CNPJ_Inicializar";
    if (Inicializar($ffi, $iniPath) != 0)
        exit;

        $provedor = "";
        $processo = "CNPJ_ConfigLerValor";
        $resultado = ConfigLerValor($ffi, "ConsultaCNPJ", "Provedor", $provedor);

        if($resultado != 0)
        exit;

        $usuario = "";
        $processo = "CNPJ_ConfigLerValor";
        $resultado = ConfigLerValor($ffi, "ConsultaCNPJ", "Usuario", $usuario);

        if($resultado != 0)
        exit;

        $senha = "";
        $processo = "CNPJ_ConfigLerValor";
        $resultado = ConfigLerValor($ffi, "ConsultaCNPJ", "Senha", $senha);

        if($resultado != 0)
        exit;

        $processo = "responseData";
        $responseData = [
            'retorno' => $resultado,
            'dados' => [
                'usuario' => $usuario ?? '',
                'senha' => $senha ?? '',
                'provedor' => $provedor ?? '',
            ]
        ];

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

echo json_encode($responseData);
?>