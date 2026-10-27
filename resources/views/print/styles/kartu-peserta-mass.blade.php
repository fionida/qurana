<style>
    @page {
        size: A4 portrait;
        margin: 6mm;
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
        height: 297mm;
        margin: 0 auto 16px;
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 6mm;
        page-break-after: always;
        display: flex;
        flex-direction: column;
    }
    .page:last-child { page-break-after: auto; }
    .grid {
        flex: 1;
        display: grid;
        grid-template-columns: repeat({{ $gridCols }}, minmax(0, 1fr));
        grid-template-rows: repeat({{ $gridRows }}, minmax(0, 1fr));
        gap: 3.5mm;
        width: 100%;
        height: 100%;
        min-height: 0;
    }
    .card {
        border: 1.5px solid #059669;
        border-radius: 5px;
        overflow: hidden;
        background: #fff;
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 0;
        break-inside: avoid;
        page-break-inside: avoid;
    }
    .card.has-bg {
        position: relative;
        border: 0;
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
        min-height: 0;
        padding: 8% 6%;
        justify-content: center;
    }
    .head {
        background: #059669;
        color: #fff;
        padding: 2.2mm 2.5mm;
        text-align: center;
        flex-shrink: 0;
    }
    .head-title {
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.05em;
        line-height: 1.15;
    }
    .head-sub {
        font-size: 8px;
        margin-top: 1px;
        opacity: 0.95;
        line-height: 1.15;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .body {
        display: flex;
        gap: 3mm;
        padding: 2.5mm 3mm;
        flex: 1 1 auto;
        min-height: 0;
        align-items: center;
        justify-content: flex-start;
    }
    .photo {
        width: 34%;
        max-width: 28mm;
        aspect-ratio: 3 / 4;
        height: auto;
        max-height: 92%;
        object-fit: cover;
        border-radius: 3px;
        border: 1px solid #cbd5e1;
        flex-shrink: 0;
        background: #e2e8f0;
        align-self: center;
    }
    .meta {
        flex: 1 1 auto;
        min-width: 0;
        min-height: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 1.2mm;
        font-size: 9.5px;
        line-height: 1.3;
        color: #0f172a;
    }
    .meta strong {
        display: block;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        line-height: 1.2;
        margin: 0;
    }
    .meta div {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .meta-tes {
        font-size: 8px;
        color: #334155;
    }
    .foot {
        border-top: 1px dashed #cbd5e1;
        padding: 1.6mm 2.5mm;
        font-size: 7px;
        color: #64748b;
        text-align: center;
        line-height: 1.2;
        flex-shrink: 0;
    }
    .photo-missing {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8px;
        color: #94a3b8;
        text-align: center;
        padding: 2px;
        width: 34%;
        max-width: 28mm;
        aspect-ratio: 3 / 4;
        max-height: 92%;
        border-radius: 3px;
        border: 1px dashed #cbd5e1;
        background: #f8fafc;
        flex-shrink: 0;
    }

    @media print {
        body { background: #fff; }
        .toolbar { display: none !important; }
        .pages { padding: 0; }
        .page {
            width: auto;
            height: auto;
            min-height: 0;
            border: 0;
            margin: 0;
            padding: 0;
        }
        .grid {
            height: calc(297mm - 12mm);
        }
    }
</style>
