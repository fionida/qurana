@php
    $pw = $pageLayout['width_mm'];
    $ph = $pageLayout['height_mm'];
    $orient = $pageLayout['orient_css'];
@endphp
<style>
    @page { margin: 0; size: A4 {{ $orient }}; }
    * { box-sizing: border-box; }
    body {
        font-family: DejaVu Serif, Times, serif;
        margin: 0;
        padding: 0;
        color: #111;
    }
    .cert-page {
        position: relative;
        width: {{ $pw }}mm;
        height: {{ $ph }}mm;
        page-break-after: always;
        overflow: hidden;
    }
    .cert-page:last-child { page-break-after: auto; }
    .cert-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: {{ $pw }}mm;
        height: {{ $ph }}mm;
    }
    .cert-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: {{ $pw }}mm;
        height: {{ $ph }}mm;
    }
    .field {
        position: absolute;
        font-weight: bold;
        line-height: 1.3;
    }
    .field-center { text-align: center; }
    .field-justify { text-align: justify; }
    .field-normal { font-weight: normal; }
</style>
