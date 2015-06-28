<?php ob_start(); ?>

<html>
<head>
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<style type="text/css">

@-webkit-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
@-webkit-keyframes fadeOut { from { opacity:1; } to { opacity:0; } }

.fade-in {
  opacity:0;
  -webkit-animation:fadeIn ease-in 1;
  -webkit-animation-fill-mode:forwards;
  -webkit-animation-duration:0.5s;
}

.fade-out {
  opacity:1;
  -webkit-animation:fadeOut ease-in 1;
  -webkit-animation-fill-mode:forwards;
  -webkit-animation-duration:0.5s;
}

.fade-in.main {
  -webkit-animation-delay: 3s;
  -webkit-animation-duration: 1s;
}

.main {
  width: 100%;
  height: 100%;
  position: relative;
  background-image:url("images/bkg.png");
  color: #fff;
  -webkit-transform-style: preserve-3d;
}

.frame {
  width: 100%;
  margin: auto;
  text-align: center;
  position: relative;
  top: 50%;
  transform: translateY(-50%);
  display: none;
}

.google_frame {
  width: 100%;
  margin: auto;
  text-align: center;
  position: relative;
  top: 50%;
  transform: translateY(-50%);
}

.google_logo {
  width:35%;
  padding-bottom: 2%;
}

body {
  background: #000;
  margin: 0px;
  overflow: hidden;
}

.button {
  border-style: solid;
  border-width: 2px;
  border-color: #fff;
  width: 18%;
  margin: 0.2%;
  -webkit-filter: brightness(0.5);
  -webkit-transition: all 0.35s ease-out;
  vertical-align: middle;
}

.button:hover {
  -webkit-filter: brightness(1.0);
  -webkit-transition: all 0.35s ease-in;
}

.hidden {
  display: none;
}

.blackness {
  background: #000;
  width: 100%;
  height: 100%;
}

.flip {
  -webkit-perspective: 5000;
  width: 100%;
  position: absolute;
  right: 0px;
  bottom: 0px;
}

.flip .google_card.flipped {
  -webkit-transform: rotatex(-180deg);
}

.flip .google_card {
  width: 100%;
  height: 0px;
  -webkit-transform-style: preserve-3d;
  -webkit-transition: 0.5s;
}

.flip .google_card .front {
  position: absolute;
  z-index: 1;
  cursor: pointer;
}
.flip .google_card .back {
  -webkit-transform: rotatex(-180deg);
  cursor: pointer;
}

.google_button {
  width: 4%;
  margin: 10px;
  right: 0px;
  bottom: 0px;
  position: fixed;
  -webkit-filter: grayscale(100%);
}

.google_search {
  width: 100%;
  height:100%;
  padding: 1.5%;
  font-size: 18pt;
  padding-right: 7.5%;
  padding-left: 3%;
  outline: 0px;
  border: 1px solid #fff;
  -webkit-border-radius: 10px;
}

.search_icon {
  max-width: 4%;
  position: absolute;
  right: 2%;
  outline: 0px;
  top: 50%;
  transform: translateY(-50%);
}

.search_div {
  width: 40%;
  height: 6%;
  position: relative;
  margin-left:  auto;
  margin-right: auto;
}

.preloaded-images {
   position: absolute;
   overflow: hidden;
   left: -9999px;
   top: -9999px;
   height: 1px;
   width: 1px;
}

</style>
</head>

<body>

  <!-- Preload all images -->
  <div class="preloaded-images">
    <img src="images/bkg.png" width="1" height="1" alt="" />
    <img src="images/ggl_btn.png" width="1" height="1" alt="" />
    <img src="images/google.png" width="1" height="1" alt="" />
    <img src="buttons/htpcfront.png" width="1" height="1" alt="" />
   
    <?php
      $directory = "buttons/";
      $images = glob($directory . "*.png");

      foreach($images as $image) { echo '<img src="$image" width="1" height="1" alt="" />'; }
    ?>

  </div>

  <div id="blackness" class="blackness">&nbsp;</div>

  <div id="main" class="fade-in main">

    <!-- GOOGLE SEARCH PAGE -->
    <div id="google_frame" class="frame fade-in">
      <img src="images/google.png" class="google_logo"><br />

      <div class="search_div">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <input type="text" name="query" class="google_search" value="" autofocus>
          <input type="image" src="images/search.png" class="search_icon">
        </form>
      </div>

      <?php
        function test_input($data) {
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          return $data;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $query = test_input($_POST["query"]);

          if (!preg_match("/^((https?|ftp)?:\/\/|www\.)?([-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]\.\w{2,4}$)/i",$query)) {
            $query = preg_replace('!\s+!', '+', $query);
            header("location: http://www.google.com/search?q=$query");
          } else {
            $query_array=explode("/",$query);
            if (!preg_match("/(^https?|ftp:)/i",$query_array[0])) { $URL="http://" . $query; } else { $URL=$query; }
            header("location:" . $URL);
          }
        }
      ?>
    </div>

    <!-- CHANNELS (FRONT) PAGE -->
    <div id="channels" class="frame fade-in">
      <?php
        $dataFromFile = file('buttons.txt');
        $button_image = array();
        $button_url = array();
        $i = 0;

        foreach ($dataFromFile as $line) {
          if ($line != "\n" && $i < 20) {
            list($btn_img, $btn_url) = explode(';', $line, 2);
            $button_image[$i] = $btn_img;
            $button_url[$i] = $btn_url;
            $i=$i+1;
          }
        }
      ?>

      <div class="button_container">
        <?php
          $i=0;
          foreach ($button_image as $filename) {
            echo "<a href=\"" . $button_url[$i] . "\" onmouseout=\"window.status=''\"><img id=\"button\" class=\"button\" src=\"buttons/" . $filename . "\"></a>";
            $i=$i+1;
          }
        ?>
      </div>
    </div>

    <!-- PAGE SWITCH BUTTON (GOOGLE/CHANNELS) -->
    <div class="flip">
      <div class="google_card">

        <div class="front">
          <img id="google_button" class="google_button" src="images/ggl_btn.png">
          <script>
            $("#google_button").click(function() {
              $("#channels").addClass("fade-out");

              setTimeout(function() {
                $("#channels").css("display","none");
                $("#google_frame").removeClass("fade-out").css("display","block");
              }, 550);

              $(".google_card").addClass("flipped");
            });
          </script>
        </div>

        <div class="back">
          <img id="home_button" class="google_button" src="images/chnl_btn.png">
          <script>
            $("#home_button").click(function() {
              $("#google_frame").addClass("fade-out");

              setTimeout(function() {
                $("#google_frame").css("display","none");
                $("#channels").removeClass("fade-out").css("display","block");
              }, 550);

              $(".google_card").removeClass('flipped')
            });
         </script>
       </div>
     </div>
   </div>
 </div>

<script>
$(document).ready(function() { 
    $("#blackness").fadeOut(500);
  setTimeout(function() {
    $("#google_frame").css("display","none");
    $("#channels").removeClass("fade-out").css("display","block");
  }, 1000);
});
</script>
</body>
</html>
