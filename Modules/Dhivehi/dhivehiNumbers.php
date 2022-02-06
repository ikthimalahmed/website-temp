<?php
/*
Author: Yoosuf Moosa
Email: 7465188@gmail.com
sample usage: <?php echo dhivehi(543.23); ?> outputs the Thaana text of it.
*/



function ChrW($input)
{
  $output = '';
  if (!is_array($input)) $input = array($input);
  foreach ($input as $char) {
    if ($char < 128) {
      $output.= chr($char);
    }
    elseif ($char < 2048) {
      $output.= chr(192 + (($char - ($char % 64)) / 64));
      $output.= chr(128 + ($char % 64));
    }
    else {
      $output.= chr(224 + (($char - ($char % 4096)) / 4096));
      $output.= chr(128 + ((($char % 4096) - ($char % 64)) / 64));
      $output.= chr(128 + ($char % 64));
    }
  }
  return $output;
}

//  ---------------------------------------

function othr($v){
  switch ($v*1)
  {
    case 1:
      $Result = ChrW(array(1927, 1964, 1927, 1968));
      break;
    case 2:
      $Result = ChrW(array(1931, 1964));
      break;
    case 3:
      $Result = ChrW(array(1932, 1960, 1922, 1968));
      break;
    case 4:
      $Result = ChrW(array(1920, 1958, 1932, 1958, 1923, 1962));
      break;
    case 5:
      $Result = ChrW(array(1930, 1958, 1936, 1968));
      break;
    case 6:
      $Result = ChrW(array(1920, 1958));
      break;
    case 7:
      $Result = ChrW(array(1920, 1958, 1932, 1968));
      break;
    case 8:
      $Result = ChrW(array(1927, 1958, 1921, 1968));
      break;
    case 9:
      $Result = ChrW(array(1922, 1962, 1928, 1958));
      break;
  }
  return $Result;
}

//  ---------------------------------------

function tens($v)
{
  switch ($v*1)
  {
    case 1:
    $Result = ChrW(array(1931, 1960, 1920, 1958));
    break;
    case 2:
    $Result = ChrW(array(1928, 1960, 1920, 1960));
    break;
    case 3:
    $Result = ChrW(array(1932, 1960, 1923, 1961, 1936, 1968));
    break;
    case 4:
    $Result = ChrW(array(1936, 1959, 1925, 1961, 1936, 1968));
    break;
    case 5:
    $Result = ChrW(array(1930, 1958, 1922, 1968, 1936, 1959, 1936, 1968));
    break;
    case 6:
    $Result = ChrW(array(1930, 1958, 1936, 1968, 1931, 1966, 1925, 1958, 1936, 1968));
    break;
    case 7:
    $Result = ChrW(array(1920, 1958, 1932, 1968, 1931, 1960, 1920, 1958));
    break;
    case 8:
    $Result = ChrW(array(1927, 1958, 1927, 1968, 1937, 1960, 1920, 1958));
    break;
    case 9:
    $Result = ChrW(array(1922, 1962, 1928, 1958, 1931, 1960, 1920, 1958));
    break;
  }
  return $Result;
}

//  ---------------------------------------

function e2t($v)
{
  switch ($v*1)
  {
    case 1:
    $Result = ChrW(array(1927, 1964, 1934, 1959, 1923, 1958));
    break;
    case 2:
    $Result = ChrW(array(1924, 1959, 1923, 1958));
    break;
    case 3:
    $Result = ChrW(array(1932, 1965, 1923, 1958));
    break;
    case 4:
    $Result = ChrW(array(1936, 1959, 1931, 1958));
    break;
    case 5:
    $Result = ChrW(array(1930, 1958, 1922, 1958, 1923, 1958));
    break;
    case 6:
    $Result = ChrW(array(1936, 1967, 1925, 1958));
    break;
    case 7:
    $Result = ChrW(array(1936, 1958, 1932, 1959, 1923, 1958));
    break;
    case 8:
    $Result = ChrW(array(1927, 1958, 1921, 1959, 1923, 1958));
    break;
    case 9:
    $Result = ChrW(array(1922, 1958, 1928, 1959, 1923, 1958));
    break;
    case 11:
    $Result = ChrW(array(1927, 1964, 1926, 1959, 1928, 1961, 1936, 1968));
    break;
    case 12:
    $Result = ChrW(array(1924, 1959, 1928, 1961, 1936, 1968));
    break;
    case 13:
    $Result = ChrW(array(1932, 1965, 1928, 1961, 1936, 1968));
    break;
    case 14:
    $Result = ChrW(array(1936, 1958, 1927, 1962, 1928, 1961, 1936, 1968));
    break;
    case 15:
    $Result = ChrW(array(1930, 1958, 1922, 1968, 1936, 1958, 1928, 1961, 1936, 1968));
    break;
    case 16:
    $Result = ChrW(array(1936, 1958, 1927, 1968, 1924, 1961, 1936, 1968));
    break;
    case 17:
    $Result = ChrW(array(1920, 1958, 1932, 1959, 1928, 1961, 1936, 1968));
    break;
    case 18:
    $Result = ChrW(array(1927, 1958, 1921, 1959, 1928, 1961, 1936, 1968));
    break;
    case 19:
    $Result = ChrW(array(1922, 1958, 1928, 1959, 1928, 1961, 1936, 1968));
    break;
  }
  return $Result;
}

