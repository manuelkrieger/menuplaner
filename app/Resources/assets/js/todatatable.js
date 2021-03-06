$(document).ready(function ($) {
    $('.table.toDataTable').each(function () {
        var sorting = [];
        var notSortable = [];
        $('thead th', this).each(function (colIndex) {
            if ($(this).data('sort')) {
                sorting.push([colIndex, $(this).data('sort')]);
            }
            if ($(this).data('sortable') && $(this).data('sortable') === 'false') {
                notSortable.push(colIndex);
            }
        });
        $(this).DataTable({
            "pageLength": 25,
            'language': {
                'emptyTable': 'Keine Daten in der Tabelle vorhanden',
                'info': '_START_ bis _END_ von _TOTAL_ Einträgen',
                'infoEmpty': '0 bis 0 von 0 Einträgen',
                'infoFiltered': '(gefiltert von _MAX_ Einträgen)',
                'infoPostFix': '',
                'infoThousands': '.',
                'lengthMenu': '_MENU_ Einträge anzeigen',
                'loadingRecords': 'Wird geladen...',
                'processing': 'Bitte warten...',
                'search': 'Suchen',
                'zeroRecords': 'Keine Einträge vorhanden.',
                'paginate': {
                    'first': 'Erste',
                    'previous': 'Zurück',
                    'next': 'Nächste',
                    'last': 'Letzte'
                },
                'aria': {
                    'sortAscending': ': aktivieren, um Spalte aufsteigend zu sortieren',
                    'sortDescending': ': aktivieren, um Spalte absteigend zu sortieren'
                }
            },
            'aaSorting': sorting,
            'aoColumnDefs': [
                {'bSortable': false, 'aTargets': notSortable}
            ],
            'dom': "<'row be-datatable-header'<'col-sm-6'l><'col-sm-6'f>>" +
            "<'row be-datatable-body'<'col-sm-12'tr>>" +
            "<'row be-datatable-footer'<'col-sm-5'i><'col-sm-7'p>>"
        });
    });
});
