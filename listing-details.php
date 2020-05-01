<?php require_once('Connections/cms.php'); ?>
<?php
$pageID = -1;
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

$colname_listing = "-1";
if (isset($_GET['listingID'])) {
  $colname_listing = $_GET['listingID'];
}
mysqli_select_db($cms, $database_cms);
$query_listing = sprintf("SELECT * FROM listings  LEFT JOIN (SELECT photoAlbums.albumID,photoAlbums.coverPhotoID,photoAlbums.albumName,photos.id,photos.file_name FROM photoAlbums,photos WHERE photoAlbums.coverPhotoID=photos.id)  AS a ON listings.albumID=a.albumID  WHERE listingID = %s", GetSQLValueString($colname_listing, "int"));
$listing = mysqli_query($cms, $query_listing) or die(mysqli_error($cms));
$row_listing = mysqli_fetch_assoc($listing);
$totalRows_listing = mysqli_num_rows($listing);
$totalRows_photos = 0;

if ($row_listing['albumID'] != NULL){
    $query_photos = "SELECT * FROM photos WHERE albumID = ".$row_listing['albumID']." ORDER BY photoSequence ASC";
    $photos = mysqli_query($cms, $query_photos) or die(mysqli_error($cms));
    $row_photos = mysqli_fetch_assoc($photos);
    $totalRows_photos = mysqli_num_rows($photos);
}

$query_websiteInfo = "SELECT * FROM cmsWebsites WHERE websiteID = ".$websiteID;
$websiteInfo = mysqli_query($cms, $query_websiteInfo) or die(mysqli_error($cms));
$row_websiteInfo = mysqli_fetch_assoc($websiteInfo);
$totalRows_websiteInfo = mysqli_num_rows($websiteInfo);
?>
<?php
$pageTitle = 'Listing Details';
if ($row_listing['propertyLocation'] != '') $pageTitle = $row_listing['propertyLocation'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Listing Details |<?php echo $row_listing['propertyLocation']; ?></title>
<!-- InstanceEndEditable -->
<link rel="stylesheet" type="text/css" href="styles/wicked.css"/>
<link rel="stylesheet" type="text/css" href="styles/wicked-herosJourney.css"/>
<script type="text/javascript" src="scripts/modernizr.custom.js"></script>
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" type="text/css" href="styles/flickity.css"/>
"
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

  <!-- listing photos -->
  <?php
if ($totalRows_photos>0){
?>
  <div class="main-gallery">
    <?php do {
  list($width, $height) = getimagesize('http://4siteusa.com/uploads/'.$row_photos['file_name']);
  ?>
    <div class="gallery-cell" style="width:<?php echo $width ?>px; height:auto;"><img width="<?php echo $width ?>" height="<?php echo $height ?>" src="http://4siteusa.com/uploads/<?php echo $row_photos['file_name']; ?>"/></div>
    <?php } while ($row_photos = mysqli_fetch_assoc($photos)); ?>
  </div>
  <?php
}
?>
  <?php if ($row_listing['longDescription'] != ''){ ?>
  <p><?php echo $row_listing['longDescription']; ?></p>
  <?php } ?>
  <div class="wf_centered"><a href="contact.php?listingID=<?php echo $row_listing['listingID']; ?>" class="button">request more information</a></div>
  <?php if ($row_listing['interiorFeatures'] != ''){ ?>
  <hr />
  <h2 class="wf_centered">Interior Features</h2>
  <p><?php echo $row_listing['interiorFeatures']; ?></p>
  <?php } ?>
  <?php if ($row_listing['exteriorFeatures'] != ''){ ?>
  <hr />
  <h2 class="wf_centered">Exterior Features</h2>
  <p><?php echo $row_listing['exteriorFeatures']; ?></p>
  <?php } ?>
  <hr />
  <h2 class="wf_centered">Property Details</h2>
  <table class="wf_centered" style="margin-top:20px" border="0" align="center" cellpadding="5" cellspacing="0">
    <tbody>
      <?php if ($row_listing['propertyPrice'] != '' && $row_listing['propertyPrice'] != 0){ ?>
      <tr align="left" valign="top">
        <td height="22">Price:</td>
        <td height="22"><strong><?php echo "$".number_format($row_listing['propertyPrice'],0); ?></strong></td>
      </tr>
      <?php } ?>
      <?php if ($row_listing['propertyStatus'] != ''){ ?>
      <tr align="left" valign="top">
        <td height="22">Status:</td>
        <td height="22"><?php echo $row_listing['propertyStatus']; ?></td>
      </tr>
      <?php } ?>
      <?php if ($row_listing['propertyType'] != ''){ ?>
    <tr align="left" valign="top">
      <td height="22">Property Type:</td>
      <td height="22"><?php echo $row_listing['propertyType']; ?></td>
    </tr>
      <?php } ?>
      <?php if ($row_listing['propertyStyle'] != ''){ ?>
    <tr align="left" valign="top">
      <td height="22">Property Style:</td>
      <td height="22"><?php echo $row_listing['propertyStyle']; ?></td>
    </tr>
      <?php } ?>
      <?php if ($row_listing['mlsNumber'] != ''){ ?>
    <tr align="left" valign="top">
      <td height="22">MLS Number:</td>
      <td height="22"><?php echo $row_listing['mlsNumber']; ?></td>
    </tr>
      <?php } ?>
      <?php if ($row_listing['beds'] != ''){ ?>
    <tr align="left" valign="top">
      <td height="22">Beds:</td>
      <td height="22"><?php echo $row_listing['beds']; ?></td>
    </tr>
      <?php } ?>
      <?php if ($row_listing['fullBaths'] != ''){ ?>
    <tr align="left" valign="top">
      <td height="22">Full Baths:</td>
      <td height="22"><?php echo $row_listing['fullBaths']; ?></td>
    </tr>
      <?php } ?>
      <?php if ($row_listing['halfBaths'] != ''){ ?>
    <tr align="left" valign="top">
      <td height="22">Half Baths:</td>
      <td height="22"><?php echo $row_listing['halfBaths']; ?></td>
    </tr>
      <?php } ?>
    </tbody>
  </table>
  <div class="wf_centered"><a href="listings.php" class="button">back to the listings page</a></div>
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
<script src="scripts/flickity.pkgd.min.js"></script>
<script>
$('.main-gallery').flickity({
  // options
  cellAlign: 'center',
  contain: true,
  autoPlay: true,
  autoPlay: 3000,
  imagesLoaded: true,
  pageDots: false
});
jQuery(document).ready(function() {
	$('.main-gallery').flickity('reloadCells');
	$('.banner').css('margin-top','59px');
});
</script>
<!-- InstanceEndEditable -->
<script type="text/javascript" src="scripts/herosJourney.js"></script>
</body>
<!-- InstanceEnd --></html>
<?php
mysqli_free_result($listing);

mysqli_free_result($photos);

mysqli_free_result($websiteInfo);
?>
