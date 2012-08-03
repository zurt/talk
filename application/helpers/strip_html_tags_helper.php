<?php 
function strip_html_tags($s , $keep = '' , $expand = 'script|style|noframes|select|option'){ 
        /**///prep the string 
        $s = ' ' . $s; 
        $k= array();

        /**///initialize keep tag logic 
        if(strlen($keep) > 0){ 
            $k = explode('|',$keep); 
            for($i=0;$i<count($k);$i++){ 
                $s = str_replace('<' . $k[$i],'[{(' . $k[$i],$s); 
                $s = str_replace('</' . $k[$i],'[{(/' . $k[$i],$s); 
            } 
        } 
        
        //begin removal 
        /**///remove comment blocks 
        while(stripos($s,'<!--') > 0){ 
            $pos[1] = stripos($s,'<!--'); 
            $pos[2] = stripos($s,'-->', $pos[1]); 
            $len[1] = $pos[2] - $pos[1] + 3; 
            $x = substr($s,$pos[1],$len[1]); 
            $s = str_replace($x,'',$s); 
        } 
        
        /**///remove tags with content between them 
        if(strlen($expand) > 0){ 
            $e = explode('|',$expand); 
            for($i=0;$i<count($e);$i++){ 
                while(stripos($s,'<' . $e[$i]) > 0){ 
                    $len[1] = strlen('<' . $e[$i]); 
                    $pos[1] = stripos($s,'<' . $e[$i]); 
                    $pos[2] = stripos($s,$e[$i] . '>', $pos[1] + $len[1]); 
                    $len[2] = $pos[2] - $pos[1] + $len[1]; 
                    $x = substr($s,$pos[1],$len[2]); 
                    $s = str_replace($x,'',$s); 
                } 
            } 
        } 
        
        /**///remove remaining tags 
        while(stripos($s,'<') > 0){ 
            $pos[1] = stripos($s,'<'); 
            $pos[2] = stripos($s,'>', $pos[1]); 
            $len[1] = $pos[2] - $pos[1] + 1; 
            $x = substr($s,$pos[1],$len[1]); 
            $s = str_replace($x,'',$s); 
        } 
        
        /**///finalize keep tag 
        for($i=0;$i<count($k);$i++){ 
            $s = str_replace('[{(' . $k[$i],'<' . $k[$i],$s); 
            $s = str_replace('[{(/' . $k[$i],'</' . $k[$i],$s); 
        } 
        
        return trim($s); 
    }
?>