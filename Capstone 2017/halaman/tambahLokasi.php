<?php
$title = "Manajemen Perangkat";
$judul = $title;
$url = 'tambahLokasi';
if (isset($_POST['simpan'])) {
    if ($_POST['id_device'] != '') {
        $db->where('id_device', $_GET['id']);
        $get = $db->ObjectBuilder()->getOne('device');
    }
    $data['keterangan'] = $_POST['keterangan'];
    $data['lokasi'] = $_POST['lokasi'];
    $data['lat'] = $_POST['lat'];
    $data['lng'] = $_POST['lng'];
    $data['tanggal'] = $_POST['tanggal'];
    if ($_POST['id_device'] == "") {
        $query = $db->insert("device", $data);
        $info = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Sukses!</h4> Data Sukses Ditambah </div>';
    } else {
        $db->where('id_device', $_POST['id_device']);
        $query = $db->update("device", $data);
        $info = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Sukses!</h4> Data Sukses diubah </div>';
    }

    if ($query) {
        $session->set('info', $info);
    } else {
        $session->set("info", '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4> Proses gagal dilakukan
              </div>');
    }
    redirect(url($url));
}

if (isset($_GET['hapus'])) {
    $setTemplate = false;
    $db->where("id_device", $_GET['id']);
    $query = $db->delete("device");
    $info = '<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-ban"></i> Sukses!</h4> Data Sukses dihapus </div>';
    if ($query) {
        $session->set('info', $info);
    } else {
        $session->set("info", '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4> Proses gagal dilakukan
              </div>');
    }
    redirect(url($url));
} elseif (isset($_GET['tambah']) or isset($_GET['ubah'])) {
    $id_device = "";
    $lokasi = "";
    $keterangan = "";
    $lat = "";
    $lng = "";
    $tanggal = date('Y-m-d');
    if (isset($_GET['ubah']) and isset($_GET['id'])) {
        $id = $_GET['id'];
        $db->where('id_device', $id);
        $row = $db->ObjectBuilder()->getOne('device');
        if ($db->count > 0) {
            $id_device = $row->id_device;
            $lokasi = $row->lokasi;
            $keterangan = $row->keterangan;
            $lat = $row->lat;
            $lng = $row->lng;
            $tanggal = $row->tanggal;
        }
    }
?>
    <?= open('') ?>
    <form method="post" enctype="multipart/form-data">
        <?= input_hidden('id_device', $id_device) ?>
        <div class="form-group">
            <label>Lokasi</label>
            <div class="row">
                <div class="col-md-4">
                    <?= input_text('lokasi', $lokasi) ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <div class="row">
                <div class="col-md-4">
                    <?= textarea('keterangan', $keterangan) ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Titik Koordinat</label>
            <div class="row">
                <div class="col-md-3">
                    <?= input_text('lat', $lat) ?>
                </div>
                <div class="col-md-3">
                    <?= input_text('lng', $lng) ?>
                </div>
            </div>
        </div>
        <?= input_hidden('tanggal', $tanggal) ?>

        <div class="form-group">
            <button type="submit" name="simpan" class="btn btn-info"><i class="fa fa-save"></i> Simpan</button>
            <a href="<?= url($url) ?>" class="btn btn-danger"><i class="fa fa-reply"></i> Kembali</a>
        </div>
    </form>
    <?= close() ?>

<?php  } else { ?>
    <?= open('Data Perangkat') ?>

    <a href="<?= url($url . '&tambah') ?>" class="btn btn-success"><i class="fa fa-plus"></i> Tambah</a>
    <hr>
    <?= $session->pull("info") ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Device</th>
                <th>Lokasi</th>
                <th>Keterangan</th>
                <th>Latitude</th>
                <th>Longitude</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $getdata = $db->ObjectBuilder()->get('device a');
            foreach ($getdata as $row) {
            ?>
                <tr>
                    <td><?= $row->id_device ?></td>
                    <td><?= $row->lokasi ?></td>
                    <td><?= $row->keterangan ?></td>
                    <td><?= $row->lat ?></td>
                    <td><?= $row->lng ?></td>
                    <td>
                        <a href="<?= url($url . '&ubah&id=' . $row->id_device) ?>" class="btn btn-info"><i class="fa fa-edit"></i> Ubah</a>
                        <a href="<?= url($url . '&hapus&id=' . $row->id_device) ?>" class="btn btn-danger" onclick="return confirm('Hapus data?')"><i class="fa fa-trash"></i> Hapus</a>
                        <a href="<?= url('home&id=' . $row->id_device) ?>" class="btn btn-default"><i class="fa fa-line-chart"></i> Monitoring</a>

                    </td>
                </tr>
            <?php
            }

            ?>
        </tbody>
    </table>
    <?= close() ?>
<?php } ?>