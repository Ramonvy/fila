<!DOCTYPE html>
<html>
    <head>
        <title>Painel</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="css/styles2.css" rel="stylesheet"/>
        <script type="text/javascript" src="./js/js_ctrl.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body onclick="fr()">
        <div id="main_div_l1">
        
            <div id="main_div_l2">

                <div id="pass">
                    <p id="pass_l2">0002</p>
                </div>

                <div id="guiche">
                    <p id="gnum"></p>
                </div>
            </div>
        
        </div>

        <footer>
            <ul type="none">
                <li>
                    <div id="sub_hi">
                        <p class="hi_pass">0001</p>
                        <p class="hi_g"></p>
                    </div>
                </li>

                <li>
                    <div id="sub_hi">
                        <p class="hi_pass">0001</p>
                        <p class="hi_g"></p>
                    </div>
                </li>

                <li>
                    <div id="sub_hi">
                        <p class="hi_pass">0001</p>
                        <p class="hi_g"></p>
                    </div>
                </li>

                <li>
                    <div id="sub_hi">
                        <p class="hi_pass">0001</p>
                        <p class="hi_g"></p>
                    </div>
                </li>
                
            </ul>
        </footer>

    </body>
</html>

<script language="javascript">
    function fr(){
        var audio = new Audio('sound/doorbell-bingbong.wav');
        audio.play();
    }

    update();
</script>
