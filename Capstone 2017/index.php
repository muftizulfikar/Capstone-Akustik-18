<?php
include 'loader.php';
$setTemplate = true;

if (isset($_GET['halaman'])) {
  $pages = $_GET['halaman'];
} else {
  $pages = 'beranda';
}

ob_start();
$file = 'halaman/' . $pages . '.php';
if (!file_exists($file)) {
  include 'halaman/error.php';
} else {
  include $file;
}
$content = ob_get_contents();
ob_end_clean();

if ($setTemplate == true) {
?>
  <!DOCTYPE html>
  <html lang="en">
  <?php include 'layouts/head.php' ?>

  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <?php
      include 'layouts/header.php';
      include 'layouts/sidebar.php';
      ?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            <?= $judul ?>
          </h1>
          <ol class="breadcrumb">
            <li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?= $judul ?></li>
          </ol>
        </section>
        <?php
        echo $content;
        ?>
      </div>
      <?php
      include 'layouts/footer.php';
      include 'layouts/javascript.php';
      ?>
    </div>
  </body>

  </html>
<?php } else {
  echo $content;
}


if (isset($fileJs)) {
  include 'halaman/js/' . $fileJs . '.php';
}

?>