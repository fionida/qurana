<style>
    @page {
        size: A4 portrait;
        margin: 8mm;
    }
    * { box-sizing: border-box; }
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        color: #0f172a;
        background: #f1f5f9;
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
    }
    .toolbar button.primary {
        background: #065f46;
        color: #fff;
        border-color: #065f46;
    }
    .toolbar-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .pages { padding: 16px; }
    .page {
        width: 210mm;
        min-height: 297mm;
        margin: 0 auto 16px;
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 8mm;
        page-break-after: always;
    }
    .page:last-child { page-break-after: auto; }
    .grid {
        display: grid;
        grid-template-columns: repeat({{ $gridCols }}, minmax(0, 1fr));
        gap: 4mm;
        height: 100%;
        align-content: start;
    }
    .card {
        border: 1.5px solid #059669;
        border-radius: 6px;
        overflow: hidden;
        background: #fff;
        display: flex;
        flex-direction: column;
        min-height: 62mm;
        break-inside: avoid;
        page-break-inside: avoid;
    }
    .card.has-bg {
        position: relative;
        border: 0;
        min-height: 64mm;
    }
    .card.has-bg .bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .card.has-bg .card-inner {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 10% 6% 8%;
        justify-content: center;
    }
    .head {
        background: #059669;
        color: #fff;
        padding: 4px 6px;
        text-align: center;
    }
    .head-title {
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.04em;
        line-height: 1.2;
    }
    .head-sub {
        font-size: 7px;
        margin-top: 1px;
        opacity: 0.95;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .body {
        display: flex;
        gap: 5px;
        padding: 5px 6px;
        flex: 1;
        align-items: flex-start;
    }
    .photo {
        width: 18mm;
        height: 24mm;
        object-fit: cover;
        border-radius: 3px;
        border: 1px solid #cbd5e1;
        flex-shrink: 0;
        background: #e2e8f0;
    }
    .meta {
        flex: 1;
        min-width: 0;
        font-size: 8px;
        line-height: 1.35;
        color: #0f172a;
    }
    .meta strong {
        display: block;
        font-size: 9px;
        font-weight: 800;
        margin-bottom: 2px;
        text-transform: uppercase;
        line-height: 1.25;
    }
    .meta-tes {
        margin-top: 2px;
        font-size: 7px;
        color: #334155;
    }
    .foot {
        border-top: 1px dashed #cbd5e1;
        padding: 3px 6px;
        font-size: 6.5px;
        color: #64748b;
        text-align: center;
        line-height: 1.2;
    }
    .photo-missing {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 7px;
        color: #94a3b8;
        text-align: center;
        padding: 2px;
    }

    @media print {
        body { background: #fff; }
        .toolbar { display: none !important; }
        .pages { padding: 0; }
        .page {
            width: auto;
            min-height: auto;
            border: 0;
            margin: 0;
            padding: 0;
        }
    }
</style>
