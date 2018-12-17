 <!DOCTYPE html>
   <html>
   <head>
       <title></title>
   </head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="bootstrap/css/fontawesome-all.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/css/mdb.css">
    <link rel="stylesheet" href="bootstrap/css/style.css">
    <link rel="stylesheet" href="bootstrap/css/animate.min.css">
    <style type="text/css">
        .container{
            padding: 15px;
        }
        .video h2{
           font-size: 16px;
           font-weight: bolder; 
        }
        .video h6{
            font-size: 14px;
            font-weight: bolder; 
        }
        .table {
            height: 50px;
        }
        .table-style:hover{
         background-color:#00796b;
         cursor: pointer;
         color: white;
        }
        .text-center{
        height: 100px;
        }
        
    </style>
    <script type="text/javascript">
         <script src="bootstrap/js/fontawesome-all.js"></script>
         <script src="bootstrap/js/jquery-3.2.1.min.js"></script>
         <script src="bootstrap/js/popper.min.js"></script>
         <script src="bootstrap/js/bootstrap.min.js"></script>
         <script src="bootstrap/js/mdb.min.js"></script>
         <script src="bootstrap/js/wow.min.js"></script>
         <script src="js/wow.min.js"></script>
              <script>
              new WOW().init();
              </script>
    </script>


<?php
error_reporting(0);
require_once "config.php";
 
$arr_list = array();
if (array_key_exists('q', $_GET) && array_key_exists('max_result', $_GET) && array_key_exists('order', $_GET)) {
    $keyword = $_GET['q'];
    $format_keyword = implode("+", explode(" ", $keyword));
    $url = "https://www.googleapis.com/youtube/v3/search?q=$format_keyword&order=". $_GET['order'] ."&part=snippet&contentDetails&chart=mostPopular&type=video&client=firefox&maxResults=". $_GET['max_result'] ."&key=". $key;
    // print_r($url);
 
    if (array_key_exists('pageToken', $_GET)) 
    $url .= "&pageToken=". $_GET['pageToken'];
 
    $arr_list = getList($url);
}
 
function getList($api_url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $arr_result = json_decode($response);
    // echo "<pre>";
    // print_r($arr_result);
    if (isset($arr_result->items)) {
        return $arr_result;
    } elseif (isset($arr_result->error)) {
        echo "Something went wrong.";
    }
}
?>

<div class="container" style="margin-top: 10px">
<div class="row justify-content-center">
<div class="col-md-6" align="center" >
    <img src="img/logo.jpg"><br><br>
<form method="get" class="form-group animated flip">
    <div><input style="text-align: center;" class="form-control"type="text" autocomplete="true" name="q" placeholder="Enter keyword" 
        value="<?php if(array_key_exists('q', $_GET)) 
        echo $_GET['q']; ?>" required>
    </div>
    <div>
        <input style="text-align: center;" class="form-control" type="number" name="max_result" placeholder="Max Results" min="1" max="50" value="<?php if(array_key_exists('max_result', $_GET)) echo $_GET['max_result']; ?>" required></div>
    <div class="form-control">
        <?php $arr_orders = ['date', 'rating', 'relevance', 'title', 'viewCount']; ?>
        <select name="order" required>
            <option value="">--SELECT ORDER--</option>
            <?php 
            foreach ($arr_orders as $order) 
            { 
            ?>
            <option value="<?php echo $order; ?>" <?php if(array_key_exists('order', $_GET)&& ($order==$_GET['order'])) echo 'selected'; ?>><?php echo ucfirst($order); ?></option>
            <?php 
            }
            ?>
        </select>
    </div>
    <div><input class="btn btn-secondary" type="submit" value="Submit"></div>
</form>
</div>
</div>
</div>
<div class="container">
    <div class="row ">

<?php
if (!empty($arr_list)) {
    foreach ($arr_list->items as $item) {
       echo '<table class="col-lg-4 col-sm-6 col-xs-6 table table-style video"><tr class="text-center" >
                <td class="size">
                <iframe width="200" height="150" src="https://www.youtube.com/embed/'.$item->id->videoId.'"></iframe>
                    <h2>'. $item->snippet->title .'</h2>
                    <h6>'. $item->snippet->thumbnails->default->url .'</h6>
                    <h2>'. $item->snippet->publishedAt .'</h2>
                    </td>
                    </tr>
            </table>';
    }
    ?>
    </div>
</div>

<?php
    //append pagination url in query string and get token id.... 
    $url = "?q=". $_GET['q'] ."&max_result=". $_GET['max_result'] ."&order=". $_GET['order'];
    ?>
    <div align="center">
        <?php
    if (isset($arr_list->prevPageToken)) {
        echo '<a class="btn btn-primary href="'.$url.'&pageToken='.$arr_list->prevPageToken.'">Prev</a>';
    }
 
    if (isset($arr_list->nextPageToken)) {
        echo '<a class="btn btn-primary" href="'.$url.'&pageToken='.$arr_list->nextPageToken.'">Next</a>';
    }
    ?>
    </div>
    <?php  
}
?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <script type="text/javascript" src="https://ssl.gstatic.com/trends_nrtr/1671_RC04/embed_loader.js"></script> <script type="text/javascript"> trends.embed.renderTopChartsWidget("fc042c71-95b2-43ac-919c-f6bca31f39c5", {"geo":"GLOBAL","guestPath":"https://trends.google.com:443/trends/embed/"}, 2018); </script> 
</body>
</html>
