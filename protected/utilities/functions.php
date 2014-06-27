<?php
class functions {  

    public static function ufalt($text) {
        $search = array("Ç", "İ", "I", "Ğ", "Ö", "Ş", "Ü");
        $replace = array("ç", "i", "ı", "ğ", "ö", "ş", "ü");
        $text = str_replace($search, $replace, $text);
        $text = strtolower($text);
        return $text;
    }
     /**
     * Attach (or remove) multiple callbacks to an event and trigger those callbacks when that event is called.
     *
     * @param string $event name
     * @param mixed $value the optional value to pass to each callback
     * @param mixed $callback the method or function to call - FALSE to remove all callbacks for event
     */
    static function event($event, $value = NULL, $callback = NULL)
    {
        static $events;

        // Adding or removing a callback?
        if($callback !== NULL)
        {
            if($callback)
            {
                $events[$event][] = $callback;
            }
            else
            {
                unset($events[$event]);
            }
        }
        elseif(isset($events[$event])) // Fire a callback
        {
            foreach($events[$event] as $function)
            {
                $value = call_user_func($function, $value);
            }
            return $value;
        }
    }

    public static function lang_code(){
        $locale=Yii::app()->language;
        $res=explode('_',$locale);
        if ($res[0]) return $res[0];
        return $locale ;
    }

    public static function _lang_code(){
        echo functions::lang_code();
    }

     function delTree($dir) { 
        if (!file_exists($dir) and !is_dir($dir)) return false;
        $files = array_diff(scandir($dir), array('.','..')); 
        foreach ($files as $file) { 
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file"); 
        } 
        return rmdir($dir); 
    } 

    public static function save_base64_file($file_content,$filename,$folder,$extension=null){
         $parts=explode(',',$file_content);
         
         if ($parts[1]){

             if( $extension === null ){
                 $extension_props=explode('/', $parts[0]);
                 $extension_props=explode('+', $extension_props[1]);
                 $extension_props=explode(';', $extension_props[0]);
                 $extension='.'.$extension_props[0];
             } 
             else if($extension == false){
                $extension='';
             }

             $file=new file ( $filename.$extension ,$folder);
             $file->writeLine(base64_decode($parts[1]));
             $file->closeFile();

             return $file;
         }


    }

    public static function compressBase64Image($imagecontent, $sizeTrashold=20000, $quality=100, $pixelarea = 180000 ){
        $parts=explode(',',$imagecontent);
        $changeQuality="";
        if ( strlen($imagecontent) > $sizeTrashold || $quality <50) $quality=75;
        if ( $pixelarea <10000 || $pixelarea > 250000) $pixelarea = 180000;

        if ($parts[1]){
            $timestamp = time();
            $image_file=new file ('tmp.img.'.$timestamp,'/tmp');
            $image_file->writeLine($parts[1]);
            $command=' base64 -d '.$image_file->filepath . " | convert - -quality $quality - | convert -  -resize '".$pixelarea."@>' - |  base64 | cat > /tmp/compressed.img.".$timestamp ;
            shell_exec($command);
            $parts[1]= file_get_contents('/tmp/compressed.img.'.$timestamp);
            $image_file->closeFile();
            //unlink('/tmp/compressed.img.'.$timestamp);
            //unlink($image_file->filepath);
            return $imagecontent_new =implode(',',$parts);
        }
        return $imagecontent;
    }

    public static function get_random_string($length=44,$valid_chars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789")
    {
        // start with an empty random string
        $random_string = "";

        // count the number of chars in the valid chars string so we know how many choices we have
        $num_valid_chars = strlen($valid_chars);

        // repeat the steps until we've created a string of the right length
        for ($i = 0; $i < $length; $i++)
        {
            // pick a random number from 1 up to the number of valid chars
            $random_pick = mt_rand(1, $num_valid_chars);

            // take the random character out of the string of valid chars
            // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
            $random_char = $valid_chars[$random_pick-1];

            // add the randomly-chosen char onto the end of our string so far
            $random_string .= $random_char;
        }

        // return our finished random string
        return $random_string;
    }
    
    public static function new_id($length=44,$valid_chars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"){
        $unique=true;

        while ( $unique) {
        $new_id=functions::get_random_string($length,$valid_chars);
        $unique=Yii::app()->db->createCommand()
        ->select('id')
        ->from('ids')
        ->where('id=:id', array(':id'=>$new_id))
        ->queryRow();
       
            
        }
       return $new_id;

    }
    
    public static function uuid($serverID=81)
    {
        $t=explode(" ",microtime());
        return sprintf( '%04x-%08s-%08s-%04s-%04x%04x',
            $serverID,
            functions::clientIPToHex(),
            substr("00000000".dechex($t[1]),-8),   // get 8HEX of unixtime
            substr("0000".dechex(round($t[0]*65536)),-4), // get 4HEX of microtime
            mt_rand(0,0xffff), mt_rand(0,0xffff));
    }

    public static function uuidDecode($uuid) {
        $rez=Array();
        $u=explode("-",$uuid);
        if(is_array($u)&&count($u)==5) {
            $rez=Array(
                'serverID'=>$u[0],
                'ip'=>functions::clientIPFromHex($u[1]),
                'unixtime'=>hexdec($u[2]),
                'micro'=>(hexdec($u[3])/65536)
            );
        }
        return $rez;
    }

    public static function clientIPToHex($ip="") {
        $hex="";
        if($ip=="") $ip=getEnv("REMOTE_ADDR");
        $part=explode('.', $ip);
        for ($i=0; $i<=count($part)-1; $i++) {
            $hex.=substr("0".dechex($part[$i]),-2);
        }
        return $hex;
    }

    public static function clientIPFromHex($hex) {
        $ip="";
        if(strlen($hex)==8) {
            $ip.=hexdec(substr($hex,0,2)).".";
            $ip.=hexdec(substr($hex,2,2)).".";
            $ip.=hexdec(substr($hex,4,2)).".";
            $ip.=hexdec(substr($hex,6,2));
        }
        return $ip;
    }
}
