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

$prov = intval($_POST['prov']);
$cnpj_valor = $_POST['cnpj'];

if (!in_array($prov, range(1, 3))) {
    echo json_encode(["mensagem" => "Provedor inválido."]);
    exit;
}

try {
    $processo = "file_get_contents";
    $ffi = CarregaContents($importsPath, $dllPath);

    $processo = "CNPJ_Inicializar";
    if (Inicializar($ffi, $iniPath) != 0)
        exit;

    $processo = "CNPJ_ConfigGravarValor";
    if (ConfigGravarValor($ffi, "ConsultaCNPJ", "Provedor", (string)$prov) != 0)
        exit;

    $iniContent = "";
    $processo = "CNPJ_Consultar";
    $resultado = Consultar($ffi, $cnpj_valor, $iniContent);
    if($resultado != 0)
    exit;    

    $parsedIni = parseIniToStr($iniContent);
    $responseData = [
        'retorno' => $resultado,
        'tamanho_resposta' => $esTamanho->cdata,
        'mensagem' => $iniContent,
        'dados' => [
            'abertura' => $parsedIni['Consulta']['Abertura'] ?? '',
            'bairro' => $parsedIni['Consulta']['Bairro'] ?? '',
            'cep' => $parsedIni['Consulta']['CEP'] ?? '',
            'CNAE1' => $parsedIni['Consulta']['CNAE1'] ?? '',
            'CNAE2' => $parsedIni['Consulta']['CNAE2'] ?? '',
            'Cidade' => $parsedIni['Consulta']['Cidade'] ?? '',
            'Complemento' => $parsedIni['Consulta']['Complemento'] ?? '',
            'EmpresaTipo' => $parsedIni['Consulta']['EmpresaTipo'] ?? '',
            'Endereco' => $parsedIni['Consulta']['Endereco'] ?? '',
            'Fantasia' => $parsedIni['Consulta']['Fantasia'] ?? '',
            'InscricaoEstadual' => $parsedIni['Consulta']['InscricaoEstadual'] ?? '',
            'NaturezaJuridica' => $parsedIni['Consulta']['NaturezaJuridica'] ?? '',
            'Numero' => $parsedIni['Consulta']['Numero'] ?? '',
            'RazaoSocial' => $parsedIni['Consulta']['RazaoSocial'] ?? '',
            'Situacao' => $parsedIni['Consulta']['Situacao'] ?? '',
            'UF' => $parsedIni['Consulta']['UF'] ?? '',
        ]
    ];

} catch (Exception $e) {
    $erro = $e->getMessage();
    echo json_encode(["mensagem" => "Exceção[$processo]: $erro"]);
    exit;
}

try {
    if ($processo != "CNPJ_Inicializar") {
        $processo = "CNPJ_Inicializar";
        if (Finalizar($ffi) != 0)
            exit;
    }
} catch (Exception $e) {
    $erro = $e->getMessage();
    echo json_encode(["mensagem" => "Exceção[$processo]: $erro"]);
    exit;
}

echo json_encode($responseData);