//  ---------------------------------------

function getN($str, $n)
{
  $length = strlen($str);
  if ($n <= $length){
    return substr($str, $length - $n, 1);
  }
  else {
    return 0;
  }
}

//  ---------------------------------------

function getHun($v){
  $length = strlen($v);
  $re ='';
  
  if ($length == 1){
    $str = "00".$v;
  }
  if ($length == 2){
    $str = "0".$v;
  }
  if ($length == 3){
    $str = $v;
  }
  $g1 = getN($v, 1);
  $g2 = getN($v, 2);
  $g3 = getN($v, 3);
  if ($g3 != 2 && $g3 > 0){
    $hun = othr($g3);
  }
  else if ($g3 == 2){
    $hun = ChrW(array(1931, 1962, 1927, 1960));
  }
  if ($g3 == 2){
    $re = $hun.ChrW(array(1936, 1958, 1927, 1968, 1932, 1958))." ";
  }
  else if ($g3 > 0){
    $re = $re.$hun.ChrW(array(1936, 1958, 1932, 1965, 1926, 1958))." ";
  }
  if ($g1 == 0 && $g2 > 0 || $g1 > 0 && $g2 > 2){
    $re = $re.tens($g2)." ";
  }
  else if ($g2 > 0){
    $re = $re.e2t(substr($v, 1, 2) - 10)." ";
  }
  if ($g1 > 0 && $g2 > 2){
    $re = $re.othr($g1)." ";
  }
  else if ($g1 > 0 && $g2 == 0){
    $re = $re.othr($g1)." ";
  }
  if ($v > 10 && $v < 30 && $v != 20){
    $re = e2t($v - 10)." ";
  }
  return $re;
}

//  ---------------------------------------

function dv($str){
  $g1 = getN($str, 1);
  $g2 = getN($str, 2);
  $g3 = getN($str, 3);
  $g4 = getN($str, 4);
  $g5 = getN($str, 5);
  $g6 = getN($str, 6);
  $g7 = getN($str, 7);
  $g8 = getN($str, 8);
  $g9 = getN($str, 9);
  $g10 = getN($str, 10);
  $g11 = getN($str, 11);
  $g12 = getN($str, 12);

  $out = '';
  //1bn , 10bn, 100bn
  if ($g12 > 0 || $g11 > 0 || $g10 > 0){
    $out = $out.getHun($g12.$g11.$g10).ChrW(array(1924, 1960, 1933, 1960, 1927, 1958, 1922, 1968))." ";
  }
  //1mn, 10mn, 100mn
  if ($g9 > 0 || $g8 > 0 || $g7 > 0){
    $out = $out.getHun($g9.$g8.$g7).ChrW(array(1929, 1960, 1933, 1960, 1927, 1958, 1922, 1968))." ";
  }
  //100000
  if ($g6 > 0){
    $out = $out.othr($g6).ChrW(array(1933, 1958, 1927, 1968, 1926, 1958))." ";
  }
  //1000 10000
  if ($g4 > 0 || $g5 > 0){
    $out = $out.getHun($g5.$g4).ChrW(array(1920, 1959, 1936, 1968))." ";
  }
  //1 10 100
  $out = $out.getHun($g3.$g2.$g1);
  return $out;
}

//  ---------------------------------------

function dhivehi($v)
{
  $RL = explode(".", $v);
  $R = $RL[0];
  $L = $RL[1];
  $out = "";
  if (strlen($L) == 1){
    $L = $L."0";
  }
  if (strlen($L) == 0){
    $L = $L."00";
  }
  if ($R > 0){
    $out = dv($R).ChrW(array(1923, 1962, 1930, 1960, 1940, 1959));
  }
  else {
    $out = "-";
  }
  $out .= " / ";
  if ($L > 0){
    $out = $out.dv($L).ChrW(array(1933, 1959, 1923, 1960));
  }
  else {
    $out = $out."-";
  }
  return $out;
}

//  ---------------------------------------

function formatVal($v)
{
  $RL = explode(".", $v);
  $R = $RL[0];
  $L = $RL[1];
  if (strlen($L) == 1){
    $L = $L."0";
  }
  if (strlen($L) == 0){
    $L = $L."00";
  }
  return strrev(implode(",", str_split(strrev($R), 3))).".".$L;
}


function dhivehiNumWords($amount){
  $words = dv($amount);
  return $amount.' ('.trim($words).')';

}

function dhivehiTimeWords($amount){

  $words = dv($amount);

  return $amount.' ('.trim($words).')';

}
?>