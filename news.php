<?php
include("config.php");
session_start();

if(!isset($_SESSION['id'])){
  header('Location: index.html');
}

$sc = $_POST["val"];
$realTime = date('Y-m-d', strtotime(date("Y-m-d") . "-1 days"));
// From URL to get webpage contents.
$url = "http://newsapi.org/v2/everything?q=$sc&from=$realTime&to=$realTime&sortBy=popularity&apiKey=6bde99ea3e1a494c9d7fea109ecf6a25";
// Initialize a CURL session.
//$url = "http://newsapi.org/v2/everything?q=apple&from=2020-03-27&to=2020-03-27&sortBy=popularity&apiKey=6bde99ea3e1a494c9d7fea109ecf6a25";

$ch = curl_init();
// Return Page contents.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//grab URL and pass it to the variable.
curl_setopt($ch, CURLOPT_URL, $url);
$result = curl_exec($ch);

//echo $result;
$data = json_decode($result, true);

$dataSet = [
  $data['articles'][1]['title'], $data['articles'][1]['description'], $data['articles'][1]['url'], $data['articles'][1]['urlToImage'],
  $data['articles'][2]['title'], $data['articles'][2]['description'], $data['articles'][2]['url'], $data['articles'][2]['urlToImage'],
  $data['articles'][3]['title'], $data['articles'][3]['description'], $data['articles'][3]['url'], $data['articles'][3]['urlToImage']
];

?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <title>Hello, world!</title>
  <link rel="stylesheet" type="text/css" href="rest.css">
</head>

<body class="container">

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded">
    <a class="navbar-brand" href="#">
      <img src="/docs/4.0/assets/brand/bootstrap-solid.svg" width="30" height="30" class="d-inline-block align-top" alt="">
      Bootstrap
    </a> <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="news.php">Home</a><span class="sr-only">(current)</span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="stock.php">Investment</a>
        </li>
      </ul>
      <div class="nav-item dropdown ml-auto" id="navbarNav">
        <a class="nav-link dropdown-toggle " href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo $_SESSION['id']; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="profile.php">Profile</a>
          <a class="dropdown-item" href="logout.php">Logout</a>
          <a class="dropdown-item" href="drop.html">Delete Account</a>
        </div>
      </div>
    </div>
  </nav>

  <div class="container emp-profile">
    <div class="d-flex justify-content-center mt-4">
      <form class="form-inline" method="POST" action="news.php">
        <input class="form-control mr-sm-2" placeholder="What's up?" type="text" name="val">
        <button type="submit" class="btn btn-primary mr-2">Search</button>
      </form>
    </div>

    <div class="card text-center mt-4">
      <div class="card-header">
        Featured
      </div>
      <div class="card-body">
        <h5 class="card-title">Special stock treatment</h5>
        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
        <a href="stock.php" class="btn btn-primary">Buy Now</a>
      </div>
      <img class="card-img-bottom" src="images/gold.jpg" alt="Card image cap">
    </div>

    <div class="card-deck mt-4">
      <div class="card">
        <a href="<?php echo $dataSet[2] ?>">
          <img class="card-img-top" src="<?php echo $dataSet[3] ?>" alt=>
          <div class="card-body">
            <h5 class="card-title"><?php echo $dataSet[0] ?></h5>
        </a>
        <p class="card-text"><?php echo $dataSet[1] ?></p>
      </div>
    </div>
    <div class="card">
      <a href="<?php echo $dataSet[6] ?>">
        <img class="card-img-top" src="<?php echo $dataSet[7] ?>" alt=>
        <div class="card-body">
          <h5 class="card-title"><?php echo $dataSet[4] ?></h5>
      </a>
      <p class="card-text"><?php echo $dataSet[5] ?></p>
    </div>
  </div>
  <div class="card">
    <a href="<?php echo $dataSet[10] ?>">
      <img class="card-img-top" src="<?php echo $dataSet[11] ?>" alt=>
      <div class="card-body">
        <h5 class="card-title"><?php echo $dataSet[8] ?></h5>
    </a>
    <p class="card-text"><?php echo $dataSet[9] ?></p>
  </div>
  </div>
  </div>

  <div id="myModal" class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">COVID-19 Alert</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <img src="images/fingers.gif" alt="">
            <h1>DO THE FIVE</h1>
            <p><strong>Help stop coronavirus</strong></p>
            <p>1<strong> HANDS</strong> Wash them often</p>
            <p>2<strong> ELBOW</strong> Cough into it</p>
            <p>3<strong> FACE</strong> Don't touch it</p>
            <p>4<strong> SPACE</strong> Keep safe distance</p>
            <p>5<strong> HOME</strong> Stay if you can</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">Got it</button>
        </div>
      </div>
    </div>
  </div>
  </div>


  <!--Start of Tawk.to Script-->
  <script type="text/javascript">
    var Tawk_API = Tawk_API || {},
      Tawk_LoadStart = new Date();
    (function() {
      var s1 = document.createElement("script"),
        s0 = document.getElementsByTagName("script")[0];
      s1.async = true;
      s1.src = 'https://embed.tawk.to/5e599c36298c395d1cea5e1c/default';
      s1.charset = 'UTF-8';
      s1.setAttribute('crossorigin', '*');
      s0.parentNode.insertBefore(s1, s0);
    })();
  </script>
  <!--End of Tawk.to Script-->
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="modal.js"></script>
</body>

</html>