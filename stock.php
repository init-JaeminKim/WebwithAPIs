<?php
include("config.php");
session_start();
if(!isset($_SESSION['id'])){
  header('Location: index.html');
}
date_default_timezone_set("America/Chicago");

$sc = $_POST["val"];
$realTime = date("Y-m-d");
$cur_User = $_SESSION['id'];
// From URL to get webpage contents.
$url = "https://www.alphavantage.co/query?function=SYMBOL_SEARCH&keywords=$sc&apikey=JKVU5Z9EVVANZVU6&datatype=json";
// Initialize a CURL session.
$ch = curl_init();
// Return Page contents.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//grab URL and pass it to the variable.
curl_setopt($ch, CURLOPT_URL, $url);
$result = curl_exec($ch);

//echo $result;
$data = json_decode($result, true);
$symbol = array();
$company = array();
foreach ($data['bestMatches'] as $data => $value) {
  $symbol[] = $value['1. symbol'];
  $company[] = $value['2. name'];
}

//Get a symbol and name of companies

$url2 = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=$symbol[0]&apikey=JKVU5Z9EVVANZVU6";
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch2, CURLOPT_URL, $url2);
$result2 = curl_exec($ch2);
$data2 = json_decode($result2, true);

$current = $data2['Global Quote']['05. price'];
$open = $data2['Global Quote']['02. open'];
$high = $data2['Global Quote']['03. high'];
$low = $data2['Global Quote']['04. low'];
$change = $data2['Global Quote']['09. change'];
$percent = $data2['Global Quote']['10. change percent'];
$volume = $data2['Global Quote']['06. volume'];

$sql = "SELECT customer_id FROM login where id = '$cur_User'";
$result = $conn->query($sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$cus_ID = $row['customer_id'];

$sql = "SELECT iCent FROM login where id = '$cur_User'";
$result = $conn->query($sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$cur_iCent = $row['iCent'];
if (!empty($_POST['buy'])) {
  $unit = $_POST['buy'];
  $iCent = $current * $unit;

  if ($cur_iCent >= $iCent) {
    $sql = "INSERT INTO stock_order (customer_id, company, stock, types , invest) VALUES ('$cus_ID', '$company[0]', '$unit', 'BUY', '$iCent')";
    $conn->query($sql);

    $cur_iCent = $cur_iCent - $iCent;
    $sql = "UPDATE login SET iCent = '$cur_iCent' WHERE id = '$cur_User'";
    $conn->query($sql);
  } else {
    echo '<script language="javascript">';
    echo 'alert("NOT ENOUGH iCent!")';
    echo '</script>';
  }
  //UPDATE iCent, Number Format

} elseif (!empty($_POST['sell'])) {
  $unit = $_POST['sell'];
  $iCent = $current * $unit;

  $sql = "SELECT SUM(stock) AS stock FROM stock_order WHERE customer_id ='$cus_ID' AND company = '$company[0]' AND types='BUY'"; //where = buy
  $result = $conn->query($sql);
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  $cur_Stock = $row['stock'];

  //Compare holding stocks and process selling

  if ($cur_Stock >= $unit) {
    $sql = "INSERT INTO stock_order (customer_id, company, stock, types , invest) VALUES ('$cus_ID', '$company[0]', '$unit', 'SELL', '$iCent')";
    $conn->query($sql);

    $cur_iCent = $cur_iCent + $iCent;
    $sql = "UPDATE login SET iCent = '$cur_iCent' WHERE id = '$cur_User'";
    $conn->query($sql);
  } else {
    echo '<script language="javascript">';
    echo 'alert("NOT ENOUGH HOLDING STOCKS!")';
    echo '</script>';
  }
}

mysqli_close($conn);


?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <title>Stock World!</title>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
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
        <li class="nav-item">
          <a class="nav-link" href="news.php">Home</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="stock.php">Investment <span class="sr-only">(current)</span></a>
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
    <form class="form-inline" method="POST" action="stock.php">
      <input class="form-control mr-sm-2" placeholder="search" type="text" name="val" value="<?php echo $symbol[0] ?>" />
      <button type="submit" class="btn btn-secondary mr-2">Search</button>
      <input class="form-control mr-sm-2" placeholder="number of units" type="text" name="buy" />
      <button id="buy" type="submit" class="btn btn-success mr-2">Buy</button>
      <input class="form-control mr-sm-2" placeholder="number of units" type="text" name="sell" />
      <button id="sell" type="submit" class="btn btn-danger mr-2">Sell</button>
    </form>
  </div>


  <table class="table table-dark mt-4 rounded">
    <thead>
      <tr>
        <th scope="col ">Symbol</th>
        <td><?php echo $symbol[0] ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="col">Company</th>
        <td><?php echo $company[0] ?></td>
      </tr>
      <tr>
        <th scope="col">Open</th>
        <td>$<?php echo number_format((float) $current, 2, '.', ''); ?></td>
      </tr>
      <tr>
        <th scope="col">Open</th>
        <td>$<?php echo number_format((float) $open, 2, '.', ''); ?></td>
      </tr>
      <tr>
        <th scope="col">High</th>
        <td>$<?php echo number_format((float) $high, 2, '.', ''); ?></td>
      </tr>
      <tr>
        <th scope="col">Low</th>
        <td>$<?php echo number_format((float) $low, 2, '.', ''); ?></td>
      </tr>
      <tr>
        <th scope="col">Change</th>
        <td>$<?php echo number_format((float) $change, 2, '.', ''); ?></td>
      </tr>
      <tr>
        <th scope="col">Percent</th>
        <td><?php echo number_format((float) $percent, 2, '.', ''); ?>%</td>
      </tr>
      <tr>
        <th scope="col">Volume</th>
        <td><?php echo number_format($volume) ?></td>
      </tr>
    </tbody>
  </table>



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
  </div>
  <!--End of Tawk.to Script-->
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>