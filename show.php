
<?php
// How many images should be shown on each apge?
$limit = 6;

// How many adjacent pages should be shown on each side?
$adjacents = 3;

// This is the name of file ex. I saved this file as index.php.
$targetpage = 'index.php';

// All iamges holder, defalut value is empty
$allImages = [];

// Target images directory
$image_dir = 'img/';
// Iterator over the directory
$dir = new DirectoryIterator(dirname(__FILE__).DIRECTORY_SEPARATOR.$image_dir);

// Iterator over the files and push jpg and png images to $allImages array.
foreach ($dir as $fileinfo) {
    if ($fileinfo->isFile() && in_array($fileinfo->getExtension(),array('jpg','png'))) {
      array_push($allImages,$image_dir.$fileinfo->getBasename());
    }
  }

// total number of images
$total_pages = count($allImages);

//how many items to show per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;

//  if no page var is given, set start to 0
$start = $page ?  (($page - 1) * $limit) : 0;


$images = array_slice( $allImages, $start, $limit );;

/* Setup page vars for display. */
if ($page == 0) $page = 1;                    //if no page var is given, default to 1.
$prev = $page - 1;                            //previous page is page - 1
$next = $page + 1;                            //next page is page + 1
$lastpage = ceil($total_pages/$limit);        //lastpage is = total pages / items per page, rounded up.
$lpm1 = $lastpage - 1;                        //last page minus 1

$pagination = "";
if($lastpage > 1)
{
  $pagination .= "<div class=\"pagination\">";
  //previous button
  if ($page > 1)
    $pagination.= "<a href=\"$targetpage?page=$prev\">Previous</a>";
  else
    $pagination.= "<span class=\"disabled\">Previous</span>";

  //pages
  if ($lastpage < 7 + ($adjacents * 2))    //not enough pages to bother breaking it up
  {
    for ($counter = 1; $counter <= $lastpage; $counter++)
    {
      if ($counter == $page)
        $pagination.= "<span class=\"current\">$counter</span>";
      else
        $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
    }
  }
  elseif($lastpage > 5 + ($adjacents * 2))    //enough pages to hide some
  {
    //close to beginning; only hide later pages
    if($page < 1 + ($adjacents * 2))
    {
      for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
      {
        if ($counter == $page)
          $pagination.= "<span class=\"current\">$counter</span>";
        else
          $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
      }
      $pagination.= "...";
      $pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
      $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";
    }
    //in middle; hide some front and some back
    elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    {
      $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
      $pagination.= "<a href=\"$targetpage?page=2\">2</a>";
      $pagination.= "...";
      for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
      {
        if ($counter == $page)
          $pagination.= "<span class=\"current\">$counter</span>";
        else
          $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
      }
      $pagination.= "...";
      $pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
      $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";
    }
    //close to end; only hide early pages
    else
    {
      $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
      $pagination.= "<a href=\"$targetpage?page=2\">2</a>";
      $pagination.= "...";
      for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
      {
        if ($counter == $page)
          $pagination.= "<span class=\"current\">$counter</span>";
        else
          $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
      }
    }
  }

  //next button
  if ($page < $counter - 1)
    $pagination.= "<a href=\"$targetpage?page=$next\">Next</a>";
  else
    $pagination.= "<span class=\"disabled\">Next</span>";
  $pagination.= "</div>\n";
}

echo '<link rel="stylesheet" type="text/css" href="style.css">';
echo '<link rel="stylesheet" type="text/css" href="ins-imgs.css">';
echo '<ul class="ins-imgs">';

if(count($images) > 0) {
  foreach($images as $image) { ?>
<li class='ins-imgs-li'>
   <a target="_blank" href="<?php echo $image; ?>"><img width="200" src="<?php echo $image; ?>" /></a>
</li>
<?php }
} else {
  echo 'No images';
}

echo '</ul>';

echo $pagination;
?>
