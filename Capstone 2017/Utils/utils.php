<?php
function base_url($a = '')
{
  $getbase_url = getenv('BASE_URL');
  return $getbase_url . $a;
}

function assets($a = '')
{
  $getbase_assets = getenv('BASE_ASSETS');
  return base_url($getbase_assets . $a);
}

function url($a = '', $b = '')
{
  return base_url($b . '?halaman=' . $a);
}

function redirect($a = '')
{
  header("location: " . $a);
  exit;
}

function templates($a = '')
{
  return assets(getenv('template') . $a);
}


function open($title = '')
{
  return '
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">' . $title . '</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">';
}
function close()
{
  return '
	  </div>
       
      </div>
      <!-- /.box -->

    </section>';
}
function alert_message($message, $lokasi, $time = 'recently')
{
  return '<li>
  <!-- start message -->
  <a href="#">
    <div class="pull-left">
    </div>
    <h4>
      ' . $message . '
      <small><i class="fa fa-clock-o"></i>' . $time . '</small>
    </h4>
    <p>' . $lokasi . '</p>
  </a>
</li>';
}
function nav($id = '')
{
  return '
  <section class="content">
  <!-- Default box -->
  <div class="box">
    <div class="box-header with-border">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="?halaman=home&id=' . $id . '">Status</a>
    <a class="navbar-brand" href="?halaman=pd&id=' . $id . '">Partial Discharge Monitoring</a>
    <a class="navbar-brand" href="?halaman=arc&id=' . $id . '">Partial Arc Monitoring</a>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body">';
}
function getLastData($id)
{
  $url = "https://firebase17.herokuapp.com/api/v1/getLastData?id=$id";
  $json = file_get_contents($url);
  $json = json_decode($json);
  return $json;
}
function getNotification($id)
{
  $url = "https://firebase17.herokuapp.com/api/v1/getNotification?id=$id";
  $json = file_get_contents($url);
  $json = json_decode($json);
  return $json;
}


function input_text($name, $value, $data = '', $addition = '')
{
  if (!is_array($data)) {

    if ($data != '') {
      $data = ' ' . $data;
    }
    if ($addition != '') {
      $addition = ' ' . $addition;
    }
    return '<input type="text" name="' . $name . '" value="' . $value . '" class="form-control' . $data . '"' . $addition . '>' . "\n";
  } else {
    $set = '';
    foreach ($data as $key => $value) {
      $set .= ' ' . $key . '="' . $value . '" ';
    }
    $data = $set;
    return '<input type="text" name="' . $name . '" value="' . $value . '" ' . $data . '>' . "\n";
  }
}
function input_file($name, $value, $c = '', $d = '')
{
  if ($c != '') {
    $c = ' ' . $c;
  }
  if ($d != '') {
    $d = ' ' . $d;
  }
  return '<input type="file" name="' . $name . '" value="' . $value . '" class="form-control' . $c . '"' . $d . '>' . "\n";
}

function input_date($name, $value, $c = '', $d = '')
{
  if ($c != '') {
    $c = ' ' . $c;
  }
  if ($d != '') {
    $d = ' ' . $d;
  }
  return '<input type="date" name="' . $name . '" value="' . $value . '" class="form-control' . $c . '"' . $d . '>' . "\n";
}


function textarea($name, $text, $c = '', $d = '')
{
  if (!is_array($c)) {
    if ($c != '') {
      $c = ' ' . $c;
    }
    if ($d != '') {
      $d = ' ' . $d;
    }
    return '<textarea name="' . $name . '" class="form-control' . $c . '"' . $d . '>' . $text . '</textarea>' . "\n";
  } else {
    $set = '';
    foreach ($c as $key => $value) {
      $set .= ' ' . $key . '="' . $value . '" ';
    }
    $c = $set;

    return '<textarea name="' . $name . '" class="form-control' . $c . '"' . $d . '>' . $text . '</textarea>' . "\n";
    return '<textarea name="' . $name . '" ' . $c . '>' . $text . '</textarea>' . "\n";
  }
}
function input_hidden($name, $value, $data = '')
{

  if (!is_array($data)) {

    if ($data != '') {
      $data = ' ' . $data;
    }
    return '<input type="hidden" name="' . $name . '" value="' . $value . '" class="form-control' . $data . '">' . "\n";
  } else {
    $set = '';
    foreach ($data as $key => $val) {
      $set .= ' ' . $key . '="' . $val . '" ';
    }
    $data = $set;
    return '<input type="hidden" name="' . $name . '" value="' . $value . '" ' . $data . '>' . "\n";
  }
}


function _info_template($text, $addition)
{
  return '<div class="alert alert-dismissable ' . $addition . '" style="margin-left:0px;margin-top:10px"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> ' . $text . ' </div>';
}
