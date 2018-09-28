<?php
   function rc4( $key_str, $data_str ) {
      // convert input string(s) to array(s)
      $key = array();
      $data = array();
      for ( $i = 0; $i < strlen($key_str); $i++ ) {
         $key[] = ord($key_str{$i});
      }
      for ( $i = 0; $i < strlen($data_str); $i++ ) {
         $data[] = ord($data_str{$i});
      }
     // prepare key
      $pointer_size = 16;
      $pool_size = pow(2, $pointer_size);
      $state = array();
      for ($i = 0; $i < $pool_size; $i++) {
            $state[$i] = $i;
      }
      $len = count($key);
      $index1 = $index2 = 0;
      for( $counter = 0; $counter < $pool_size; $counter++ ){
         $index2   = ( $key[$index1] + $state[$counter] + $index2 ) % 256;
         $tmp = $state[$counter];
         $state[$counter] = $state[$index2];
         $state[$index2] = $tmp;
         $index1 = ($index1 + 1) % $len;
      }
      // rc4
      $len = count($data);
      $x = $y = 0;
      for ($counter = 0; $counter < $len; $counter++) {
         $x = ($x + 1) % 256;
         $y = ($state[$x] + $y) % 256;
         $tmp = $state[$x];
         $state[$x] = $state[$y];
         $state[$y] = $tmp;
         $data[$counter] ^= $state[($state[$x] + $state[$y]) % 256];
      }
      // convert output back to a string
      $data_str = "";
      for ( $i = 0; $i < $len; $i++ ) {
         $data_str .= chr($data[$i]);
      }
      return $data_str;
   }
?>
