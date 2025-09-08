<?php
require dirname(__DIR__) . '/required/globals_nonauth.php';
$gameuser = $_SESSION['userid']; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
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
    background-color: #000000;
  }

</style>
