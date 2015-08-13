<?php

// Preload images
function preload_images() {
  $directory = 'buttons/';
  $images = glob($directory . '*.png');

  echo '<div id="preloaded-images" style="position: absolute; overflow: hidden; left: -9999px; top: -9999px; height: 1px; width: 1px;">';
  foreach($images as $image) { echo '<img src="' . $image . '" width="1" height="1" alt="" />'; }

  $directory = 'images/';
  $images = glob($directory . '*.png');

  foreach($images as $image) { echo '<img src="' . $image . '" width="1" height="1" alt=""/>'; }
  echo '</div>';
}

// Check config file
function check_config() {
  $fp = fopen('includes/buttons.txt', 'a+');
  fclose($fp);
}

// Draw main page buttons
function draw_buttons() {
  $dataFromFile = file('includes/buttons.txt');
  $button_image = array();
  $button_url = array();
  $i = 0;

  foreach ($dataFromFile as $line) {
    if ($line != "\n" && $i < 20) {
      list($btn_img, $btn_url) = explode(';', $line, 2);
      $button_image[$i] = trim($btn_img);
      $button_url[$i] = trim($btn_url);
      $i=$i+1;
      $total_btns = $i;
    }
  }

  $i=0;
  $row_btn = 1;
  foreach ($button_image as $filename) {
    echo ("\n" . '<a href="' . $button_url[$i] . '"><img id="button_' . $i . '" class="button" src="buttons/' . $filename . '" alt=""></a>' . "\n");
    switch ($total_btns) {

      case "16":
      case "11":      
      case "12":
      case "8":
      case "7":
        if ($row_btn == 4) { echo ("<br />"); $row_btn=0; }
        break;

      case "6":
        if ($row_btn == 3) { echo ("<br />"); $row_btn=0; }
        break;

      case "4":
        if ($row_btn == 2) { echo ("<br />"); $row_btn=0; }
        break;

    }
    $i=$i+1;
    $row_btn = $row_btn+1;
  }
}

// Draw button placeholders for configuration page
function draw_config_buttons() {
  for($i=1;$i<=20;$i++) {
    echo('<li class="configbutton_cell ui-state-default grab" id="button' . $i . '_cell" style="display: none">
	    <div class="bkg">
              <div class="wrapper"><img src="images/remove_channel.png" class="remove_channel" alt=""></div>
  	      <form action="includes/upload.php" method="post" enctype="multipart/form-data" target="upload_frame" class="button_form" id="button' . $i . '_form">
  	        <div class="croparea config">
                  <label for="button' . $i . '_input" class="button_label">
		    <img class="addbutton" src="images/add_button.png" id="button' . $i .'_image" alt="">
		  </label>              
                </div>
	        <input class="config_input" type="text" id="button' . $i . '_url" name="button' . $i . '_url" value="">
	        <input type="file" name="button_upload" id="button' . $i . '_input" class="input_addbutton" required>
  	      </form>
	    </div>
	    <input type="hidden" class="button_input" name="button' . $i . '" id="button' . $i . '" value="">
	  </li>');
  }
}

// Remove funky characters and white spaces when checking for URL
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// When posting, clean test input and check for URL. If no URL can be detected, let Google handle it
function google_urlpost() {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = test_input($_POST["query"]);

    if (!preg_match("/^((https?|ftp)?:\/\/|www\.)?([-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]\.\w{2,4}$)/i",$query)) {
      header("location: http://www.google.com/search?q=$query");
    } else {
      $query_array=explode("/",$query);
      if (!preg_match("/(^https?|ftp:)/i",$query_array[0])) { $URL="http://" . $query; } else { $URL=$query; }
      header("location:" . $URL);
    }
  }
}

?>
