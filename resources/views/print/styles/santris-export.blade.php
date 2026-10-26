<style>
    @page {
        size: A4 landscape;
        margin: 10mm 8mm;
    }
    * { box-sizing: border-box; }
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        color: #0f172a;
        background: #f1f5f9;
        font-size: 11px;
    }
    .toolbar {
        position: sticky;
        top: 0;
        z-index: 10;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        padding: 12px 16px;
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
    }
    .toolbar button,
    .toolbar a {
        border: 1px solid #cbd5e1;
        background: #fff;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 600;
        color: #0f172a;
        text-decoration: none;
        cursor: pointer;
        display: inline-block;
    }
    .toolbar button.primary {
        background: #065f46;
        color: #fff;
        border-color: #065f46;
    }
    .toolbar-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }
    .sheet {
        max-width: 297mm;
        margin: 16px auto;
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 10mm 8mm;
    }
    .doc-header {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: flex-start;
        border-bottom: 2px solid #065f46;
        padding-bottom: 8px;
        margin-bottom: 10px;
    }
    .doc-brand {
        font-size: 13px;
        font-weight: 700;
        color: #065f46;
        letter-spacing: 0.02em;
    }
    .doc-title {
        margin: 2px 0 0;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
    }
    .doc-meta {
        text-align: right;
        font-size: 10px;
        color: #475569;
        line-height: 1.45;
    }
    .filters {
        margin: 0 0 10px;
        padding: 6px 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        color: #334155;
        font-size: 10px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    thead { display: table-header-group; }
    th, td {
        border: 1px solid #94a3b8;
        padding: 3px 5px;
        vertical-align: top;
        word-wrap: break-word;
    }
    th {
        background: #ecfdf5;
        color: #064e3b;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        font-weight: 700;
        text-align: left;
    }
    td { font-size: 10px; }
    tbody tr:nth-child(even) td { background: #f8fafc; }
    .col-no { width: 28px; text-align: center; }
    .col-noreg { width: 88px; }
    .col-nama { width: auto; }
    .col-jk { width: 28px; text-align: center; }
    .col-lembaga { width: 120px; }
    .col-program { width: 110px; }
    .col-gelombang { width: 80px; }
    .col-wa { width: 90px; }
    .col-bayar { width: 70px; }
    .col-status { width: 80px; }
    .col-nilai { width: 44px; text-align: center; }
    .footer-note {
        margin-top: 10px;
        font-size: 9px;
        color: #64748b;
        display: flex;
        justify-content: space-between;
        gap: 12px;
    }
    @media print {
        body { background: #fff; }
        .toolbar { display: none !important; }
        .sheet {
            margin: 0;
            border: 0;
            max-width: none;
            padding: 0;
        }
        tbody tr:nth-child(even) td { background: transparent; }
        th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
