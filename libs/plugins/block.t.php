<?php
/**
* The block content is the text that should be translated. 
* Language must be set with define('LANGUAGE', $language_code);
*
* Any parameter that is sent to the function will be represented as %varname 
* in the translation text. The following parameters are reserved:
* - escape - sets escape mode:
* - 'html' for HTML escaping, this is the default.
* - 'js' for javascript escaping.
* - 'url' for url escaping.
* - 'off' turns off escaping.
* - plural - The plural version of the text.
* - count - The item count for plural mode.
*/
function smarty_block_t($params, $text, &$smarty) {
  global $msg;

  if (!isset($text))
    return $text;

  $msgId = md5($text);
  $text = stripslashes($text);
  
  // set escape mode
  $escape = 'html';
  if (isset($params['escape'])) {
    $escape = $params['escape'];
    unset($params['escape']);
  }

  // set plural version
  if (isset($params['plural'])) {
    $plural = $params['plural'];
    unset($params['plural']);

    // set count
    if (isset($params['count'])) {
      $count = $params['count'];
      unset($params['count']);
    }
  }

  // if language is set and langauge file exist then load
  if (defined('LANGUAGE')) {
    foreach ($smarty->getConfigDir() as $cofigDir)
      if (file_exists($cofigDir.'language_'.LANGUAGE.'.php')) 
        include_once $cofigDir.'language_'.LANGUAGE.'.php';
    
    if ((isset($count))and(isset($plural))) {
      // plural translation
      if (isset($msg[$msgId])) {
        if (isset($msg[$msgId]['condition'])) {
          eval('$index = '.str_replace('n', $count, $msg[$msgId]['condition']).';');
        } else {
          $index = $count > 1 ? 1 : 0;
        }
        if ((isset($msg[$msgId][$index]))and($msg[$msgId][$index] != ''))
          $text = $msg[$msgId][$index];
      } else {
        // use default plural
        if (($count > 1)and($plural != ''))
          $text = $plural;
      }
    } else {
      // regular translation
      if ((isset($msg[$msgId]))and($msg[$msgId] != ''))
        $text = $msg[$msgId];
    }
  }

  // udapte any '%varname' with its value
  foreach ($params as $key=>$value)
    $text = str_replace('%'.$key, $value, $text);

  switch ($escape) {
    case 'html':
      $text = nl2br(htmlspecialchars($text));
      break;
    case 'js':
      $text = str_replace('\'', '\\\'', stripslashes($text));
      break;
    case 'url':
      $text = urlencode($text);
      break;
  }

  return $text;
}
?>