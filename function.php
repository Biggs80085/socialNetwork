<?php
    function debug($var){
        echo '<br><br><br><pre>'.print_r($var, true).'</pre>';
    }

    function str_random($length){
        $alpha = "0123456789azertyuiopmlkjhgfdsqwxcvbn%!AZERTYUIOPMLKJHGFDSQWXCVBN";
        return substr(str_shuffle(str_repeat($alpha, $length)), 0, $length);
    }


    function old($date){
        $old = date('Y') - $date;
        if(date('md') < date('md', strtotime($date))){
            return $old - 1;
        }
        return $old;
    }

    function regExpName($name){
        if(!preg_match('/[a-zA-Z]/', $name)){
            return false;
        }
        return true;
    }

    function regExpPwd($pwd){
        if(!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,12}$/', $pwd)){
            return false;
        }
        return true;
    }

    function disDate($date){
        if(date('Y', strtotime($date)) > date('Y')){
            return strftime("%d %B %G", strtotime($date));
        }else{
            return strftime("%d %B %H:%M", strtotime($date));
        }
    }
