// lazyload config
var MODULE_CONFIG = {
    chat:           [
                      '../themes/default/plugins/list.js/dist/list.js',
                      '../themes/default/plugins/notie/dist/notie.min.js',
                      '../themes/default/plugins/scripts/plugins/notie.js',
                      '../themes/default/plugins/scripts/app/chat.js'
                    ],
    mail:           [
                      '../themes/default/plugins/list.js/dist/list.js',
                      '../themes/default/plugins/notie/dist/notie.min.js',
                      '../themes/default/plugins/scripts/plugins/notie.js',
                      '../themes/default/plugins/scripts/app/mail.js'
                    ],
    user:           [
                      '../themes/default/plugins/list.js/dist/list.js',
                      '../themes/default/plugins/notie/dist/notie.min.js',
                      '../themes/default/plugins/scripts/plugins/notie.js',
                      '../themes/default/plugins/scripts/app/user.js'
                    ],
    screenfull:     [
                      '../themes/default/plugins/screenfull/dist/screenfull.js',
                      '../themes/default/plugins/scripts/plugins/screenfull.js'
                    ],
    jscroll:        [
                      '../themes/default/plugins/jscroll/jquery.jscroll.min.js'
                    ],
    stick_in_parent:[
                      '../themes/default/plugins/sticky-kit/jquery.sticky-kit.min.js'
                    ],
    scrollreveal:   [
                      '../themes/default/plugins/scrollreveal/dist/scrollreveal.min.js',
                      '../themes/default/plugins/scripts/plugins/jquery.scrollreveal.js'
                    ],
    owlCarousel:    [
                      '../themes/default/plugins/owl.carousel/dist/assets/owl.carousel.min.css',
                      '../themes/default/plugins/owl.carousel/dist/assets/owl.theme.css',
                      '../themes/default/plugins/owl.carousel/dist/owl.carousel.min.js'
                    ],
    html5sortable:  [
                      '../themes/default/plugins/html5sortable/dist/html.sortable.min.js',
                      '../themes/default/plugins/scripts/plugins/jquery.html5sortable.js',
                      '../themes/default/plugins/scripts/plugins/sortable.js'
                    ],
    easyPieChart:   [
                      '../themes/default/plugins/easy-pie-chart/dist/jquery.easypiechart.min.js' 
                    ],
    peity:          [
                      '../themes/default/plugins/peity/jquery.peity.js',
                      '../themes/default/plugins/scripts/plugins/jquery.peity.tooltip.js',
                    ],
    chart:          [
                      '../themes/default/plugins/moment/min/moment-with-locales.min.js',
                      '../themes/default/plugins/chart.js/dist/Chart.min.js',
                      '../themes/default/plugins/scripts/plugins/jquery.chart.js',
                      '../themes/default/plugins/scripts/plugins/chartjs.js'
                    ],
    dataTable:      [
                      '../themes/default/plugins/datatables/media/js/jquery.dataTables.min.js',
                      '../themes/default/plugins/datatables.net-bs4/js/dataTables.bootstrap4.js',
                      '../themes/default/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css',
                      '../themes/default/plugins/scripts/plugins/datatable.js'
                    ],
    bootstrapTable: [
                      '../themes/default/plugins/bootstrap-table/dist/bootstrap-table.min.css',
                      '../themes/default/plugins/bootstrap-table/dist/bootstrap-table.min.js',
                      '../themes/default/plugins/bootstrap-table/dist/extensions/export/bootstrap-table-export.min.js',
                      '../themes/default/plugins/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js',
                      '../themes/default/plugins/scripts/plugins/tableExport.min.js',
                      '../themes/default/plugins/scripts/plugins/bootstrap-table.js'
                    ],
    bootstrapWizard:[
                      '../themes/default/plugins/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js'
                    ],
    dropzone:       [
                      '../themes/default/plugins/dropzone/dist/min/dropzone.min.js',
                      '../themes/default/plugins/dropzone/dist/min/dropzone.min.css'
                    ],
    datetimepicker: [
                      '../themes/default/plugins/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css',
                      '../themes/default/plugins/moment/min/moment-with-locales.min.js',
                      '../themes/default/plugins/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js',
                      '../themes/default/plugins/scripts/plugins/datetimepicker.js'
                    ],
    datepicker:     [
                      "../themes/default/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js",
                      "../themes/default/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css",
                    ],
    fullCalendar:   [
                      '../themes/default/plugins/moment/min/moment-with-locales.min.js',
                      '../themes/default/plugins/fullcalendar/dist/fullcalendar.min.js',
                      '../themes/default/plugins/fullcalendar/dist/fullcalendar.min.css',
                      '../themes/default/plugins/scripts/plugins/fullcalendar.js'
                    ],
    parsley:        [
                      '../themes/default/plugins/parsleyjs/dist/parsley.min.js'
                    ],
    select2:        [
                      '../themes/default/plugins/select2/dist/css/select2.min.css',
                      '../themes/default/plugins/select2/dist/js/select2.min.js',
                      '../themes/default/plugins/scripts/plugins/select2.js'
                    ],
    summernote:     [
                      '../themes/default/plugins/summernote/dist/summernote.css',
                      '../themes/default/plugins/summernote/dist/summernote-bs4.css',
                      '../themes/default/plugins/summernote/dist/summernote.min.js',
                      '../themes/default/plugins/summernote/dist/summernote-bs4.min.js'
                    ],
    vectorMap:      [
                      '../themes/default/plugins/jqvmap/dist/jqvmap.min.css',
                      '../themes/default/plugins/jqvmap/dist/jquery.vmap.js',
                      '../themes/default/plugins/jqvmap/dist/maps/jquery.vmap.world.js',
                      '../themes/default/plugins/jqvmap/dist/maps/jquery.vmap.usa.js',
                      '../themes/default/plugins/jqvmap/dist/maps/jquery.vmap.france.js',
                      '../themes/default/plugins/scripts/plugins/jqvmap.js'
                    ]
  };

var MODULE_OPTION_CONFIG = {
  parsley: {
    errorClass: 'is-invalid',
    successClass: 'is-valid',
    errorsWrapper: '<ul class="list-unstyled text-sm mt-1 text-muted"></ul>'
  }
}
