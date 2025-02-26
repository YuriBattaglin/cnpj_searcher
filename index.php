<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>Consulta CNPJ</title>
</head>

<style>
    .tituloColunas {
        display: flex;
        align-items: center;
    }

    .retornoCampos {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        row-gap: 1px;
    }

    .retornoCamposColuna {
        display: flex;
        flex-direction: column;
    }
</style>

<body >
    <div class="tituloColunas">
        <img src="./imagens/search.png" alt="" width="100">
        <h1>Consulta CNPJ</h1>
    </div>

    <form id="formConsulta" >
        <label for="cnpj">Digite o CNPJ:</label>
        <input type="text" id="cnpj" name="cnpj">
        <br><br>
        <label for="prov">Selecione o provedor:</label>
        <select id="prov" name="prov">
            <option value="0">cwsNenhum</option>
            <option value="1">cwsBrasilAPI</option>
            <option value="2">cwsReceitaWS</option>
            <option value="3">cwsCNPJWs</option>
        </select>
        <label for="usuario">Usuário:</label>
        <input type="text" id="usuario" name="usuario">
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha">
        <br><br>
        <input type="button" id="consultaCNPJ" value="Consultar">
        <input type="button" id="salvarConfiguracoes" value="Salvar Configurações">
        <input type="button" id="carregarConfiguracoes" value="Carregar Configurações">
    </form>

    <div class="retornoCampos">
    <div class="retornoCamposColuna">
            <label for="abertura">Abertura</label>
            <input type="text" id="abertura" name="abertura">
        </div>

        <div class="retornoCamposColuna">
            <label for="bairro">Bairro</label>
            <input type="text" id="bairro" name="bairro">
        </div>

        <div class="retornoCamposColuna">
            <label for="cep">CEP</label>
            <input type="text" id="cep" name="cep">
        </div>

        <div class="retornoCamposColuna">
            <label for="cnae1">CNAE1</label>
            <input type="text" id="cnae1" name="cnae1">
        </div>

        <div class="retornoCamposColuna">
            <label for="cnae2">CNAE2</label>
            <input type="text" id="cnae2" name="cnae2">
        </div>

        <div class="retornoCamposColuna">
            <label for="cidade">Cidade</label>
            <input type="text" id="cidade" name="cidade">
        </div>

        <div class="retornoCamposColuna">
            <label for="complemento">Complemento</label>
            <input type="text" id="complemento" name="complemento">
        </div>

        <div class="retornoCamposColuna">
            <label for="empresaTipo">Empresa Tipo</label>
            <input type="text" id="empresaTipo" name="empresaTipo">
        </div>

        <div class="retornoCamposColuna">
            <label for="endereco">Endereço</label>
            <input type="text" id="endereco" name="endereco">
        </div>

        <div class="retornoCamposColuna">
            <label for="fantasia">Fantasia</label>
            <input type="text" id="fantasia" name="fantasia">
        </div>

        <div class="retornoCamposColuna">
            <label for="inscricaoEstadual">IE</label>
            <input type="text" id="inscricaoEstadual" name="inscricaoEstadual">
        </div>

        <div class="retornoCamposColuna">
            <label for="naturezaJuridica">Natureza Juridica</label>
            <input type="text" id="naturezaJuridica" name="naturezaJuridica">
        </div>

        <div class="retornoCamposColuna">
            <label for="numero">Número</label>
            <input type="text" id="numero" name="numero">
        </div>

        <div class="retornoCamposColuna">
            <label for="razaoSocial">Razao Social</label>
            <input type="text" id="razaoSocial" name="razaoSocial">
        </div>

        <div class="retornoCamposColuna">
            <label for="situacao">Situação</label>
            <input type="text" id="situacao" name="situacao">
        </div>

        <div class="retornoCamposColuna">
            <label for="uf">UF</label>
            <input type="text" id="uf" name="uf">
        </div>
    </div>

    <label  for="result">Retorno:</label>
    <br>
    <textarea id="result" rows="10" cols="100" readonly></textarea>
    <br><br>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#salvarConfiguracoes').on('click', function() {
                $.ajax({
                    url: 'methods/salvarConfiguracoes.php',
                    type: 'POST',
                    data: {
                        prov: $('#prov').val(),
                        usuario: $('#usuario').val(),
                        senha: $('#senha').val(),
                    },
                    success: function(response) {
                        if (response.mensagem)
                        $('#result').val(response.mensagem)
                        else
                            $('#result').val('Erro: ' + JSON.stringify(response, null, 4))
                    },
                    error: function(error) {
                        if (error.mensagem)
                            $('#result').val(error.mensagem)
                        else
                        $('#result').val('Erro: ' + JSON.stringify(error, null, 4))
                    },
                })
            });

            $('#carregarConfiguracoes').on('click', function() {
                $.ajax({
                    url: 'methods/carregarConfiguracoes.php',
                    type: 'POST',
                    success: function(response) {
                        if (response.dados){
                            $('#result').val(JSON.stringify(response, null, 4));
                            $('#usuario').val(response.dados.usuario)
                            $('#senha').val(response.dados.senha)
                            $('#prov').val(response.dados.prov)
                        }else{
                            if(response.mensagem)
                            $('#result').val(response.mensagem)
                            else
                            $('#result').val('Erro: ' + JSON.stringify(response, null, 4))
                        }
                    },
                    error: function(error) {
                        if (error.mensagem)
                            $('#result').val(error.mensagem)
                        else
                            $('#result').val('Erro: ' + JSON.stringify(error, null, 4))
                    },
                })
            });

            $('#consultaCNPJ').on('click', function() {
                $.ajax({
                    url: 'methods/consultaCnpj.php',
                    type: 'POST',
                    data: {
                        cnpj: $('#cnpj').val(),
                        prov: $('#prov').val()
                    },
                    success: function(response) {
                        if (response.dados){
                            $('#result').val(JSON.stringify(response, null, 4));
                            $('#abertura').val(response.dados.abertura);
                            $('#bairro').val(response.dados.bairro);
                            $('#cep').val(response.dados.cep);
                            $('#cnae1').val(response.dados.CNAE1 || '');
                            $('#cnae2').val(response.dados.CNAE2 || '');
                            $('#cidade').val(response.dados.Cidade || '');
                            $('#complemento').val(response.dados.Complemento || '');
                            $('#empresaTipo').val(response.dados.EmpresaTipo || '');
                            $('#endereco').val(response.dados.Endereco || '');
                            $('#fantasia').val(response.dados.Fantasia || '');
                            $('#inscricaoEstadual').val(response.dados.InscricaoEstadual || '');
                            $('#naturezaJuridica').val(response.dados.NaturezaJuridica || '');
                            $('#numero').val(response.dados.Numero || '');
                            $('#razaoSocial').val(response.dados.RazaoSocial || '');
                            $('#situacao').val(response.dados.Situacao || '');
                            $('#uf').val(response.dados.uf || '');
                        }else{
                            if(response.mensagem)
                            $('#result').val(response.mensagem)
                            else
                            $('#result').val('Erro: ' + JSON.stringify(response, null, 4))
                        }
                    },
                    error: function(error) {
                        if (error.mensagem)
                            $('#result').val(error.mensagem)
                        else
                            $('#result').val('Erro: ' + JSON.stringify(error, null, 4))
                    },
                })
            });
        });
    </script>
</body>