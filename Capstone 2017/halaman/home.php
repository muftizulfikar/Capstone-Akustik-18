<?php
$title = "Home";
$judul = $title;
$url = 'homeJs';
$fileJs = 'homeJs';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $db->where("id_device", $id);
    $data = $db->get("device", null, 'lokasi');
    $lokasi = $data[0]['lokasi'];
}
?>
<?= open() ?>

<?= nav($id) ?>

<!-- Severity Level Section Start-->
<div class="containera">
    <h3><b>Status <?= $lokasi ?>:</b></h3>
    <div id="message" style="color:black; font-weight:bold; font-size: 200%;text-align: center; line-height: 10%;">
    </div>
    <br>
    <div id="box1" class="box1"></div>
    <div id="box2" class="box2"></div>
    <div id="box3" class="box3"></div>
</div>


<div class="containera">
    <div class="wrapper1">
        <h3><b>Data 1 menit terakhir:</b></h3>


        <div id="boxb" class="boxb"><br>
            <p>Rata-rata dB:</p>
            <div id="average_dB"></div>
        </div>
        <div id="boxb" class="boxb"><br>
            <p>Arc Counter:</p>
            <div id="countVolt"></div>
        </div>


    </div>

    <div class="wrapper1">
        <div id="boxb" class="boxb"><br>
            <p>dB minimal:</p>
            <div id="dB_min"></div>
        </div>
        <div id="boxb" class="boxb"><br>
            <p>dB maksimal:</p>
            <div id="dB_max"></div>
        </div>


    </div>

    <div class="container2">
        <div id="boxe" class="boxe"> <br>
            <p>Time:</p>
            <div id="durasi"></div>
        </div>
    </div>

    <br>
    <!-- Severity Level Section End-->

    <!-- Chart Section Start-->

    <?= $session->pull("info") ?>
    <?= close() ?>