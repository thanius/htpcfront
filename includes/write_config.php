<?php
$config = str_replace(" ", "\n", $_GET['config']);
$config = preg_replace("(button\w{1,2}=)", "", $config);
$config_file = fopen('buttons.txt', 'w') or die('Unable to open file!');
if (! $config == file('buttons.txt')) {
  fwrite($config_file, $config);
  fclose($config_file);
}
?>
