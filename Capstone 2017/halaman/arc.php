<?php
$title = "Arc Monitoring";
$judul = $title;
$url = 'arcJs';
$fileJs = 'arcJs';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
?>
<?= nav($id) ?>
<?= open('Arc Monitoring') ?>
<div class="containera">
    <div id="message" style="color:black;  font-size: 100%;text-align: center;"></div>
    <div id="state" style="margin:0 auto;width:100px; height:100px;"></div>
</div>
<div id="myPlot"></div>
<div id="myPlot2" style="margin-top: -50px auto;"></div>

<?= $session->pull("info") ?>
<?= close() ?>