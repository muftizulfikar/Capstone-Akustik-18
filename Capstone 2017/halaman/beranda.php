<?php
$title = "Beranda";
$judul = $title;
$url = 'berandaJs';
$fileJs = 'berandaJs';
?>
<?= open('Halaman Beranda') ?>
<div id="mapid"></div>
<?= $session->pull("info") ?>
<?= close() ?>