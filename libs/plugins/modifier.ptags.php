<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.ptags.php
 * Type:     modifier
 * Name:     ptags
 * -------------------------------------------------------------
 */
function smarty_modifier_ptags($string , $class=null)
{
  return preg_replace(
    '!^(.*)(?:\n|$)+!m',
    sprintf("<p%s>$1</p>\n", isset($class) ? ' class="'.$class.'"' : ''),
    $string
  );
}  
?>