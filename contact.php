<?php require_once('Connections/cms.php'); ?>
<?php
$pageID = 13;
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_Recordset1 = "-1";
if (isset($_GET['listingID'])) {
  $colname_Recordset1 = $_GET['listingID'];
}
mysqli_select_db($cms, $database_cms);
$query_Recordset1 = sprintf("SELECT * FROM listings WHERE listingID = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysqli_query($cms, $query_Recordset1) or die(mysqli_error($cms));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_currentPage = "SELECT * FROM cmsPages WHERE pageID = ".$pageID;
$currentPage = mysqli_query($cms, $query_currentPage) or die(mysqli_error($cms));
$row_currentPage = mysqli_fetch_assoc($currentPage);
$totalRows_currentPage = mysqli_num_rows($currentPage);

$query_websiteInfo = "SELECT * FROM cmsWebsites WHERE websiteID = ".$websiteID;
$websiteInfo = mysqli_query($cms, $query_websiteInfo) or die(mysqli_error($cms));
$row_websiteInfo = mysqli_fetch_assoc($websiteInfo);
$totalRows_websiteInfo = mysqli_num_rows($websiteInfo);
?>
<?php
$emailSent = false;
$subject = '[Contact from '.$row_websiteInfo['url'].']';
$emailTo = $row_websiteInfo['emailAddress'];
$emailTo = 'kim@4siteusa.com';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = stripslashes(trim($_POST['name']));
    $email    = stripslashes(trim($_POST['email']));
    $phone    = stripslashes(trim($_POST['phone']));
    $message  = stripslashes(trim($_POST['message']));
    $pattern  = '/[\r\n]|Content-Type:|Bcc:|Cc:/i';
    if (preg_match($pattern, $name) || preg_match($pattern, $email) || preg_match($pattern, $subject)) {
        die("Header injection detected");
    }
    $emailIsValid = preg_match('/^[^0-9][A-z0-9._%+-]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $email);
    if($name && $email && $emailIsValid && $subject && $message){
        $body = "Nome: $name <br /><br /> Email: $email <br /><br /> phone: $phone";
		if ($totalRows_Recordset1 > 0) $body = $body."<br/><br/>Listing Inquiry For: ".$row_Recordset1['propertyLocation'];
		$body = $body." <br /><br /> message: $message";
        $headers  = 'MIME-Version: 1.1' . PHP_EOL;
        $headers .= 'Content-type: text/html; charset=utf-8' . PHP_EOL;
        $headers .= "From: $name <$email>" . PHP_EOL;
        $headers .= "Return-Path: $emailTo" . PHP_EOL;
        $headers .= "Reply-To: $email" . PHP_EOL;
        $headers .= "X-Mailer: PHP/". phpversion() . PHP_EOL;
        mail($emailTo, $subject, $body, $headers);
        $emailSent = true;
    } else {
        $hasError = true;
    }
}
?>
<?php
$pageTitle = $row_currentPage['pageTitle'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $row_websiteInfo['firstName']; ?> <?php echo $row_websiteInfo['lastName']; ?> | <?php echo $row_currentPage['pageTitle']; ?></title>
<!-- InstanceEndEditable -->
<link rel="stylesheet" type="text/css" href="styles/wicked.css"/>
<link rel="stylesheet" type="text/css" href="styles/wicked-herosJourney.css"/>
<script type="text/javascript" src="scripts/modernizr.custom.js"></script>
<!-- InstanceBeginEditable name="head" -->
<script type="text/javascript">
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
</script>
<!-- InstanceEndEditable -->
</head>

<body>

<!-- navigation -->
<div class="navigation">
  <div class="menu">
    <div id="trigger-overlay" type="button">MENU<br />
      <img src="images/menu.png" /></div>
  </div>
  <div class="navigationContent">
    <div class="navigationContentItem"><a href="index.php">Rocio Granados</a></div>
  </div>
</div>
<div class="banner"><div class="bannerContent"><h1 class="wf_centered"><?php echo $pageTitle ?></h1></div></div>
<div class="wf_container">

<!-- main content -->
<div class="mainContent">
<!-- InstanceBeginEditable name="mainContent" -->
<?php if ($emailSent == true) { echo '<br><br><p class="wf_centered">Thank you for your submission! Someone will get back to you shortly.</p>'; } else { ?>
<?php echo $row_currentPage['pageContent']; ?>
<hr />
<form class="wickedForm" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="contact-form" onsubmit="MM_validateForm('name','','R','email','','RisEmail','message','','R');return document.MM_returnValue">
<input type="text" id="name" name="name" placeholder=" name" required><br />
<input type="text" id="email" name="email" placeholder=" email address" required><br />
<input type="text" id="phone" name="phone" type="tel" placeholder=" phone number"><br />
<textarea rows="5" id="message" name="message" placeholder=" please enter your message here!" required></textarea><br />
<input name="submit" value="submit" type="submit" class="button" style="margin:0" />
</form>
<?php } ?>
<!-- InstanceEndEditable -->
</div>

<!-- footer -->
<div class="footer">
  <div class="wf_row">
    <div class="wf_column wf_two">
      <h2><a href="index.php">Home</a> | <a href="about.php">About Me</a> | <a href="listings.php">Listings</a> | <a href="search.php">Property Search</a> | <a href="localInfo.php">Local Info</a> | <a href="contact.php">Contact Me</a> | <a href="jewelry.php">Jewelry</a> | <a href="art.php">Rocio's Art</a></h2>
      <p>Copyright &copy; <?php echo $row_websiteInfo['firstName']; ?> <?php echo $row_websiteInfo['lastName']; ?> <?php echo date("Y"); ?>, All Rights Reserved.</p>
      <p>Web Design by <a href="http://www.4siteusa.com">4 Site</a>.</p>
    </div>
    <div class="wf_column wf_two wf_text_right"> <br class="wf_hideOnDesktop wf_hideOnTablet"/>
      <h2><?php echo $row_websiteInfo['companyName']; ?></h2>
      <p><?php echo $row_websiteInfo['iaddress']; ?></p>
      <?php if ($row_websiteInfo['iaddress2'] <> ''){ ?>
      <p><?php echo $row_websiteInfo['iaddress2']; ?></p>
      <?php } ?>
      <p><?php echo $row_websiteInfo['phoneNumber']; ?></p>
    </div>
  </div>
</div>

<!-- navigation -->
<div class="overlay overlay-hugeinc">
  <button type="button" class="overlay-close">Close</button>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="about.php">About Me</a></li>
      <li><a href="listings.php">Listings</a></li>
      <li><a href="search.php">Property Search</a></li>
      <li><a href="localInfo.php">Local Info</a></li>
      <li><a href="contact.php">Contact Me</a></li>
    </ul>
  </nav>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<!-- InstanceBeginEditable name="scripts" -->
<!-- InstanceEndEditable -->
<script type="text/javascript" src="scripts/herosJourney.js"></script>
</body>
<!-- InstanceEnd --></html>
<?php
mysqli_free_result($Recordset1);

mysqli_free_result($currentPage);

mysqli_free_result($websiteInfo);
?>
