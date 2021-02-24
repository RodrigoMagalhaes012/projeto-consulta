<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SESSION["vs_array_access"], "T0004") == 0) {
    print('<script> alert(\'Favor realizar login novamente!\'); location.href = \'https://app.agrocontar.com.br\'; </script>');
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="css/animate.css" rel="stylesheet">
    <link rel="stylesheet" href="../class/alert/css/class_alert.css" id="theme-styles">
    <link rel="stylesheet" href="css/dunfe.css" id="theme-styles">
    <!-- <link rel="stylesheet" href="css/codbarras.css"> -->
    <script src="../class/alert/js/class_alert.js"></script>

    <title>AgroContar APP</title>

</head>

<body>

    <div class="container">

        <div id="box_tab_titulo" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-4">
                    <h3>NFE - SAÍDA</h3>
                </div>
                <div class="form-group col-sm-8">
                    <select id="c_empresa" class="form-control class_inputs">

                    </select>
                </div>
            </div>
        </div>


        <div id="box_tab_titulo2" class="box" style="height: 60px; margin-bottom: 10px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="col-sm-3">
                    <select id="c_filtro_analises" class="form-control class_inputs">
                        <option value="T">TODAS AS ANÁLISES</option>
                        <option value="P">NÃO LANÇADAS</option>
                        <option value="-">NÃO ANALISADAS</option>
                        <option value="S">ANALISADAS COM SUCESSO</option>
                        <option value="N">ANALISADAS COM RESSALVA</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <select id="c_filtro_manifesto" class="form-control class_inputs">
                        <option value="T">TODAS AS NOTAS</option>
                        <option value="0">SEM MANIFESTO</option>
                        <option value="110110">CORRIGIDAS</option>
                        <option value="110111">CANCELADAS</option>
                        <option value="210200">CONFIRMADAS</option>
                        <option value="210210">CIÊNCIA</option>
                        <option value="210220">DESCONHECIDAS</option>
                        <option value="210240">NÃO REALIZADA</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <input id="c_filtro_dt_in" class="form-control class_inputs" type="date">
                </div>
                <div class="col-sm-2">
                    <input id="c_filtro_dt_fim" class="form-control class_inputs" type="date">
                </div>
                <div class="col-sm-1" style="padding: 0px;">
                    <button class="btn btn-success" style="width: 90%;" onclick="func_carrega_tab();">Filtrar</button>
                </div>
                <div class="col-sm-1" style="padding: 0px;">
                    <button class="btn btn-primary" style="width: 90%;" onclick="func_pdf_lista_nfe();">PDF</button>
                </div>
            </div>
        </div>



        <div id="box_tab1" class="row" style="border-color: grey; padding: 10px; background-color: white; overflow-x: hidden; width: 100%;">
            <input id="c_acao" type="hidden" value="">
            <input id="c_chave_01" type="hidden" value="">
            <input id="c_chave_02" type="hidden" value="">
            <input id="c_chave_03" type="hidden" value="">
            <input id="c_nfe_tipo" type="hidden" value="">
            <input id="c_db_emp" type="hidden" value="">
            <input id="c_id_tr" type="hidden" value="">
            <div class="box-body">
                <input type="hidden" id="vf_tab_sql_limit_in" value="0">
                <input type="hidden" id="vf_tab_btn_pag_select" value="1">
                <div id="btn_download"></div>

                <table id="tab1" class="table">
                    <thead style="font-weight: bold;">
                        <tr>
                            <th style="text-align: center;">Check</th>
                            <th>Data</th>
                            <th style="text-align: center;">NF-e</th>
                            <th>Chave</th>
                            <th>CNPJ/CPF</th>
                            <th style="text-align: center;">Itens</th>
                            <th style="text-align: right;">Manifesto</th>
                            <th>Valor R$</th>
                        </tr>
                    </thead>
                    <tbody id="tab1b" style="font-weight: normal;">

                    </tbody>
                </table>
            </div>
        </div>



        <div id="box_form_titulo" class="box" style="height: 60px; margin-top: 40px; background-color: white; border: none; overflow: hidden;">
            <div class="row">
                <div class="form-group col-sm-11">
                    <h3>Nota Fiscal Eletrônica ( NFe )</h3>
                </div>
                <div class="form-group col-sm-1">
                    <button id="btn_voltar" class="btn btn-danger" style="border-radius: auto; width: auto;" onclick="goBack();">X</button>
                </div>
            </div>
        </div>
        <div id="box_form_2" class="box" style="margin-top: 10px; height: auto; background-color: white; border: none; width: 100%;">
            <div class="box-body" style="height: auto; width: 100%;">

                <!-- Inicio do Danfe -->

                <div id="div_danfe" class="row " style="margin-top: 20px; margin-left: 20px; margin-right: 20px; width: 97%; border-radius: 10px; color: #34314c; font-size: 16px;">
                    <div id="div_danfe_titulo" class="row w-100">
                        <div class="col-sm-5" style="padding: 5px; height: 230px; border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px;">
                            <div class="row w-100" style="margin: 10px 0px 10px 0px; text-align: center; font-weight: bold; font-size: 16px; color: green;">
                                IDENTIFICAÇÃO DO EMITENTE
                            </div>
                            <div id="div_emit_razao_social" class="row w-100" style="padding: 5px; text-align: center; font-size: 22px; font-weight: bold; border-radius: 10px;">

                            </div>
                            <div id="div_emit_end1" class="row w-100" style="padding: 2px; text-align: center; font-size: 16px; border-radius: 10px;">

                            </div>
                            <div id="div_emit_end2" class="row w-100" style="padding: 2px; text-align: center; font-size: 16px; border-radius: 10px;">

                            </div>
                            <div id="div_emit_end3" class="row w-100" style="padding: 2px; text-align: center; font-size: 16px; border-radius: 10px;">

                            </div>
                        </div>

                        <div class="col-sm-2" style="padding: 25px; padding-top: 15px; height: 230px; border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px; border-left-style: none; border-right-style: none;">
                            <div class="col-sm-12">
                                <div class="row w-100" style="text-align: center; font-size: 22px; font-weight: bold; border-radius: 10px 5px; font-weight: bold;">
                                    DANFE
                                </div>
                                <div class="row w-100" style="padding-top: 8px; text-align: center; font-size: 13px; border-radius: 10px;">
                                    DOCUMENTO AUXILIAR DA NOTA FISCAL ELETRÔNICA
                                </div>
                                <div class="row w-100" style="padding-top: 10px; text-align: center; font-size: 14px; font-weight: bold; border-radius: 10px;">
                                    <div class="col-sm-9">
                                        <div class="row w-100" style="padding-left: 10px; text-align: left; font-size: 13px; font-weight: bold; border-radius: 14px;">0 Entrada</div>
                                        <div class="row w-100" style="padding-left: 10px; text-align: left; font-size: 13px; font-weight: bold; border-radius: 14px;">1 Saída</div>
                                    </div>
                                    <div id="div_danfe_tipo" class="col-sm-3" style="text-align: center; font-size: 20px; font-weight: bold; border-style: solid; border-width: 0.5px; border-color: gray;">

                                    </div>
                                </div>
                                <div id="div_danfe_num" class="row w-100" style="padding-top: 10px; text-align: center; font-size: 16px; font-weight: bold; border-radius: 10px;">

                                </div>
                                <div id="div_danfe_serie" class="row w-100" style="text-align: center; font-size: 16px; font-weight: bold; border-radius: 10px;">

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-5" style="padding: 5px; height: 230px; border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px;">
                            <div id="div_chave" class="row w-100" style="padding-top: 15px; padding-bottom: 10px;">
                                <div id="barcodeTarget" class="row w-100" style="border-style: none; margin: 0 auto; width: 50%;">
                                </div>
                            </div>
                            <div class="row w-100" style="margin: 10px; border-top-style: solid; border-top-width: 0.5px; border-top-color: gray;">
                            </div>
                            <div id="div_descr_NF-e_sefaz" class="row w-100" style="padding-top: 7px; text-align: center;font-size:18px; line-height: 1; border-radius: 10px;">
                                <p>Consulta de autenticidade no portal nacional da NF-e
                                <p>www.nfe.fazenda.gov.br/portal
                                <p>ou no site da Sefaz Autorizadora</p>
                            </div>
                        </div>
                    </div>

                    <div class="row w-100" style="height: 20px;">
                    </div>

                    <div id="div_Natureza-Protocolo" class="row w-100">
                        <div class="col-sm-7" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px;">
                            <div id="div_Natureza" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                NATUREZA DA OPERAÇÃO
                            </div>
                            <div id="div_emit_natureza" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-5" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_emit_prot_lab" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                PROTOCOLO DE AUTORIZAÇÃO DE USO
                            </div>
                            <div id="div_emit_prot_num" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                    </div>

                    <div class="row w-100" style="height: 20px;">
                    </div>

                    <div id="div_CNPJ-Inscricao_estadual" class="row w-100">
                        <div class="col-sm-4" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px;">
                            <div id="div_inscr_estad" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                INSCRIÇÃO ESTADUAL
                            </div>
                            <div id="div_emit_inscr_est" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-4" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_inscr_estad_ST" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                INSCRIÇÃO ESTADUAL DO SUBST. TRIBUT.
                            </div>
                            <div id="div_emit_inscr_est_sub_trib" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-4" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_cnpj" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                CNPJ
                            </div>
                            <div id="div_emit_cnpj" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                    </div>

                    <div class="row w-100" style="margin: 30px 0px 10px 5px; font-weight: bold; font-size: 16px; color: green;">
                        DESTINATÁRIO
                    </div>


                    <div id="div_nome_razao_social" class="row w-100">
                        <div class="col-sm-7" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px;">
                            <div id="div_titulo_razao_social" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                NOME / RAZÃO SOCIAL
                            </div>
                            <div id="div_dest_razao_social" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-3" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_CNPJ-CPF" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                CNPJ / CPF
                            </div>
                            <div id="div_dest_cnpj" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_emissao" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                DATA DE EMISSÃO
                            </div>
                            <div id="div_dest_dt_emit" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                    </div>

                    <div class="row w-100" style="height: 20px;">
                    </div>

                    <div id="div_enereco_Linha1" class="row w-100">
                        <div class="col-sm-6" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px;">
                            <div id="div_titulo_endereco" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                ENDEREÇO
                            </div>
                            <div id="div_dest_end" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                BAIRRO / DISTRITO
                            </div>
                            <div id="div_dest_bairro" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_CEP" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                CEP
                            </div>
                            <div id="div_dest_cep" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_entrada-saida" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                DATA DA SAÍDA
                            </div>
                            <div id="div_dest_dt_saida" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                    </div>


                    <div class="row w-100" style="height: 20px;">
                    </div>

                    <div id="div_enereco_Linha2" class="row w-100">
                        <div class="col-sm-5" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px;">
                            <div id="div_titulo_municipio" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                MINICÍPIO
                            </div>
                            <div id="div_dest_municip" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-1" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_UF" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                UF
                            </div>
                            <div id="div_dest_uf" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_Fone-Fax" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                FONE / FAX
                            </div>
                            <div id="div_dest_fone" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_IE_emitente" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                INSCRIÇÃO ESTADUAL
                            </div>
                            <div id="div_dest_ie" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_Hora_da_saída" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                HORA DA SAÍDA
                            </div>
                            <div id="div_dest_hr_saida" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                    </div>

                    <div class="row w-100" style="margin: 30px 0px 10px 5px; font-weight: bold; font-size: 16px; color: green;">
                        FATURA / DUPLICATA
                    </div>


                    <div id="div_valores_duplicata" class="row w-100">
                        <div id="div_duplicatas" class="col-sm-12" style="padding: 12px; border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px;">

                        </div>
                    </div>

                    <div class="row w-100" style="margin: 30px 0px 10px 5px; font-weight: bold; font-size: 16px; color: green;">
                        CALCULO DO IMPOSTO
                    </div>


                    <div id="div_calc_impostos_linha1" class="row w-100">
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px;">
                            <div id="div_titulo_BC_ICMS" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                BASE DE CÁLC. ICMS
                            </div>
                            <div id="div_calc_bc_icms" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_ICMS" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                VALOR ICMS
                            </div>
                            <div id="div_calc_icms" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_BC_ST" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                BASE DE CALC. ICMS ST
                            </div>
                            <div id="div_calc_bc_icms_st" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_ICMS_ST" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                VALOR DO ICMS ST
                            </div>
                            <div id="div_calc_icms_st" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">
                                R$ 0,00
                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_Imp_import" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                VLR ICMS DESON
                            </div>
                            <div id="div_calc_icms_dson" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_PIS" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                TOTAL PRODUTOS
                            </div>
                            <div id="div_calc_val_tt_prod" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                    </div>

                    <div class="row w-100" style="height: 20px;">
                    </div>

                    <div id="div_calc_impostos_linha2" class="row w-100">
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px;">
                            <div id="div_titulo_Tot_prod" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                VALOR FRETE
                            </div>
                            <div id="div_calc_frete" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_frete" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                VALOR SEGURO
                            </div>
                            <div id="div_calc_seguro" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_seguro" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                OUTRAS DESP.
                            </div>
                            <div id="div_calc_outras_desp" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_desconto" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                VALOR IPI
                            </div>
                            <div id="div_calc_ipi" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_out_depsesas" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                VALOR APROX. TRIB.
                            </div>
                            <div id="div_calc_val_aprox_trib" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_IPI" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                TOTAL DA NOTA
                            </div>
                            <div id="div_calc_val_tt_nota" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                    </div>

                    <div class="row w-100" style="height: 20px;">
                    </div>

                    <div id="div_calc_impostos_linha7" class="row w-100">
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px;">
                            <div id="div_titulo_COFINS" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                VALOR DESCONTO
                            </div>
                            <div id="div_calc_desconto" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_Tot_Nota" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                VALOR PIS
                            </div>
                            <div id="div_calc_pis" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_frete" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                VALOR COFINS
                            </div>
                            <div id="div_calc_cofins" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_seguro" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                VALOR FCP
                            </div>
                            <div id="div_calc_fcp" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_desconto" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">

                            </div>
                            <div id="div_desconto" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_out_depsesas" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">

                            </div>
                            <div id="div_out_despesas" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                    </div>

                    <div class="row w-100" style="margin: 30px 0px 10px 5px; font-weight: bold; font-size: 16px; color: green;">
                        TRANSPORTADORA / VOLUMES TRANSPORTADOS
                    </div>

                    <div id="div_transporte_linha1" class="row w-100">
                        <div class="col-sm-3" style="height: 100px; border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px;">
                            <div id="div_titulo_transportadora" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                NOME / RAZÃO SOCIAL
                            </div>
                            <div id="div_transp_razao_social" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-4" style="height: 100px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_Tipo_frete" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                FRETE POR CONTA
                            </div>
                            <div id="div_transp_frete_conta" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-1" style="height: 100px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_ANTT" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                CÓD. ANTT
                            </div>
                            <div id="div_transp_cod_antt" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-1" style="height: 100px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_placa_veic" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                PLACA
                            </div>
                            <div id="div_transp_placa" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-1" style="height: 100px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_UF_transp" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                UF
                            </div>
                            <div id="div_transp_placa_uf" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 100px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_IE_transp" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                CNPJ / CPF
                            </div>
                            <div id="div_transp_cnpj" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                    </div>

                    <div class="row w-100" style="height: 20px;">
                    </div>

                    <div id="div_transporte_linha2" class="row w-100">
                        <div class="col-sm-7" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px;">
                            <div id="div_titulo_transportadora" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                ENDEREÇO
                            </div>
                            <div id="div_transp_end" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_Tipo_frete" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                MUNICÍPIO
                            </div>
                            <div id="div_transp_cidade" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-1" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_ANTT" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                UF
                            </div>
                            <div id="div_transp_uf" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_placa_veic" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                INSCRIÇÃO ESTADUAL
                            </div>
                            <div id="div_transp_ie" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                    </div>

                    <div class="row w-100" style="height: 20px;">
                    </div>

                    <div id="div_transporte_linha3" class="row w-100">
                        <div class="col-sm-1" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px;">
                            <div id="div_titulo_Quantidade_transp" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                QUANT.
                            </div>
                            <div id="div_transp_quant" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-3" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_especie_trasnp" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                ESPÉCIE
                            </div>
                            <div id="div_transp_especie" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-3" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_marca_transp" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                MARCA
                            </div>
                            <div id="div_transp_marca" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-1" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_numeracao_transp" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                NUMERO
                            </div>
                            <div id="div_transp_num" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_peso_bruto_transp" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                PESO B.
                            </div>
                            <div id="div_transp_pb" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                        <div class="col-sm-2" style="height: 80px; border-style: solid; border-width: 0.5px; border-color: gray; border-left-style: none; border-radius: 10px;">
                            <div id="div_titulo_peso_liquido_transp" class="row w-100" style="margin: 10px 0px 0px 5px; font-weight: bold; font-size: 12px; color: black;">
                                PESO L.
                            </div>
                            <div id="div_transp_pl" class="row w-100" style="margin: 5px; margin-top: 10px; text-align: left;">

                            </div>
                        </div>
                    </div>

                    <div class="row w-100" style="margin: 30px 0px 10px 5px; font-weight: bold; font-size: 16px; color: green;">
                        DADOS DO PRODUTO / SERVIÇO
                    </div>

                    <table id="tab_itens" class="table" style="border: none; margin: 0px; font-size: 10px; table-layout: fixed; width: 100%">
                        <thead style="background-color: #DCDCDC; margin: 0px; padding: 0px;">
                            <tr>
                                <td class="col-md-1" style="border-radius: 10px; border: none;">COD</th>
                                <td class="col-md-1" style="border-radius: 10px; border: none;">NCM</th>
                                <td class="col-md-1" style="border-radius: 10px; border: none;">CST</th>
                                <td class="col-md-1" style="border-radius: 10px; border: none;">CFOP</th>
                                <td class="col-md-1" style="border-radius: 10px; border: none;">UN</th>
                                <td class="col-md-1" style="border-radius: 10px; border: none;">QUANT</th>
                                <td class="col-md-1" style="border-radius: 10px; border: none;">VLR UNID</th>
                                <td class="col-md-1" style="border-radius: 10px; border: none;">TOTAL</th>
                                <td class="col-md-1" style="border-radius: 10px; border: none;">BC ICMS</th>
                                <td class="col-md-1" style="border-radius: 10px; border: none;">ICMS</th>
                                <td class="col-md-1" style="border-radius: 10px; border: none;">IPI</th>
                                <td class="col-md-1" style="border-radius: 10px; border: none;">ALIQ ICMS</th>
                                <td class="col-md-1" style="border-radius: 10px; border: none;">ALIQ IPI</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_itens" style="font-size: 11px; color: black;">

                        </tbody>
                    </table>



                    <div class="row w-100" style="margin: 50px 0px 10px 5px; font-weight: bold; font-size: 16px; color: green">
                        DADOS ADICIONAIS
                    </div>

                    <div class="row">
                        <div class="col-sm-12" style="border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px; height: 120px;">
                            <textarea readonly class="form-control info" id="div_dados_adicionais" rows="20" style="background-color: white; margin: 5px; white-space: pre-wrap; overflow-wrap: break-word; border-style: none; line-height: 20px; max-height: 110px;"></textarea>
                        </div>
                    </div>



                    <div class="row w-100" style="margin: 50px 0px 10px 5px; font-weight: bold; font-size: 16px; color: #0000FF;">
                        ANÁLISES SOBRE A NFe
                    </div>

                    <div class="row">
                        <div class="col-sm-12" style="border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px; background-color: #F8F8FF;; height: 200px;">
                            <textarea readonly class="form-control info" id="div_obs" rows="20" style="margin: 5px; white-space: pre-wrap; overflow-wrap: break-word; border-style: none; line-height: 20px; background-color: #F8F8FF; max-height: 190px;"></textarea>
                        </div>
                    </div>



                    <!-- Fim do danfe -->
                    <div class="row w-100" style="height: 40px;">
                    </div>

                    <div class="row w-100">
                        <div class="col-sm-12" style="padding: 0px; margin-bottom: 5px; text-align: right;">
                            <button class="btn btn-primary btn-lg" style="margin: 0px; border-radius: 20px;" data-toggle="modal" data-target="#flipFlop">Inserir Análise NF-e</button>
                        </div>
                    </div>


                </div>
            </div>
        </div>




        <!-- The modal -->
        <div class="modal fade" id="flipFlop" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="modalLabel">Análises da NF-e</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row w-100" style="padding: 30px;">
                            <div class="row w-100" style="margin: 0px 0px 10px 5px; font-weight: bold; font-size: 16px; color: green">
                                ANOTAÇÕES SOBRE A ANÁLISE
                            </div>

                            <div class="row">
                                <div class="col-sm-12" style="border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px; height: 100px;">
                                    <textarea class="form-control info" id="c_coment" rows="20" style="text-transform: uppercase; background-color: white; margin: 5px; white-space: pre-wrap; overflow-wrap: break-word; border-style: none; line-height: 20px; max-height: 90px;"></textarea>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 10px; padding: 0px;">
                                <div class="col-sm-12" style="padding: 5px; padding-right: 0px; margin-top: 5px; margin-bottom: 10px; text-align: right;">
                                    <button class="btn-lg btn-success" style="border-radius: 20px;" onclick="func_salvar_obs('S');">Analisada com Sucesso</button>
                                    <button class="btn-lg btn-warning" style="border-radius: 20px;" onclick="func_salvar_obs('N');">Analisada com Ressalvas</button>
                                    <button class="btn-lg btn-danger" style="border-radius: 20px;" onclick="func_salvar_obs('P');">Não Lançada</button>
                                </div>
                            </div>


                            <div id="l_hist_analises" class="row w-100" style="margin: 50px 0px 10px 5px; font-weight: bold; font-size: 16px; color: green">
                                HISTÓRICO DE ANÁLISES
                            </div>

                            <div id="d_hist_analises" class="row">
                                <div class="col-sm-12" style="border-style: solid; border-width: 0.5px; border-color: gray; border-radius: 10px; height: 180px;">
                                    <textarea readonly class="form-control info" id="c_obs" rows="20" style="background-color: white; margin: 5px; white-space: pre-wrap; overflow-wrap: break-word; border-style: none; line-height: 20px; max-height: 170px;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <button onclick="func_pdf_lista_nfe();">TESTE</button> -->
</body>


<script type="text/javascript" src="../class/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="js/jquery-barcode.js"></script>
<script language="JavaScript">
    $(document).ready(function() {

        var v_date = new Date();
        v_date.setMonth(v_date.getMonth() + 1);
        var v_ano = v_date.getFullYear();
        var v_mes = (v_date.getMonth() + 1) < 10 ? "0" + (v_date.getMonth() + 1) : (v_date.getMonth() + 1);
        var v_dia = "01";
        v_filtro_dt = v_ano + "-" + v_mes + "-" + v_dia;
        $('#c_filtro_dt_fim').prop('max', v_filtro_dt);
        $('#c_filtro_dt_in').prop('max', v_filtro_dt);

        v_date = new Date();
        v_date.setMonth(v_date.getMonth() - 2);
        var v_ano = v_date.getFullYear();
        var v_mes = (v_date.getMonth() + 1) < 10 ? "0" + (v_date.getMonth() + 1) : (v_date.getMonth() + 1);
        var v_dia = "01";
        v_filtro_dt = v_ano + "-" + v_mes + "-" + v_dia;
        $('#c_filtro_dt_fim').prop('min', v_filtro_dt);
        $('#c_filtro_dt_in').prop('min', v_filtro_dt);

        v_date = new Date();
        var v_ano = v_date.getFullYear();
        var v_mes = (v_date.getMonth() + 1) < 10 ? "0" + (v_date.getMonth() + 1) : (v_date.getMonth() + 1);
        var v_dia = (v_date.getDate()) < 10 ? "0" + (v_date.getDate()) : (v_date.getDate());
        v_filtro_dt = v_ano + "-" + v_mes + "-" + v_dia;
        $("#c_filtro_dt_in").val(v_filtro_dt);

        v_date = new Date();
        var v_ano = v_date.getFullYear();
        var v_mes = (v_date.getMonth() + 1) < 10 ? "0" + (v_date.getMonth() + 1) : (v_date.getMonth() + 1);
        var v_dia = (v_date.getDate()) < 10 ? "0" + (v_date.getDate()) : (v_date.getDate());
        v_filtro_dt = v_ano + "-" + v_mes + "-" + v_dia;
        $("#c_filtro_dt_fim").val(v_filtro_dt);

        func_carrega_empresas();

        $("#box_tab_titulo").show();
        $("#box_tab_titulo2").show();
        $("#box_tab1").show();

        $("#box_form_titulo").hide();
        $("#box_form_2").hide();

    });



    document.getElementById('c_coment').onpaste = function() {
        return false;
    }


    
    function func_carrega_tab() {

        $("#c_acao").val("");
        var v_acao = "CARREGA_TAB";
        var v_empresa = $("#c_empresa").val();
        var v_filtro_dt_in = $("#c_filtro_dt_in").val();
        var v_filtro_dt_fim = $("#c_filtro_dt_fim").val();
        var v_filtro_analises = $("#c_filtro_analises").val();
        var v_filtro_manifesto = $("#c_filtro_manifesto").val();
        var v_tipo = 2; // ENTRADA

        $.ajax({
            type: "POST",
            url: "lib/lib_danfe_cons.php",
            data: {
                "v_acao": v_acao,
                "v_empresa": v_empresa,
                "v_tipo": v_tipo,
                "v_filtro_analises": v_filtro_analises,
                "v_filtro_manifesto": v_filtro_manifesto,
                "v_filtro_dt_in": v_filtro_dt_in,
                "v_filtro_dt_fim": v_filtro_dt_fim
            },
            success: function(data) {
                var options = '';
                var v_index = 0;
                var v_num_linhas = 0;
                $('#tab1').DataTable().destroy();
                $("#tab1b").empty();
                v_num_linhas = data[0].linhas;
                for (v_index = 0; v_index < data.length; v_index++) {
                    options += '<tr data-toggle="tooltip" data-placement="top" title="CNPJ: ' + data[v_index].cnpj + '\n' + 'RAZÃO SOCIAL: ' + data[v_index].razao_social_nfe + '" style="cursor: pointer;"><td id="tr_' + v_index + '" style="color: ' + data[v_index].tipo_check_cor + '; background-color: ' + data[v_index].tipo_check_bcor + '; text-align: center;"  onclick="func_cons_nfe(\'MODAL\',\'tr_' + v_index + '\',' + data[v_index].db_emp + ',' + data[v_index].ano + ',\'' + data[v_index].chave_01 + '\',\'' + data[v_index].chave_02 + '\',\'' + data[v_index].chave_03 + '\',' + data[v_index].nfe_tipo + ',' + data[v_index].cod_manifesto + ');">' + data[v_index].tipo_check_ico + '</td><td id="action_danfe' + v_index + '" onclick="func_cons_nfe(\'DANFE\',\'tr_' + v_index + '\',' + data[v_index].db_emp + ',' + data[v_index].ano + ',\'' + data[v_index].chave_01 + '\',\'' + data[v_index].chave_02 + '\',\'' + data[v_index].chave_03 + '\',' + data[v_index].nfe_tipo + ',' + data[v_index].cod_manifesto + ');">' + data[v_index].dt_emit + '</td><td style="text-align: center;" onclick="$(\'#action_danfe' + v_index + '\').click();">' + data[v_index].num_nfe + '</td><td onclick="$(\'#action_danfe' + v_index + '\').click();">' + data[v_index].chave + '</td><td onclick="$(\'#action_danfe' + v_index + '\').click();">' + data[v_index].cnpj + '</td><td style="text-align: center;" onclick="$(\'#action_danfe' + v_index + '\').click();">' + data[v_index].quant_prod + '</td><td onclick="$(\'#action_danfe' + v_index + '\').click();">' + data[v_index].vnf + '</td><td onclick="$(\'#action_danfe' + v_index + '\').click();">' + data[v_index].manifesto + '</td></tr>';
                }
                $('#tab1b').html(options);

                let table = $("#tab1").DataTable({
                    "language": {
                        "url": "../class/DataTables/portugues.json",
                    },

                    "lengthMenu": [
                        [30, 50, 100, 150, -1],
                        [30, 50, 100, 150, "Todos"]
                    ],
                    "order": true,
                    "order": [],
                    "autoWidth": false,
                    "columns": [{
                            data: "Check",
                            title: "Check",
                            Check: '10%'
                        },
                        {
                            data: "Data",
                            title: "Data",
                            width: '10%'
                        },
                        {
                            data: "NF-e",
                            title: "NF-e",
                            width: '10%'
                        },
                        {
                            data: "Chave",
                            title: "Chave",
                            width: '30%'
                        },
                        {
                            data: "CNPJ",
                            title: "CNPJ",
                            width: '10%'
                        },
                        {
                            data: "Itens",
                            title: "Itens",
                            width: '10%'
                        },
                        {
                            data: "Valor",
                            title: "Valor R$",
                            width: '10%'
                        },
                        {
                            data: "Manifesto",
                            title: "Manifesto",
                            width: '10%'
                        },
                    ],
                    "scrollY": "50vh",
                    "scrollX": true,
                    "scrollCollapse": true,
                    "retrieve": true,
                    "paging": true,
                });

                table.buttons().container().appendTo('#btn_download');

            },
            error: function(request, status, erro) {
                Swal.fire({
                    icon: "error",
                    title: "FALHA!",
                    text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                })
            }
        });
    }



    function func_carrega_empresas() {

        var v_acao = "LISTAR_EMPRESAS";
        $.ajax({
            type: "POST",
            url: "lib/lib_danfe_cons.php",
            data: {
                "v_acao": v_acao
            },
            success: function(data) {
                var options = '';
                $("#c_empresa").empty();
                for (v_index = 0; v_index < data.length; v_index++) {
                    options += "<option value='" + data[v_index].cnpj + "'>" + data[v_index].nome + "</option>";
                }
                $('#c_empresa').html(options);
            },
            error: function(request, status, erro) {
                Swal.fire({
                    icon: "error",
                    title: "FALHA!",
                    text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                })
            }
        });

    }



    function func_salvar_obs(js_resultado) {

        var v_acao = "SALVAR_OBS";
        var v_chave_01 = $("#c_chave_01").val();
        var v_chave_02 = $("#c_chave_02").val();
        var v_chave_03 = $("#c_chave_03").val();
        var v_nfe_tipo = $("#c_nfe_tipo").val();
        var v_db_emp = $("#c_db_emp").val();
        var v_resultado = js_resultado;
        var v_analise_texto = $("#c_coment").val();

        $.ajax({
            type: "POST",
            url: "lib/lib_danfe_cons.php",
            data: {
                "v_acao": v_acao,
                "v_chave_01": v_chave_01,
                "v_chave_02": v_chave_02,
                "v_chave_03": v_chave_03,
                "v_nfe_tipo": v_nfe_tipo,
                "v_db_emp": v_db_emp,
                "v_analise_texto": v_analise_texto,
                "v_resultado": v_resultado
            },
            success: function(data) {
                $('#flipFlop').modal('hide');
                var v_id_tr = $("#c_id_tr").val();
                if (v_resultado == "S") {
                    $("#" + v_id_tr).empty();
                    $("#" + v_id_tr).html("<i class=\"fa fa-check-square-o fa-2x\" aria-hidden=\"true\"></i>");
                    $("#" + v_id_tr).css("color", "#006400");
                    $("#" + v_id_tr).css("background-color", "#F0FFF0");
                } else if (v_resultado == "N") {
                    $("#" + v_id_tr).empty();
                    $("#" + v_id_tr).html("<i class=\"fa fa-exclamation-triangle fa-2x\" aria-hidden=\"true\"></i>");
                    $("#" + v_id_tr).css("color", "#B8860B");
                    $("#" + v_id_tr).css("background-color", "#FFFAF0");
                } else {
                    $("#" + v_id_tr).empty();
                    $("#" + v_id_tr).html("<i class=\"fa fa-minus-square fa-2x\" aria-hidden=\"true\"></i>");
                    $("#" + v_id_tr).css("color", "#C85A53");
                    $("#" + v_id_tr).css("background-color", "#FFF5EE");
                }
                $("#c_coment").val("");
                goBack();
            },
            error: function(request, status, erro) {
                Swal.fire({
                    icon: "error",
                    title: "FALHA!",
                    text: "Problema ocorrido: " + status + "\nDescição: " + erro + "\nInformações da requisição: " + request.responseText
                })
            }
        });
    }



    function func_cons_nfe(js_tipo_view, js_id_tr, js_db_emp, js_ano, js_chave_01, js_chave_02, js_chave_03, js_nfe_tipo, js_cod_manifesto) {

        var v_quant_prod = 0;
        // EMITENTE
        $("#div_emit_razao_social").html("");
        $("#div_emit_end1").html("");
        $("#div_emit_end2").html("");
        $("#div_emit_end3").html("");
        $("#div_danfe_tipo").html("");
        $("#div_danfe_num").html("");
        $("#div_danfe_serie").html("");
        generateBarcode(0);
        $("#div_emit_natureza").html("");
        $("#div_emit_prot_num").html("");
        $("#div_emit_inscr_est").html("");
        $("#div_emit_cnpj").html("");
        // DESTINATÁRIO
        $("#div_dest_razao_social").html("");
        $("#div_dest_cnpj").html("");
        $("#div_dest_dt_emit").html("");
        $("#div_dest_end").html("");
        $("#div_dest_bairro").html("");
        $("#div_dest_cep").html("");
        $("#div_dest_municip").html("");
        $("#div_dest_uf").html("");
        $("#div_dest_fone").html("");
        $("#div_dest_ie").html("");
        $("#div_dest_dt_saida").html("");
        $("#div_dest_hr_saida").html("");
        // CALCULO DO IMPOSTO
        $("#div_duplicatas").html("");
        $("#div_calc_bc_icms").html("");
        $("#div_calc_icms").html("");
        $("#div_calc_bc_icms_st").html("");
        $("#div_calc_icms_dson").html("");
        $("#div_calc_val_tt_prod").html("");
        $("#div_calc_frete").html("");
        $("#div_calc_seguro").html("");
        $("#div_calc_outras_desp").html("");
        $("#div_calc_ipi").html("");
        $("#div_calc_val_aprox_trib").html("");
        $("#div_calc_val_tt_nota").html("");
        $("#div_calc_desconto").html("");
        $("#div_calc_pis").html("");
        $("#div_calc_cofins").html("");
        $("#div_calc_fcp").html("");

        $("#div_transp_razao_social").html("");
        $("#div_transp_frete_conta").html("");
        $("#div_transp_cod_antt").html("");
        $("#div_transp_placa").html("");
        $("#div_transp_placa_uf").html("");
        $("#div_transp_cnpj").html("");
        $("#div_transp_end").html("");
        $("#div_transp_cidade").html("");
        $("#div_transp_uf").html("");
        $("#div_transp_ie").html("");
        $("#div_transp_quant").html("");
        $("#div_transp_especie").html("");
        $("#div_transp_marca").html("");
        $("#div_transp_num").html("");
        $("#div_transp_pb").html("");
        $("#div_transp_pl").html("");

        $("#div_dados_adicionais").html("");
        $("#c_obs").html("");
        $("#div_obs").html("");
        $("#tbody_itens").empty();

        var v_cod_manifesto = js_cod_manifesto;
        if (v_cod_manifesto > 0) {

            if ($BODY.hasClass('nav-md')) {
                $('#sidebar-menu').find('li.active ul').hide();
                $('body').toggleClass('nav-md nav-sm');
            }
            $('#menu_toggle').hide();



            var v_tipo_view = js_tipo_view;
            if (v_tipo_view == "MODAL") {
                $("#l_hist_analises").show();
                $("#d_hist_analises").show();
                $('#flipFlop').modal('show');
            } else {

                $("#l_hist_analises").hide();
                $("#d_hist_analises").hide();

                $("#box_tab_titulo").hide();
                $("#box_tab_titulo2").hide();

                $("#box_tab1").hide();

                $("#box_form_titulo").show();
                $("#box_form_2").show();
            }

            $("#c_coment").val("");

            var v_acao = "CONSULTAR_NFE";
            var v_db_emp = js_db_emp;
            var v_ano = js_ano;
            var v_chave_01 = js_chave_01;
            var v_chave_02 = js_chave_02;
            var v_chave_03 = js_chave_03;
            var v_nfe_tipo = js_nfe_tipo;
            var v_id_tr = js_id_tr;

            $("#c_chave_01").val(v_chave_01);
            $("#c_chave_02").val(v_chave_02);
            $("#c_chave_03").val(v_chave_03);
            $("#c_nfe_tipo").val(v_nfe_tipo);
            $("#c_id_tr").val(v_id_tr);

            $("#c_db_emp").val(v_db_emp);
            $.ajax({
                type: "POST",
                url: "lib/lib_danfe_cons.php",
                data: {
                    "v_acao": v_acao,
                    "v_chave_01": v_chave_01,
                    "v_chave_02": v_chave_02,
                    "v_chave_03": v_chave_03,
                    "v_nfe_tipo": v_nfe_tipo,
                    "v_db_emp": v_db_emp,
                    "v_ano": v_ano
                },
                success: function(data) {
                    var v_quant_prod = data[0].quant_prod;
                    // EMITENTE
                    $("#div_emit_razao_social").html(data[0].v_emit_razao_social);
                    $("#div_emit_end1").html(data[0].v_emit_end1);
                    $("#div_emit_end2").html(data[0].v_emit_end2);
                    $("#div_emit_end3").html(data[0].v_emit_end3);
                    $("#div_danfe_tipo").html("1");
                    $("#div_danfe_num").html("N°: " + data[0].v_danfe_num);
                    $("#div_danfe_serie").html("SÉRIE: " + data[0].v_danfe_serie);
                    generateBarcode(data[0].v_danfe_chave);
                    $("#div_emit_natureza").html(data[0].v_emit_natureza);
                    $("#div_emit_prot_num").html(data[0].v_emit_prot_num);
                    $("#div_emit_inscr_est").html(data[0].v_emit_inscr_est);
                    $("#div_emit_cnpj").html(data[0].v_emit_cnpj);
                    // DESTINATÁRIO
                    $("#div_dest_razao_social").html(data[0].v_dest_razao_social);
                    $("#div_dest_cnpj").html(data[0].v_dest_cnpj);
                    $("#div_dest_dt_emit").html(data[0].v_dest_dt_emit);
                    $("#div_dest_end").html(data[0].v_dest_end);
                    $("#div_dest_bairro").html(data[0].v_dest_bairro);
                    $("#div_dest_cep").html(data[0].v_dest_cep);
                    $("#div_dest_municip").html(data[0].v_dest_municip);
                    $("#div_dest_uf").html(data[0].v_dest_uf);
                    $("#div_dest_fone").html(data[0].v_dest_fone);
                    $("#div_dest_ie").html(data[0].v_dest_ie);
                    $("#div_dest_dt_saida").html(data[0].v_dest_dt_saida);
                    $("#div_dest_hr_saida").html(data[0].v_dest_hr_saida);
                    // CALCULO DO IMPOSTO
                    $("#div_duplicatas").html(data[0].v_duplicatas);
                    $("#div_calc_bc_icms").html("R$ " + data[0].v_calc_bc_icms);
                    $("#div_calc_icms").html("R$ " + data[0].v_calc_icms);
                    $("#div_calc_bc_icms_st").html("R$ " + data[0].v_calc_bc_icms_st);
                    $("#div_calc_icms_dson").html("R$ " + data[0].v_calc_icms_dson);
                    $("#div_calc_val_tt_prod").html("R$ " + data[0].v_calc_val_tt_prod);
                    $("#div_calc_frete").html("R$ " + data[0].v_calc_frete);
                    $("#div_calc_seguro").html("R$ " + data[0].v_calc_seguro);
                    $("#div_calc_outras_desp").html("R$ " + data[0].v_calc_outras_desp);
                    $("#div_calc_ipi").html("R$ " + data[0].v_calc_ipi);
                    $("#div_calc_val_aprox_trib").html("R$ " + data[0].v_calc_val_aprox_trib);
                    $("#div_calc_val_tt_nota").html("R$ " + data[0].v_calc_val_tt_nota);
                    $("#div_calc_desconto").html("R$ " + data[0].v_calc_desconto);
                    $("#div_calc_pis").html("R$ " + data[0].v_calc_pis);
                    $("#div_calc_cofins").html("R$ " + data[0].v_calc_cofins);
                    $("#div_calc_fcp").html("R$ " + data[0].v_calc_fcp);

                    $("#div_transp_razao_social").html(data[0].v_transp_razao_social);
                    $("#div_transp_frete_conta").html(data[0].v_transp_frete_conta);
                    $("#div_transp_cod_antt").html(data[0].v_transp_cod_antt);
                    $("#div_transp_placa").html(data[0].v_transp_placa);
                    $("#div_transp_placa_uf").html(data[0].v_transp_placa_uf);
                    $("#div_transp_cnpj").html(data[0].v_transp_cnpj);
                    $("#div_transp_end").html(data[0].v_transp_end);
                    $("#div_transp_cidade").html(data[0].v_transp_cidade);
                    $("#div_transp_uf").html(data[0].v_transp_uf);
                    $("#div_transp_ie").html(data[0].v_transp_ie);
                    $("#div_transp_quant").html(data[0].v_transp_quant);
                    $("#div_transp_especie").html(data[0].v_transp_especie);
                    $("#div_transp_marca").html(data[0].v_transp_marca);
                    $("#div_transp_num").html(data[0].v_transp_num);
                    $("#div_transp_pb").html(data[0].v_transp_pb);
                    $("#div_transp_pl").html(data[0].v_transp_pl);

                    $("#div_dados_adicionais").html(data[0].v_dados_adicionais);
                    $("#c_obs").html(data[0].v_analise_texto.replaceAll("<br>", "\n"));
                    $("#div_obs").html(data[0].v_analise_texto.replaceAll("<br>", "\n"));

                    var options = '';
                    $("#tbody_itens").empty();
                    for (v_index = 1; v_index < data.length; v_index++) {
                        options += '<tr style="border: none;"><td style="border: none;">' + data[v_index].v_item_cProd + '</td><td style="border: none;">' + data[v_index].v_item_NCM + '</td><td style="border: none;">' + data[v_index].v_item_CST + '</td><td style="border: none;">' + data[v_index].v_item_CFOP + '</td><td style="border: none;">' + data[v_index].v_item_uTrib + '</td><td style="border: none;">' + data[v_index].v_item_qCom + '</td><td style="border: none;">R$ ' + data[v_index].v_item_vUnCom + '</td><td style="border: none;">R$ ' + data[v_index].v_item_vProd + '</td><td style="border: none;">R$ ' + data[v_index].v_item_calc_icms + '</td><td style="border: none;">R$ ' + data[v_index].v_item_valor_icms + '</td><td style="border: none;">R$ ' + data[v_index].v_item_valor_ipi + '</td><td style="border: none;">' + data[v_index].v_item_aliq_icms + '</td><td style="border: none;">R$ ' + data[v_index].v_item_aliq_ipi + '</td></tr>';
                        options += '<tr style="margim-bottom: 20px; border: none;  border-bottom: 1px solid gray;"><td style="border: none; font-weight: bold;">' + 'DESCRIÇÃO: ' + '</td><td colspan="13" style="border: none; font-size: 13px;">' + data[v_index].v_item_xProd + '</td></tr>';
                    }
                    $('#tbody_itens').html(options);
                    // $('#tab_itens').css('height', '300px');

                    var v_div = $('#div_quantidade_transpx').css("height");
                    $('#div_especie_trasnpx').css("height", v_div);
                    $('#div_marca_transpx').css("height", v_div);
                    $('#div_numeracao_transpx').css("height", v_div);
                    $('#div_peso_bruto_transpx').css("height", v_div);
                    $('#div_peso_liquido_transpx').css("height", v_div);

                }
            });
        }
    }



    function goBack() {

        $('#sidebar-menu').find('li.active ul').show();
        $('body').toggleClass('nav-md nav-sm');
        $('#menu_toggle').show();

        $("#box_form_titulo").hide();
        $("#box_form_2").hide();
        $("#box_tab_titulo").show();
        $("#box_tab_titulo2").show();
        $("#box_tab1").show();
        $("#ds_code_operation_type").val(" ");

        $("#ds_code_operation_type").html("");
        $("#ds_company_issuer_name").html("");
        $("#ds_company_address").html("");
        $("#ds_company_neighborhood").html("");
        $("#ds_company_city_name").html("");
        $("#nl_invoice").html("");
        $("#ds_invoice_serie").html("");
        $("#ds_danfe").html("");
        $("#ds_transaction_nature").html("");
        $("#protocol_label").html("");
        $("#ds_protocol").html("");
        $("#nl_company_ie").html("");
        $("#nl_company_cnpj_cpf").html("");
        $("#ds_client_receiver_name").html("");
        $("#nl_client_cnpj_cpf").html("");
        $("#dt_invoice_issue").html("");
        $("#dt_input_output").html("");
        $("#ds_client_address").html("");
        $("#ds_client_neighborhood").html("");
        $("#nu_client_cep").html("");
        $("#ds_client_city_name").html("");
        $("#nl_client_phone_number").html("");
        $("#ds_client_uf").html("");
        $("#ds_client_ie").html("");
        $("#hr_input_output").html("");
        $("#tot_bc_icms").html("");
        $("#tot_icms").html("");
        $("#tot_bc_icms_st").html("");
        $("#tot_icms_fcp").html("");
        $("#vl_total_prod").html("");
        $("#vl_shipping").html("");
        $("#vl_insurance").html("");
        $("#vl_discount").html("");
        $("#vl_other_expense").html("");
        $("#tot_total_ipi_tax").html("");
        $("#vl_total_trib").html("");
        $("#vl_total").html("");
        $("#ds_transport_carrier_name").html("");
        $("#ds_transport_code_shipping_type").html("");
        $("#ds_transport_vehicle_uf").html("");
        $("#nl_transport_cnpj_cpf").html("");
        $("#ds_transport_address").html("");
        $("#ds_transport_city").html("");
        $("#ds_transport_uf").html("");
        $("#ds_transport_ie").html("");
        $("#nu_transport_amount_transported_volumes").html("");
        $("#ds_transport_type_volumes_transported").html("");
        $("#vl_transport_gross_weight").html("");
        $("#vl_transport_net_weight").html("");
        $("#ds_additional_information").html("");
        $("#c_obs").html("");
        $("#div_obs").html("");
    }





    function func_pdf_lista_nfe() {

        $("#c_acao").val("");
        var v_acao = "PDF_LISTA_NFE";
        var v_pdf_titulo = "Relatório Conferência - NFes Saídas"; // INSERINDO O TÍTULO
        var v_pdf_logo = "N"; // DESEJA INSERIR A LOGO DA EMPRESA ?
        var v_pdf_orientacao = "PAISAGEM"; // PAISAGEM OU RETRATO
        var v_empresa = $("#c_empresa").val();
        var v_empresa_rs = $("#c_empresa option:selected").text();
        var v_filtro_dt_in = $("#c_filtro_dt_in").val();
        var v_filtro_dt_fim = $("#c_filtro_dt_fim").val();
        var v_filtro_analises = $("#c_filtro_analises").val();
        var v_filtro_manifesto = $("#c_filtro_manifesto").val();
        var v_filtro_analises_info = $("#c_filtro_analises option:selected").text();
        var v_tipo = 2; // ENTRADA

        $.ajax({
            type: "POST",
            url: "lib/lib_danfe_cons.php",
            data: {
                "v_acao": v_acao,
                "v_pdf_titulo": v_pdf_titulo,
                "v_pdf_logo": v_pdf_logo,
                "v_pdf_orientacao": v_pdf_orientacao,
                "v_empresa": v_empresa,
                "v_empresa_rs": v_empresa_rs,
                "v_tipo": v_tipo,
                "v_filtro_analises": v_filtro_analises,
                "v_filtro_manifesto": v_filtro_manifesto,
                "v_filtro_analises_info": v_filtro_analises_info,
                "v_filtro_dt_in": v_filtro_dt_in,
                "v_filtro_dt_fim": v_filtro_dt_fim
            },
            complete: function() {
                window.open('../class/dompdf/pdf_fisco.php', '_blank');
            }
        });
    }




    function generateBarcode(js_val) {
        var value = js_val;
        var btype = 'codabar';
        var renderer = 'css';
        var quietZone = true;

        var settings = {
            output: renderer,
            bgColor: "#FFFFFF",
            color: "#000000",
            fontSize: 15,
            barWidth: 1,
            barHeight: 50,
            moduleSize: 5,
            posX: 10,
            posY: 20,
            addQuietZone: 1
        };

        value = {
            code: value,
            rect: true
        };
        $("#canvasTarget").hide();
        $("#barcodeTarget").html("").show().barcode(value, btype, settings);
    }
</script>



</html>