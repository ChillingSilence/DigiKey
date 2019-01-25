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
SOFTWARE.

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

    <title>DigiKey authentication</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
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
		<?php
		// We show the user their Digi-ID address, but clarify it's for this site
		// I don't have a real use for this at present but in-future we may need it so it's here for now
		// The theory being if we have two Johns who sign up at once we could always show the Admin the Digi-ID at the same time to avoid any ambiguity?
		// That said the user could just request that we "forget" their account, and sign up again using an additional identifier such as a surname if it was really an issue
		// It's fine for now though :)
		?>
            <p class="lead">Your Digi-ID for this site:<br /><?= $address ?></p>

            <p class="lead" style="margin-top: 40px">
		<?php
		if ($permissions[ispermitted] == 0) {
			echo "Your access request is pending approval by an administrator. Please ask your admin to approve / deny your request";
			}
		else if ($permissions[ispermitted] == 1) {
			// We only have the one "unlock" at present with unlock.php automatically firing the unlock mechanism, but this can easily be modified in future for further expansion / multiple doors
			// This could probably be done on this page itself, but I have a feeling that making it a new page will be better for extensibility in-future
			echo "<a href='unlock.php' class='btn btn-lg btn-default'>Unlock the door</a>";
			}
		else {
			// Presume the user has been rejected because anything other than 1 or 2 is a no-go
			echo "Sorry your request for permission has been denied.";
			}
		?>

            </p>

	<p class="lead" style="margin-top: 40px">
	<?php if ($permissions[isadmin] == 1) { echo "You're an admin! You can authorize additional users once they've performed an initial log-in.<br />"; } ?>
	<?php if ($permissions[isadmin] == 1) {
		$pending_requests = $user->get_pending_requests();
		print_r($pending_request);
		if (strlen($pending_request[fio] > 1)) {
			// There must be a user who wants access so we do them one at a time
			// We do it this way for two reasons:
			// #1 I'm lazy and can't be bothered making the code through all of them at once
			// #2 It looks better from a UI perspective at this point and again, I'm too lazy to fit it all in on one page
			echo "<br />Would you like to allow " . $pending_requests[fio] ." access?<br /><br />";
			// Authorize the user, could probably be done on this page but filler link for now
			echo "<a href='authorize.php' class='btn btn-lg'>Authorize</a>";
			// Reject the user, again could probably be done on this page but filler for now coz I'm editing things in the database
			echo "<a href='authorize.php' class='btn btn-lg btn-default'>Reject</a>";
			}
		}
		?>
          </div>

            <p class="lead" style="margin-top: 60px">
              <a href="logout.php" class="btn btn-lg">Logout</a> || <a href="forget.php" class="btn btn-lg">Forget my account</a>
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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

  </body>
</html>
