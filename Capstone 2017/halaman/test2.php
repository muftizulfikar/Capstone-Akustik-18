<?php
$title = "Home";
$judul = $title;
$url = 'testJs2';
$fileJs = 'testJs2';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $db->where("id_device", $id);
    $data = $db->get("device", null, 'lokasi');
    $lokasi = $data[0]['lokasi'];
}
?>
<?= nav($id) ?>
<?= open() ?>
<div class="containera">
    <div id="message" style="color:black;  font-size: 100%;text-align: center;"></div>
    <div id="state" style="margin:0 auto;width:100px; height:100px;"></div>
</div>
<div id="myPlot"></div>

<!-- <h3 style="text-align: center;"><b>Data 1 menit terakhir:</b></h3> -->
<!-- 
<div class="containera">
    <h3><b>Partial Discharge Parameter:</b></h3>

    <div class="wrapper1">

        <div id="boxb" class="boxb"><br>
            <p>Rata-rata dB:</p>
            <div id="average_dB"></div>
        </div>
        <div id="boxb" class="boxb"><br>
            <p>dB minimal:</p>
            <div id="dB_min"></div>
        </div>
        <div id="boxb" class="boxb"><br>
            <p>dB maksimal:</p>
            <div id="dB_max"></div>
        </div>
    </div>
</div>
<div class="containera">
    <h3><b>Partial Arc Parameter:</b></h3>

    <div class="wrapper1">

        <div id="boxb" class="boxb"><br>
            <p>Arc Counter:</p>
            <div id="countVolt"></div>
        </div>

    </div>
</div> -->
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Recent History</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                <i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">
        <ul id="recentHistory">
        </ul>
    </div>
</div>

</div>


<?= $session->pull("info") ?>
<?= close() ?>