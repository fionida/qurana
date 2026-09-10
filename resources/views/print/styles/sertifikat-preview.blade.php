@php
    $pw = $pageLayout['width_mm'];
    $ph = $pageLayout['height_mm'];
    $orient = $pageLayout['orient_css'];
@endphp
<style>
    @page { margin: 0; size: A4 {{ $orient }}; }
    * { box-sizing: border-box; }
    body {
        font-family: 'Times New Roman', Times, serif;
        margin: 0;
        padding: 16px;
        background: #64748b;
        color: #111;
    }
    .toolbar {
        position: fixed;
        top: 12px;
        right: 12px;
        z-index: 100;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .toolbar a, .toolbar button {
        border: none;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        font-family: system-ui, sans-serif;
    }
    .toolbar button { background: #059669; color: #fff; }
    .toolbar a.secondary { background: #fff; color: #334155; }
    .pages {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
        padding-top: 48px;
        padding-bottom: 24px;
    }
    .cert-page {
        position: relative;
        width: {{ $pw }}mm;
        height: {{ $ph }}mm;
        background: #fff;
        box-shadow: 0 8px 32px rgb(0 0 0 / 0.25);
        overflow: hidden;
    }
    .cert-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: fill;
    }
    .cert-overlay {
        position: absolute;
        inset: 0;
    }
    .field {
        position: absolute;
        font-weight: bold;
        line-height: 1.3;
    }
    .field-center { text-align: center; }
    .field-justify { text-align: justify; }
    .field-normal { font-weight: normal; }
    .pages .cert-page-back {
        box-shadow: 0 8px 32px rgb(0 0 0 / 0.25);
    }
    @media print {
        body { padding: 0; background: #fff; }
        .toolbar, .pages { padding: 0; gap: 0; }
        .cert-page { box-shadow: none; page-break-after: always; }
        .cert-page:last-child { page-break-after: auto; }
    }
</style>
