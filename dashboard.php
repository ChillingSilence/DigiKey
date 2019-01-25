<?php
/*
Copyright (c) 2019 Josiah Spackman

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE
*/

session_start();
require_once dirname(__FILE__) . "/config.php";
require_once dirname(__FILE__) . "/classes/users.php";

// No user logged in
if (empty($_SESSION['user']['address']) || empty($_SESSION['user']['info']))
{
	header ('location: index.php');
	exit;
}

// Set variables with user info
$address = $_SESSION['user']['address'];
$user_info = $_SESSION['user']['info'];
$user = new token_user($_SESSION['user']['address']);

// Get current permission-levels, this needs to be done first before anything further
$permissions = $user->get_permissions();


// Request permissions
// Need to add if-statement here so it only does shit when they want to change
if ($permissions[ispermitted] == 0) {
	$user->requestaccess ($address);
	}




?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

    <title>Digi-ID demo site</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<?php if (DIGIID_GOOGLE_ANALYTICS_TAG != '') : ?><!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= DIGIID_GOOGLE_ANALYTICS_TAG ?>"></script>
    <script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date()); gtag('config', '<?= DIGIID_GOOGLE_ANALYTICS_TAG ?>');</script><?php endif ?>
  </head>

  <body>

    <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">

          <div class="masthead clearfix">
<!--            <div class="inner">
              <h3 class="masthead-brand">Cover</h3>
              <nav>
                <ul class="nav masthead-nav">
                  <li class="active"><a href="#">Home</a></li>
                </ul>
              </nav>
            </div>-->
          </div>

          <div class="inner cover">
            <h1 class="cover-heading">Hello, <?= $user_info['fio']; ?>!</h1>

            <p class="lead">Your address: <?= $address ?></p>

            <p class="lead" style="margin-top: 40px">
		<?php
		if ($permissions[ispermitted] == 0) { echo "<a href='dashboard.php' class='btn btn-lg btn-default'>Request access permission</a>"; }
		if ($permissions[ispermitted] == 1) { echo "You are currently pending access, please speak with the owner to have them grant permission"; }
		if ($permissions[ispermitted] == 2) { echo "<a href='dashboard.php' class='btn btn-lg btn-default'>Unlock the door</a>"; }
		else { echo "Sorry your request for permission has been denied."; }

		?>

            </p>

	<p class="lead" style="margin-top: 40px">
	<?php if ($permissions[isadmin] == 1) { echo "You're an admin! You can authorize additional users if they request permission once they're logged in.<br />"; } ?>
	<?php if ($permissions[isadmin] == 1) {
		$pending_requests = $user->get_pending_requests();
		echo "<br />Would you like to allow " . $pending_requests[fio] ." access?<br /><br />";
		echo "<a href='authorize.php' class='btn btn-lg'>Authorize</a>";
		echo "<a href='authorize.php' class='btn btn-lg btn-default'>Reject</a>";
		}
		?>
          </div>

            <p class="lead" style="margin-top: 60px">
              <a href="logout.php" class="btn btn-lg">Logout</a> || <a href="forget.php" class="btn btn-lg">Forget me</a>
                <?php if ($permissions[ispermitted] == 0) { echo " || <a href='request.php' class='btn btn-lg'>Request access</a>"; } ?>
            </p>


          <div class="mastfoot">
            <div class="inner">
              <p>Need help? <a href="https://t.me/DigiByteDevelopers">Ask in Telegram</a></p>
            </div>
          </div>

        </div>

      </div>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  </body>
</html>
