<?php
    $com = $_REQUEST["com"];
    $filename = "crr.txt";
    

    function count_lines($name){
        $ff = fopen($name, "r");
        //$fsz = filesize($name);

        $lines = 0;
        while(!feof($ff)){
            fgets($ff, 10);
            $lines = $lines + 1;
        }

        fclose($ff);

        return $lines;
    }

    function remove_first_line($name){
        $ff = fopen($name, "r");
        $f2 = fopen("tmp.txt", "w");

        //$fsz = filesize($name);
        fgets($ff, 10);

        while(!feof($ff)){
            $str = fgets($ff, 10);
            fwrite($f2, $str);
            //fwrite($f2, "\n");
        }

        fclose($f2);
        fclose($ff);

        $ff = fopen("tmp.txt", "r");
        $f2 = fopen($name, "w");

        //$fsz = filesize($name);
        //fgets($ff, 10);

        while(!feof($ff)){
            $str = fgets($ff, 10);
            fwrite($f2, $str);
            //fwrite($f2, "\n");
        }

        fclose($f2);
        fclose($ff);
    }

    function update_h($next){
        $lines = count_lines("his.txt");
        
        while($lines >= 6){
            remove_first_line("his.txt");
            $lines = count_lines("his.txt");
        }

        $hist = "his.txt";
        $ff = fopen($hist, "a");
        
        fwrite($ff, $next);
        fwrite($ff, "\n");

        fclose($ff);
        
    }

    function indexOf($str, $c){
        $len = strlen($str);

        for($i = 0; $i < $len; $i++){
            if($str[$i] == $c){
                return $i;
            }
        }

        return -1;
    }

    function bool2str($bool){
        if($bool){
            return "true";
        }

        return "false";
    }

    function str2boolArray($str){
        $drop = [];

        $len = strlen($str);
        for($i = 0; $i < $len; $i++){
            if($str[$i] == '1'){
                $drop[$i] = true;
            }
            else{
                $drop[$i] = false;
            }
        }

        return $drop;
    }

    function boolArray2str($boolArray, $len){
        $drop = "";

        for($i = 0; $i < $len; $i++){
            if($boolArray[$i]){
                $drop = $drop . "1";
            }
            else{
                $drop = $drop . "0";
            }
        }

        return $drop;
    }

    function update_v($p){
        if(!file_exists("val.txt")){
            $f = fopen("val.txt", "w");
            fwrite($f, "0\n0\n0\n");
            fclose($f);
        }

        $to = [];

        $f = fopen("val.txt", "r");
        //$fsz = filesize("val.txt");

        $i = 0;
        while($i < 3){
            $to[$i] = str_replace("\n", "", fgets($f, 3000));
            ++$i;
        }
        
        fclose($f);

        $now = new DateTime();
        $to[$p] = $now->getTimestamp() + (10);

        $f = fopen("val.txt", "w");

        for($j = 0; $j < $i; $j++){
            fwrite($f, $to[$j]);
            fwrite($f, "\n");
        }

        fclose($f);
    }

    function logout_v($p){
        if(!file_exists("val.txt")){
            $f = fopen("val.txt", "w");
            fwrite($f, "0\n0\n0\n");
            fclose($f);
        }

        $to = [];

        $f = fopen("val.txt", "r");
        //$fsz = filesize("val.txt");

        $i = 0;
        while(!feof($f)){
            $to[$i] = str_replace("\n", "", fgets($f, 3000));
            ++$i;
        }
        
        fclose($f);

        $to[$p] = 0;

        $f = fopen("val.txt", "w");

        for($j = 0; $j < $i; $j++){
            fwrite($f, $to[$j]);
            fwrite($f, "\n");
        }

        fclose($f);
    }

    function get_gc_state($p){
        $to = [];

        if(!file_exists("val.txt")){
            $f = fopen("val.txt", "w");
            fwrite($f, "0\n0\n0\n");
            fclose($f);
        }

        $f = fopen("val.txt", "r");
        //$fsz = filesize("val.txt");

        $i = 0;
        while(!feof($f)){
            $to[$i] = str_replace("\n", "", fgets($f, 3000));
            ++$i;
        }
        
        fclose($f);

        $now = new DateTime();
        if($to[$p] < $now->getTimestamp()){
            return "false";
        }

        return "true";
    }




    if($com == "next"){
        $gc = $_REQUEST["gc"];

        $f = fopen($filename, "r");
        $fsz = filesize($filename);
        $crr = 0;

        if($fsz > 0){
            $str = fgets($f, 10);

            $crr = (int)substr($str, 0, indexOf($str, '-'));
            //echo strval($crr);
        }
        
        fclose($f);

        ++$crr;
        $f = fopen($filename, "w");
        $drop = strval($crr) . "-" . strval($gc);
        fwrite($f, $drop);
        fclose($f);

        update_h($drop);
    }
    else if($com == "reset"){
        $gc = $_REQUEST["gc"];

        $f = fopen($filename, "w");

        $drop = "1-" . strval($gc);
        update_h($drop);

        fwrite($f, $drop);
        fclose($f);

    }
    else if($com == "set"){
        $num = $_REQUEST["num"];
        $gc = $_REQUEST["gc"];

        if(strlen($num) > 4){
            $num = substr($num, 0, 4);
        }

        echo $num;
        $f = fopen($filename, "w");
        $drop = strval($num) . "-" . strval($gc);
        update_h($drop);

        fwrite($f, $drop);
        fclose($f);
    }
    else if($com == "get"){
        if(!file_exists("his.txt")){
            rename("tmp.txt", "his.txt");
        }

        $f = fopen("his.txt", "r");
        $fsz = filesize("his.txt");
        $crr = 0;

        $drop = array();
        while(!feof($f)){
            $crr = str_replace("\n", "", fgets($f, 10));

            //$drop = $drop . strval($crr);
            if($crr != null){
                array_push($drop, $crr);
            }
            
        }
        
        fclose($f);

        //$drop = "[" . substr($drop, 0, strlen($drop) - 1) . "]";
        //echo $drop;
        echo json_encode($drop);
    }
    else if($com == "fill"){
        $gc = $_REQUEST["gc"];

        if($gc != 5){
            update_v($gc);

            echo "ok";
        }
        else{
            echo "line 282 fail";
        }

    }
    else if($com == "logout"){
        logout_v($_REQUEST["gc"]);
    }

    //Retorna se o guiche está com um client ativo ou não
    else if($com == "ic"){
        echo get_gc_state($_REQUEST["gc"]);
    }

    else if($com == "json"){
        $arr = array ("1-0", "2-0", "3-0", "4-0", "5-0");

        echo json_encode($arr);
    }

?> 