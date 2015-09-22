<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('max_execution_time', 0);    
require_once("includes/htmlDomParser.php");
//require_once("includes/databaseConnection.php");


function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}


?>
<html>
<head>
  <title>Search</title>
  <style type="text/css">
    #container{
      margin:0px;
      
    }
    #header{
      height:100px;
    }
    #body{

    }
    #footer{

    }
    #formContainer{
      margin-left:25%;
      margin-top:10%;    
    }
    #form{
      -moz-border-radius: 8px;
      -khtml-border-radius: 8px;
      -webkit-border-radius: 8px;
      border-radius: 8px;
      border: 2px solid gray;
      padding: 10px;
      width: 600px;
      height:150px;
      font-size: 11px;
      font-family: 'Lucida Grande',sans-serif;
      color: #333333;
      padding:10px;
    }
  </style>
</head>
<body>
  <!-- Container -->
  <div id="container">
    <!-- header -->
    <div id="header">
      <h2><img src="images/logo.png" /></h2>

    </div>
    <hr>
    <!-- Header End -->

    <!-- Body -->
    <div id="body">
      <div id="formContainer">
        <?php if(isset($_POST['search'])){
          $stateArray = array();
        

            for ($i = 5; $i <= 50; $i++){
			   
               echo $url = "https://www.zomato.com/ncr/breakfast?page=".$i;
			   echo "<br><hr><br>";
              $html = file_get_html($url);
              $linkArray = $html->find('li[class=resBB10 even  status1]');
             // print_r($linkArray);
              //echo $linkArray->text;
             
              foreach( $linkArray as $link ) {
               
                 $menuDiv = $link->find('div[class=search-result-links] a');
             
                 //echo $menuDiv[0]->href;
                 $resName = explode("/", $menuDiv[0]->href);
                 $menuPage = file_get_html(preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($menuDiv[0]->href)));
                 //echo $menuPage;
                 $scripts = $menuPage->find('script');
                 
                 foreach($scripts as $val) {

                    if (strpos($val->innertext,'zomato.menuPages') !== false) {
                        $data = explode("zomato.menuPages = ", $val->innertext);
                        $data2 = explode("zomato.menuTypes =", $data[1]);
                        $string = rtrim(trim($data2[0]) ,";");
                        $json = (json_decode($string,true));
                        echo $json[0]['url'];
                        echo "<br>".$resName[4];
                        if (!file_exists("images/".$resName[4])) {
                           if( mkdir("images/".$resName[4]) )
                           {
                            echo "<br>DIR CREATED </br>";
                           }else echo "ERROR CREATING DIRECTORY";
                        }else echo "Folder already present";
						
						
							foreach($json as $jsonObject){
								if (!file_exists('images/'.''.$resName[4].'/'.$jsonObject['filename'])) {
									file_put_contents('images/'.''.$resName[4].'/'.$jsonObject['filename'], file_get_contents($jsonObject['url']));
								}
							}
						
                        


                    }

                }
      

                 echo "<br><hr><br>";




               }

              }
               

       } else {?>
       <div id="form">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" >

          <br>
          <input type="submit" name="search" value="Search">
          <br>
        </form>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" >

          <br>
          <input type="submit" name="toCSV" value="Export">
          <br>
        </form>
        
      </div>
      <?php } ?>
    </div>

  </div>
  <!-- Body Ends -->

  <!-- Footer -->
  <div id="footer">


  </div>
  <!-- Footer Ends-->
</div>
<!-- Container Ends -->
</body>
</html>