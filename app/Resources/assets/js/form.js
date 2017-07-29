//Select2
$(".select2").select2({
    width: '100%'
});

//Select2 tags
$(".tags").select2({tags: true, width: '100%'});

//Js Code
$(".datetimepicker").datetimepicker({
  autoclose: true,
  componentIcon: '.mdi.mdi-calendar',
  navIcons: {
    rightIcon: 'mdi mdi-chevron-right',
    leftIcon: 'mdi mdi-chevron-left',

  },
  language: 'de'
});

//Date range picker

$(".daterange").daterangepicker();

$(".datetimerange").daterangepicker({
  timePicker: true,
  timePickerIncrement: 30,
  locale: {
    format: 'DD.MM.YYYY h:mm'
  }
});
