<script>
    function dtExportTitle(dt) {
        const caption = dt.table().node().querySelector('caption');
        const title = caption ? caption.textContent.trim() : document.title;

        return title.replace(/[\\/:*?"<>|]+/g, '').trim() || 'exportacion';
    }

    function dtExportColumnIndexes(dt) {
        const indexes = [];

        dt.columns().every(function (index) {
            const header = $(this.header()).text().replace(/\s+/g, ' ').trim();

            if (header !== '') {
                indexes.push(index);
            }
        });

        return indexes;
    }

    function dtDownloadJson(dt, title) {
        const exported = dt.buttons.exportData({
            columns: dtExportColumnIndexes(dt),
            stripHtml: true,
            trim: true,
        });
        const rows = exported.body.map(function (row) {
            const item = {};

            exported.header.forEach(function (header, index) {
                item[header] = row[index];
            });

            return item;
        });
        const blob = new Blob([JSON.stringify(rows, null, 2)], { type: 'application/json;charset=utf-8' });
        const link = document.createElement('a');

        link.href = URL.createObjectURL(blob);
        link.download = title + '.json';
        document.body.appendChild(link);
        link.click();
        link.remove();
        setTimeout(function () {
            URL.revokeObjectURL(link.href);
        }, 0);
    }

    function dtExportAllAction(e, dt, button, config, done) {
        const self = this;
        const settings = dt.settings()[0];
        const title = dtExportTitle(dt);
        let finished = false;

        config.title = title;
        config.filename = title;
        config.footer = false;
        config.exportOptions = $.extend(true, {}, config.exportOptions, {
            columns: dtExportColumnIndexes(dt),
            stripHtml: true,
            trim: true,
        });

        const finish = function () {
            if (finished) {
                return;
            }

            finished = true;
            $(button).prop('disabled', false);

            if (typeof done === 'function') {
                done();
            }
        };
        const run = function () {
            if (config._exportKind === 'json') {
                dtDownloadJson(dt, title);
                finish();
                return;
            }

            $.fn.dataTable.ext.buttons[config._exportExtend].action.call(self, e, dt, button, config, finish);

            if (config._exportExtend === 'print') {
                setTimeout(finish, 0);
            }
        };
        const restore = function () {
            settings._iDisplayStart = config._exportStart;
            settings._iDisplayLength = config._exportLength;
            dt.one('preXhr.dtExportRestore', function (_evt, _s, data) {
                data.start = config._exportStart;
                data.length = config._exportLength;
            });
            dt.ajax.reload(null, false);
        };

        $(button).prop('disabled', true);

        if (!settings.oFeatures.bServerSide) {
            run();
            return;
        }

        config._exportStart = settings._iDisplayStart;
        config._exportLength = settings._iDisplayLength;

        dt.one('preXhr.dtExport', function (_evt, _s, data) {
            data.start = 0;
            data.length = -1;
        });
        dt.one('xhr.dtExport', function (_evt, _s, json) {
            if (json === null) {
                dt.off('draw.dtExport');
                finish();
            }
        });
        dt.one('draw.dtExport', function () {
            try {
                run();
            } finally {
                setTimeout(restore, 0);
            }
        });
        dt.ajax.reload();
    }

    function dtExportButtons() {
        const shared = {
            className: 'btn-sm',
            footer: false,
            action: dtExportAllAction,
            exportOptions: {
                stripHtml: true,
                trim: true,
            },
        };

        return [
            $.extend({}, shared, {
                extend: 'csvHtml5',
                text: 'CSV',
                titleAttr: 'Exportar CSV',
                bom: true,
                fieldSeparator: ';',
                charset: 'utf-8',
                _exportExtend: 'csvHtml5',
            }),
            $.extend({}, shared, {
                extend: 'excelHtml5',
                text: 'Excel',
                titleAttr: 'Exportar Excel',
                _exportExtend: 'excelHtml5',
            }),
            $.extend({}, shared, {
                text: 'JSON',
                titleAttr: 'Exportar JSON',
                _exportKind: 'json',
            }),
            $.extend({}, shared, {
                extend: 'print',
                text: '<i class="fas fa-print" aria-hidden="true"></i>',
                titleAttr: 'Imprimir',
                attr: {
                    'aria-label': 'Imprimir',
                },
                autoPrint: true,
                _exportExtend: 'print',
            }),
        ];
    }
</script>
