<style>
    .cert-page-back {
        position: relative;
        width: {{ $pageLayout['width_mm'] }}mm;
        min-height: {{ $pageLayout['height_mm'] }}mm;
        page-break-after: always;
        padding: 14mm 16mm 12mm;
        font-family: DejaVu Serif, Times, serif;
        font-size: 10.5pt;
        color: #111;
        background: #fff;
    }
    .cert-page-back:last-child { page-break-after: auto; }
    .cert-back-title {
        text-align: center;
        font-size: 13pt;
        font-weight: bold;
        text-transform: uppercase;
        margin: 0 0 4px;
        line-height: 1.35;
    }
    .cert-back-subtitle {
        text-align: center;
        font-size: 10pt;
        margin: 0 0 10px;
        color: #333;
    }
    .cert-back-meta {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
        font-size: 10pt;
    }
    .cert-back-meta td {
        padding: 3px 6px;
        vertical-align: top;
    }
    .cert-back-meta .label {
        width: 28%;
        font-weight: bold;
    }
    .cert-back-table {
        width: 100%;
        border-collapse: collapse;
        margin: 8px 0 14px;
        font-size: 10pt;
    }
    .cert-back-table th,
    .cert-back-table td {
        border: 1px solid #333;
        padding: 6px 8px;
        vertical-align: middle;
    }
    .cert-back-table th {
        background: #f1f5f9;
        font-weight: bold;
        text-align: center;
    }
    .cert-back-table td.num { text-align: center; width: 8%; }
    .cert-back-table td.nilai { text-align: center; width: 14%; }
    .cert-back-table tr.total td {
        font-weight: bold;
        background: #fafafa;
    }
    .cert-back-signatures {
        width: 100%;
        margin-top: 18px;
        border-collapse: collapse;
    }
    .cert-back-signatures td {
        width: 33.33%;
        text-align: center;
        vertical-align: bottom;
        padding: 8px 8px 0;
    }
    .cert-back-sign-space {
        height: 22mm;
        border-bottom: 1px solid #333;
        margin: 0 auto 6px;
        max-width: 55mm;
    }
    .cert-back-sign-name {
        font-weight: bold;
        margin: 0;
        font-size: 10pt;
    }
    .cert-back-sign-role {
        margin: 2px 0 0;
        font-size: 9.5pt;
    }
    .cert-back-footnote {
        margin-top: 10px;
        font-size: 8.5pt;
        color: #555;
        text-align: center;
    }
</style>
