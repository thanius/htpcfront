<!DOCTYPE html>

<?php
$REF = $_SERVER['REMOTE_ADDR'];
if (strpos($REF, '192.168.') === false && strpos($REF, 'localhost') === false && strpos($REF, '127.0.0.1') === false) {
    header("HTTP/1.1 403 Unauthorized");
    header("Location: 403.php");
}
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8">
    <title>HTPC front</title>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Oswald">
    <link rel="stylesheet" type="text/css" href="includes/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <!-- Include locale strings -->
    <script src="includes/locale.js" charset="UTF-8"></script>
    <!-- Page functions -->
    <script src="includes/functions.js" charset="UTF-8"></script>
  </head>
  <body>
    <?php ob_start(); require_once 'includes/functions.php'; preload_images(); ?>

    <!-- FADE-IN/MOUSE-DISABLER -->
    <div id="flashbang"></div>
    <div id="clickkiller"></div>

    <!-- SHUTDOWN DIALOG -->
    <div id="shutdown_container" class="fade-in">
      <div id="shutdown_dialog">
        <img src="images/htpcfront.png" alt="">
      </div>
    </div>

    <!-- MAIN AREA -->
    <div id="main" class="fade-in">

      <!-- PAGE SWITCH BUTTON (GOOGLE/CHANNELS) -->
      <div class="flip-container" id="google_button_container">
        <div class="flipper google">
          <div class="front">
            <img src="images/ggl_btn.png" class="fade-in" id="google_button" alt="">
          </div>
          <div class="back">
            <img src="images/chnl_btn.png" id="home_button" alt="">
          </div>
        </div>
      </div>

      <!-- POWEROFF/CONFIG BUTTONS -->
      <div class="flip-container" id="powerconfig_container">
        <div class="flipper powerconfig">
          <div class="front">
            <img src="images/powerbutton.png" id="powerbutton" alt="">
          </div>
          <div class="back">
            <img src="images/config_btn.png" id="config_button" alt="">
          </div>
        </div>
      </div>

      <!-- CONFIG DIALOG -->
      <div id="config_frame" class="config_frame fade-in">
        <div id="config_title"></div>
        <div id="config_contents">
          <?php check_config(); ?>
          <ul id="configbutton_cell_container">
            <?php draw_config_buttons(); ?>
          </ul>
        </div>

        <!-- SAVE CONFIG BUTTON -->
        <div id="save_container"><img src="images/floppy.png" class="fade-in" id="save_button" alt=""></div>
      </div>

      <!-- GOOGLE SEARCH PAGE -->
      <div id="google_frame" class="frame fade-in">
        <a href="http://www.google.com"><img src="images/google.png" id="google_logo" alt=""></a><br />
        <div id="search_div">
          <form method="post" name="google_search" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="query" id="google_search" value="" autofocus autocomplete="off">
            <input type="image" src="images/search.png" id="search_icon" alt="Google">
          </form>
        </div>
        <?php google_urlpost(); ?>
      </div>

      <!-- CHANNELS (FRONT) PAGE -->
      <div id="channels" class="frame fade-in">
        <?php draw_buttons(); ?>
      </div>

    </div>
  </body>
</html>
