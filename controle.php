<?php
    function str2boolArray($str){
        $drop = [];

        for($i = 0; $i < strlen($str); $i++){
            if($str[$i] == '1'){
                $drop[$i] = true;
            }
            else{
                $drop[$i] = false;
            }
        }

        return $drop;
    }

    function get_gc_state($p){
        $to = [];

        if(!file_exists("val.txt")){
            $f = fopen("val.txt", "w");
            fwrite($f, "0\n0\n0\n");
            fclose($f);
        }

        $f = fopen("val.txt", "r");
        $fsz = filesize("val.txt");

        $i = 0;
        while(ftell($f) < $fsz){
            $to[$i] = str_replace("\n", "", fgets($f, 3000));
            ++$i;
        }
        
        fclose($f);

        $now = new DateTime();
        if($to[$p] < $now->getTimestamp()){
            return false;
        }

        return true;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Controle</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="css/styles.css" rel="stylesheet"/>
        <script type="text/javascript" src="./js/js_ctrl.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div id="painel_ctrl">
            <div id="fk_header">
                <div id="bt_logout" onclick="logout()">Sair</div>
            </div>
            

            <div id="painel_adapt_layer">
                <div id="painel">
                    <div id="main_div_l1">
            
                        <div id="main_div_l2">

                            <div id="pass">
                                <p id="pass_l2">0002</p>
                            </div>

                            <div id="guiche">
                                <p>Guichê</p>
                                <p id="gnum">5</p>
                            </div>
                        </div>
                    
                    </div>

                    <footer>
                        <ul type="none">
                            <li>
                                <div id="sub_hi">
                                    <p class="hi_pass">0001</p>
                                    <p class="hi_g">Guichê: 5</p>
                                </div>
                            </li>

                            <li>
                                <div id="sub_hi">
                                    <p class="hi_pass">0001</p>
                                    <p class="hi_g">Guichê: 5</p>
                                </div>
                            </li>

                            <li>
                                <div id="sub_hi">
                                    <p class="hi_pass">0001</p>
                                    <p class="hi_g">Guichê: 5</p>
                                </div>
                            </li>

                            <li>
                                <div id="sub_hi">
                                    <p class="hi_pass">0001</p>
                                    <p class="hi_g">Guichê: 5</p>
                                </div>
                            </li>
                            
                        </ul>
                    </footer>
                </div>
            </div>
            


            <div id="sub_painel">
                <div class="ctrl_bt" id="next" onclick="send_com('next')">
                    <p class="noselect">Chamar próximo</p>
                </div>
    
                <div class="ctrl_bt" id="next" onclick="send_com('reset')">
                    <p class="noselect">Reiniciar</p>
                </div>
    
                <div class="ctrl_bt" id="next" onclick="send_com('set')">
                    <p class="noselect">Definir</p>
                </div>

                <input type="text" id="num_def" onkeypress="return isNumber(event)">
                
            </div>
            
        </div>
        
        <div id="gc_select_layer">
            <p>Selecione o guichê que irá trabalhar:</p>

            <div id="gc_list">

                <div class="gc_item">
                    <p class="gc_tlt">Dispensação</p>
                    <div class="gc_select gcs" onclick="fill_gc(0)">Disponível</div>

                </div>
            
                <div class="gc_item">
                    <p class="gc_tlt">Abertura de Processo</p>
                    <div class="gc_select gcs" onclick="fill_gc(1)">Disponível</div>
                </div>

                <div class="gc_item">
                    <p class="gc_tlt">Assistente Social</p>
                    <div class="gc_select gcs" onclick="fill_gc(2)">Disponível</div>
                </div>

                <div class="gc_item">
                    <p class="gc_tlt">Coordenação</p>
                    <div class="gc_select gcs" onclick="fill_gc(3)">Disponível</div>
                </div>

            </div>
        </div>


    </body>
</html>

<script language="javascript">
    var gc = getCookie("crr_gc");

    if(gc != null){
        document.getElementById("painel_ctrl").setAttribute("style", "opacity: 1");
        document.getElementById("painel_ctrl").setAttribute("style", "opacity: 1; z-index: 5");
    }
    else{
        document.getElementById("gc_select_layer").setAttribute("style", "opacity: 1");
        document.getElementById("gc_select_layer").setAttribute("style", "opacity: 1; z-index: 5");
    }

    
    update();
</script>