<?php
session_start();
require "../lib/basic_error_handler.php";
//set_error_handler('../error_php');
include "../required/config.php";
define("MONO_ON", 1);
require "../class/class_db_{$_CONFIG['driver']}.php";
require_once('../required/global_func.php');
$db = new database;
$db->configure($_CONFIG['hostname'], $_CONFIG['username'],
        $_CONFIG['password'], $_CONFIG['database']);
$db->connect();
$c     = $db->connection_id;
$set   = [];
$settq = $db->query("SELECT * FROM settings");
while ($r = $db->fetch_row($settq)) {
    $set[$r['conf_name']] = $r['conf_value'];
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
<style>
  @font-face {
    font-family: 'BebasNeueRegular';
    src:         url('../Fonts/BebasNeue-webfont.eot');
    src:         url('../Fonts/BebasNeue-webfont.eot?#iefix') format('embedded-opentype'),
                 url('../Fonts/BebasNeue-webfont.woff') format('woff'),
                 url('../Fonts/BebasNeue-webfont.ttf') format('truetype'),
                 url('../Fonts/BebasNeue-webfont.svg#BebasNeueRegular') format('svg');
    font-weight: normal;
    font-style:  normal;

  }

  h4.fontface {
    font:           40px/48px 'BebasNeueRegular', Arial, sans-serif;
    letter-spacing: 0;
  }

  .fontface {
    font: 18px/27px 'monospace', Arial, sans-serif;
  }

  .bucks {
    color:       #379e00;
    font-weight: bold;
  }

  .airbucks {
    color:       #016a85;
    font-weight: bold;
  }

  body {
    background-color: #f1f1f1;
  }

</style>
