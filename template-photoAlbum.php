<?php require_once('Connections/cms.php'); ?>
<?php
//replace these 2 values!
$pageID = 15;
$albumID = 5;
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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

mysql_select_db($database_cms, $cms);
$query_Recordset1 = "SELECT * FROM photos WHERE albumID = ".$albumID." ORDER BY photoSequence ASC";
$Recordset1 = mysql_query($query_Recordset1, $cms) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_cms, $cms);
$query_currentPage = "SELECT * FROM cmsPages WHERE pageID = ".$pageID;
$currentPage = mysql_query($query_currentPage, $cms) or die(mysql_error());
$row_currentPage = mysql_fetch_assoc($currentPage);
$totalRows_currentPage = mysql_num_rows($currentPage);

mysql_select_db($database_cms, $cms);
$query_websiteInfo = "SELECT * FROM cmsWebsites WHERE websiteID = ".$websiteID;
$websiteInfo = mysql_query($query_websiteInfo, $cms) or die(mysql_error());
$row_websiteInfo = mysql_fetch_assoc($websiteInfo);
$totalRows_websiteInfo = mysql_num_rows($websiteInfo);
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
<link rel="stylesheet" type="text/css" href="styles/masonry.css"/>
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
<!-- InstanceBeginEditable name="mainContent" --><?php echo $row_currentPage['pageContent']; ?>

<!-- grid -->
  <div class="masonry js-masonry"  data-masonry-options='{ "isFitWidth": true }'>
    
  <?php do { ?>
  <div class="item">
      <div class="overlay-item">
        <div class="item-image"><img src="http://4siteusa.com/uploads/<?php echo $row_Recordset1['file_name']; ?>"></div>
        <?php if ($row_Recordset1['photoTitle'] != ''){ ?>
        <div class="item-title">
          <h2><?php echo $row_Recordset1['photoTitle']; ?></h2>
        </div>
        <?php 
		} 
		if ($row_Recordset1['photoDescription'] != ''){
		?>
		<p><?php echo $row_Recordset1['photoDescription']; ?></p>
        <?php 
		} 
		?>   
      </div>
    </div>
    <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
  </div>

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
<script type="text/javascript" src="scripts/masonry.pkgd.min.js"></script> 
<script type="text/javascript" src="scripts/imagesloaded.pkgd.min.js"></script>
<script>
$(document).ready(function() {
  // initiallize masonry
  var $container = $('.masonry').masonry();
  $container.imagesLoaded( function() {
    $container.masonry();
  });
});
</script>
<!-- InstanceEndEditable -->
<script type="text/javascript" src="scripts/herosJourney.js"></script>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($photos);

mysql_free_result($currentPage);

mysql_free_result($websiteInfo);
?>
