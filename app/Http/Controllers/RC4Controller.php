<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

require_once( "../rc4.php" );

class RC4Controller extends Controller
{
  public static function encript(Request $request, Response $response) {
    $key = $request->key;
    if ($request->input("input-type-enc") == "enc_text") {
      $data = $request->input('text-data');
      $extension = "txt";
    } else {
      $file = $request->file('file-data');
      $data = File::get($file->getRealPath());
      $extension = explode(".", $file->getClientOriginalName())[sizeof(explode(".", $file->getClientOriginalName())) - 1];
    }
    if (!ctype_xdigit($key) || strlen($key) < 32 || strlen($key) > 256) {
      return response(['code'=>'-1', 'status'=>'fail', "error_message"=>"Key must be HEX character greater than 128 bits and less than 1024 bits!"], 200)
                    ->header('Content-Type', 'application/json; charset=ISO-8859-15');
    }
    $ciphertext = rc4( $key, $data );
    //save data;
    $filename = date('Y-m-d H:i:s') . "enc." . $extension;
    $my_file = '../uploads/' . $filename;
    $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
    fwrite($handle, $ciphertext);
    fclose($handle);
    $hex = bin2hex($ciphertext);
    return response(['code'=>0, 'status'=>'success', "filename"=>$filename, "data"=>substr($hex, 0, 1024)], 200)
                  ->header('Content-Type', 'application/json; charset=ISO-8859-15');
  }

  public static function decript(Request $request, Response $response) {
    $key = $request->key;
    if ($request->input("input-type-dec") == "dec_text") {
      $data = $request->input('text-data');
      $extension = "txt";
    } else {
      $file = $request->file('file-data');
      $data = File::get($file->getRealPath());
      $extension = explode(".", $file->getClientOriginalName())[sizeof(explode(".", $file->getClientOriginalName())) - 1];
    }
    $decrypted = rc4( $key, $data );
    //save data;
    $filename = date('Y-m-d H:i:s') . "dec."   . $extension;
    $my_file = '../uploads/' . $filename;
    $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
    fwrite($handle, $decrypted);
    fclose($handle);
    $hex = bin2hex($decrypted);
    return response(['code'=>0, 'status'=>'success', "filename"=>$filename, "data"=>substr($hex, 0, 1024)], 200)
                  ->header('Content-Type', 'application/json; charset=ISO-8859-15');
  }

  public static function download(Request $request, Response $response) {
    $filename = $request->filename;
    $file_path = '../uploads/'. $filename;
    if (file_exists($file_path))
    {
      // Send Download
      return response()->download($file_path, $filename, [
        'Content-Length: '. filesize($file_path)
      ]);
    }
    else
    {
      // Error
      exit('Requested file does not exist on our server!');
    }
  }

}
