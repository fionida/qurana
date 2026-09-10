document.addEventListener('alpine:init', () => {
    Alpine.data('layoutEditor', (h1, labels, samples, flashSuccess = null) => ({
        h1,
        labels,
        samples,
        active: 'nama_lengkap',
        dragging: false,
        dragMode: null,
        dragKey: null,
        startX: 0,
        startY: 0,
        startLeft: 0,
        startTop: 0,
        startWidth: 0,
        saveNotice: flashSuccess || null,
        saving: false,

        init() {
            if (this.saveNotice) {
                this.$nextTick(() => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
                window.setTimeout(() => {
                    this.saveNotice = null;
                }, 6000);
            }
        },

        onSaveSubmit() {
            this.saving = true;
        },

        boxStyle(box, key) {
            const align = box.align === 'center' ? 'center' : 'left';
            const z = this.active === key ? 40 : 10;

            return [
                `top:${box.top_pct}%`,
                `left:${box.left_pct}%`,
                `width:${box.width_pct}%`,
                `font-size:${box.size}px`,
                `text-align:${align}`,
                `z-index:${z}`,
            ].join(';');
        },

        sampleText(key) {
            const t = this.samples[key] || key;

            return String(t).split('\n')[0].slice(0, 48);
        },

        selectField(key) {
            this.active = key;
        },

        startInteraction(key, e, mode) {
            this.active = key;
            this.dragging = true;
            this.dragMode = mode;
            this.dragKey = key;
            this.startX = e.clientX;
            this.startY = e.clientY;
            this.startLeft = this.h1[key].left_pct;
            this.startTop = this.h1[key].top_pct;
            this.startWidth = this.h1[key].width_pct;
            e.preventDefault();
        },

        onDrag(e) {
            if (!this.dragging || !this.dragKey) {
                return;
            }

            const board = this.$refs.board;
            if (!board) {
                return;
            }

            const rect = board.getBoundingClientRect();
            const dx = ((e.clientX - this.startX) / rect.width) * 100;
            const dy = ((e.clientY - this.startY) / rect.height) * 100;
            const box = this.h1[this.dragKey];
            const mode = this.dragMode;

            if (mode === 'move') {
                box.left_pct = this.clampPct(this.startLeft + dx, 0, 98);
                box.top_pct = this.clampPct(this.startTop + dy, 0, 98);

                return;
            }

            const minW = 3;
            const maxW = 100;

            if (mode === 'e' || mode === 'ne' || mode === 'se') {
                box.width_pct = this.clampPct(this.startWidth + dx, minW, maxW);
            }

            if (mode === 'w' || mode === 'nw' || mode === 'sw') {
                const newW = this.clampPct(this.startWidth - dx, minW, maxW);
                const delta = newW - this.startWidth;
                box.width_pct = newW;
                box.left_pct = this.clampPct(this.startLeft - delta, 0, 98);
            }

            if (mode === 'n' || mode === 'ne' || mode === 'nw') {
                box.top_pct = this.clampPct(this.startTop + dy, 0, 98);
            }

            if (mode === 's' || mode === 'se' || mode === 'sw') {
                box.top_pct = this.clampPct(this.startTop + dy, 0, 98);
            }

            box.left_pct = Math.round(box.left_pct * 10) / 10;
            box.top_pct = Math.round(box.top_pct * 10) / 10;
            box.width_pct = Math.round(box.width_pct * 10) / 10;
        },

        endDrag() {
            this.dragging = false;
            this.dragMode = null;
            this.dragKey = null;
        },

        clampPct(value, min, max) {
            return Math.round(Math.min(max, Math.max(min, value)) * 10) / 10;
        },

        handleCursor(mode) {
            const map = {
                move: 'move',
                n: 'ns-resize',
                s: 'ns-resize',
                e: 'ew-resize',
                w: 'ew-resize',
                ne: 'nesw-resize',
                sw: 'nesw-resize',
                nw: 'nwse-resize',
                se: 'nwse-resize',
            };

            return map[mode] || 'default';
        },
    }));
});
