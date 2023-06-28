function pass_ext(pass){

    if(pass.length >= 3){
        return pass;
    }

    aux = "0";

    while((aux.length + pass.length) < 3){
        aux += "0";
    }

    aux += pass;

    return aux;
}

function send_com(com){

    if(com == "set"){
        set = document.getElementById('num_def').value;
        gc = getCookie("crr_gc");

        if(set != null && set != undefined && gc != null && gc != undefined){
            com = com + "&num=" + set + "&gc=" + gc;
        }
        else{
            return;
        }
    }

    if(com == "next" || com == "reset"){
        gc = getCookie("crr_gc");

        if(gc != null && gc != undefined){
            com = com + "&gc=" + gc;
        }
        else{
            return;
        }
    }

    var xmlhttp = new XMLHttpRequest();

    if(com == "get"){
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                hs = JSON.parse(this.responseText);

                if(pass_ext(hs[hs.length - 1].split("-")[0]) != document.getElementById("pass_l2").innerText){
                    var audio = new Audio('sound/doorbell-bingbong.wav');
                    audio.play();
                }

                document.getElementById("pass_l2").innerText = pass_ext(hs[hs.length - 1].split("-")[0]);
                if(parseInt(hs[hs.length - 1].split("-")[1]) == 0){
                    document.getElementById("gnum").innerText =  'Dispensação';
                }
                else if(parseInt(hs[hs.length - 1].split("-")[1]) == 1){
                    document.getElementById("gnum").innerText =  'Abertura de Processo';
                }
                else if(parseInt(hs[hs.length - 1].split("-")[1]) == 2){
                    document.getElementById("gnum").innerText =  'Assistente Social';
                }
                else if(parseInt(hs[hs.length - 1].split("-")[1]) == 3){
                    document.getElementById("gnum").innerText =  'Coordenação';
                }
                

                for(i = 0; i < document.getElementsByClassName('hi_pass').length; i++){
                    document.getElementsByClassName('hi_pass')[i].innerText = pass_ext(hs[hs.length  - (i + 2)].split("-")[0]);

                    if(parseInt(hs[i].split("-")[1]) == 0){
                        document.getElementsByClassName("hi_g")[hs.length  - (i + 2)].innerText = 'Dispensação';
                    }
                    else if(parseInt(hs[i].split("-")[1]) == 1){
                        document.getElementsByClassName("hi_g")[hs.length  - (i + 2)].innerText = 'Abertura de Processo';
                    }
                    else if(parseInt(hs[i].split("-")[1]) == 2){
                        document.getElementsByClassName("hi_g")[hs.length  - (i + 2)].innerText =  'Assistente Social';
                    }
                    else if(parseInt(hs[i].split("-")[1]) == 3){
                        document.getElementsByClassName("hi_g")[hs.length  - (i + 2)].innerText =  'Coordenação';
                    }

                }

            }
        };
    }

    xmlhttp.open("GET", "ctrl.php?com=" + com, true);
    xmlhttp.send();
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;

    if(charCode == 13){
        send_com("set");
        return false;
    }

    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }

    return true;
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function setCookie(name,value,seconds) {
    var expires = "";
    if(seconds){
        var date = new Date();
        date.setTime(date.getTime() + (seconds*1000));
        expires = "; expires=" + date.toUTCString();
    }

    if(value != null){
        document.cookie = name + "=" + value  + expires + "; path=/";
    }
    else{
        document.cookie = name + "=" + ""  + expires + "; path=/";
    }
    
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while(c.charAt(0)==' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }

    return null;
}

function eraseCookie(name) {   
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function fill_gc(gc){
    setCookie("crr_gc", gc, 1800);
    var com = "fill";

    if(gc != null && gc != undefined){
        com = com + "&gc=" + gc;
    }
    else{
        return;
    }

    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            if(this.responseText == "ok "){
                setCookie("crr_gc", gc, 1800);

                document.getElementById("gc_select_layer").setAttribute("style", "opacity: 0; z-index: 0");
                document.getElementById("painel_ctrl").setAttribute("style", "opacity: 1; z-index: 1");
            }
            else{
                console.log("Falha ao tentar configurar guiche: " + this.responseText);
            }
        }
    };

    xmlhttp.open("GET", "ctrl.php?com=" + com, true);
    xmlhttp.send();
}

function ssext(){
    var cg = getCookie("crr_gc");
    if(cg != null){
        fill_gc(cg);
    }
}

async function update() {
    if(window.location.href.endsWith("painel.php")){
        send_com("get");
    }
    else if(window.location.href.endsWith("controle.php")){
        var cook = getCookie("crr_gc");

        //Caso não seja encontrado nenhum cookie de sessão válido
        if(cook == null || cook == undefined){
            send_com("get");

            //Executando loop que obtem o status dos guiches
            for(var i = 0; i < document.getElementsByClassName('gcs').length; i++){
                com = "ic&gc=" + i;

                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var i = this.responseURL.split("=")[this.responseURL.split("=").length - 1];

                        if(this.responseText == "true "){
                            document.getElementsByClassName('gcs')[i].setAttribute("class", "gc_noselect gcs");
                            document.getElementsByClassName('gcs')[i].innerText = "Indisponível";
                            document.getElementsByClassName('gcs')[i].setAttribute("onclick", "");
                        }
                        else{
                            document.getElementsByClassName('gcs')[i].setAttribute("class", "gc_select gcs");
                            document.getElementsByClassName('gcs')[i].innerText = "Disponível";
                            document.getElementsByClassName('gcs')[i].setAttribute("onclick", "fill_gc(" + i + ");");
                        }
                    }
                };

                xmlhttp.open("GET", "ctrl.php?com=" + com, true);
                xmlhttp.send();
            }
        }

        //Caso seja encontrado um cookie de sessão válido o cookie é revalidado e a sessão é extendida
        else{
            ssext();
            send_com("get");
        }
    }

    await sleep(500);
    update();
}

function logout(){
    var com = "logout";
    var gc = getCookie("crr_gc");



    if(gc != null && gc != undefined){
        com = com + "&gc=" + gc;
    }
    else{
        return;
    }

    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("painel_ctrl").setAttribute("style", "opacity: 0; z-index: 0");
            document.getElementById("gc_select_layer").setAttribute("style", "opacity: 1; z-index: 5");

            eraseCookie("crr_gc");
        }
    };

    xmlhttp.open("GET", "ctrl.php?com=" + com, true);
    xmlhttp.send();

}
