<?php
/********************************************
detect SQL injection php
ago/2010 - classe testada
********************************************/
class detectSQLinjection {
   //******************************************
   function ok() {
      return count($this->er)==0;
   }
   //******************************************
   function nEr($s) {
		//cho '<hr>'.count($this->er).' = '.$s;
		$this->er[count($this->er)] = $s;
	}
   //******************************************
   function detectSQLinjection($strSql) {
		$this->er = array();
      $proibido = array('UNION','INSERT','DELETE','DROP');
      $this->sq = strtoupper(trim($strSql));
      $p = strpos($this->sq.' ',' ');
      $this->cmd = trim(substr($this->sq,0,$p));
      $this->sq = trim(substr($this->sq,$p)).' ';
      //parse?
      $cb = false; $st = false; $p = ''; $fim = true;
      for ($i=0;$i<strlen($this->sq);$i++) {
         $c = $this->sq[$i];
			//cho "<hr>$c";
         if ($cb) {
            $cb = false;
         } else if ($c=="\\") {
            if (!$st) {
               $this->nEr("\\ fora de string");
            }
            $cb = true;
         } else if ($st) {
            if ($c=="'") {
               //fim string
               $st = false;
               $p = '';
            } else {
               //$p .= $c;
            }
         } else if (($c>="A" && $c<="Z")
               || ($c>="0" && $c<="9")
               || $c=='_'
               || $c=='.'
               ) {
            $p .= $c;
         } else {
            if ($c==";") {
               $this->nEr("; fora de string");
            } else if ($c=="'") {
               $st = true;
            } else if (strpos(" (),\t\r\n+-*=></",$c)===false) {
               $this->nEr("char $c invalido fora de string");
            }
            //testar palavra?
            for ($pp=0;$pp<count($proibido);$pp++) {
               if ($p==$proibido[$pp]) {
                  $this->nEr("palavra ".$p);
               }
            }
            if (substr($p,0,2)=='0X') {
               $this->nEr('hexa '.$p);
            }
            $p = '';
         }
      }
   }
}?>