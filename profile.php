<?php
include("config.php");
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: index.html');
}
$cur_usr = $_SESSION['id'];

$sql = "SELECT customer_id FROM login where id = '$cur_usr'";
$result = $conn->query($sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$cus_ID = $row['customer_id'];

$sql = "SELECT sum(invest) as invest FROM stock_order Where customer_id = '$cus_ID' AND types = 'BUY'";
$result = $conn->query($sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$buy = $row['invest'];

$sql = "SELECT sum(invest) as invest FROM stock_order Where customer_id = '$cus_ID' AND types = 'SELL'";
$result = $conn->query($sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$sell = $row['invest'];

$sql = "SELECT sum(stock) as stock FROM stock_order Where customer_id = '$cus_ID'";
$result = $conn->query($sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$stock = $row['stock'];

$sql = "SELECT iCent FROM login Where id = '$cur_usr'";
$result = $conn->query($sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$iCent = $row['iCent'];

$sql = "SELECT * FROM login ORDER by iCent DESC";
$result = $conn->query($sql);

$num_results = mysqli_num_rows($result);
$ranking = 1;



while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    if ($row['id'] == $cur_usr) {
        break;
    }
    $ranking += 1;
};

$url = "http://api.ipstack.com/183.106.199.185?access_key=89cacf58e2f03fca93a0ff7effa85c43";

$ch = curl_init();
// Return Page contents.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//grab URL and pass it to the variable.
curl_setopt($ch, CURLOPT_URL, $url);
$result = curl_exec($ch);
//echo $result;
$data = json_decode($result, true);
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="forProfile.css">
    <title>Stock World!</title>
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
                <li class="nav-item">
                    <a class="nav-link" href="stock.php">Investment
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
        <form method="post" action="image.php" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4">

                    <div class="profile-img">
                        <img src="uploads/profile.png" alt="" />
                        <div class="file btn btn-lg btn-primary">
                            Change Photo
                            <input type="file" onchange="this.form.submit()" name="fileToUpload" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="profile-head">
                        <h5>
                            <?php echo $cur_usr ?>
                        </h5>
                        <p class="proile-rating">RANKINGS : <span><?php echo $ranking . "/" . $num_results ?></span></p>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Login_Info</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="profile-work">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="tab-content profile-tab" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>User Id</label>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo $cur_usr ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Buy</label>
                                </div>
                                <div class="col-md-6">
                                    <p>$<?php echo $buy ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Sold</label>
                                </div>
                                <div class="col-md-6">
                                    <p>$<?php echo $sell ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Holding Stocks</label>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo $stock ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>iCent</label>
                                </div>
                                <div class="col-md-6">
                                    <p>$<?php echo $iCent ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>IP Address</label>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo $data['ip'] ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>City</label>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo $data['city'] ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Region</label>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo $data['region_name'] ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Zip</label>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo $data['zip'] ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Country</label>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo $data['country_name'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>