var nowTemp = new Date();
var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
 
var checkin = $('#dpd1').datepicker({
  onRender: function(date) {
    return date.valueOf() <= now.valueOf();
  }
}).on('changeDate', function(ev) {
  if (ev.date.valueOf() > checkout.date.valueOf()) {
    var newDate = new Date(ev.date)
    newDate.setDate(newDate.getDate());
    checkout.setValue(newDate);
  }
  checkin.hide();
  $('#dpd2')[0].focus();
}).data('datepicker');

var checkout = $('#dpd2').datepicker({
  onRender: function(date) {
    return date.valueOf() <= checkin.date.valueOf();
  }
}).on('changeDate', function(ev) {
  checkout.hide();
}).data('datepicker');

$(document).ready(function () {
 
window.setTimeout(function() {
    $("#alertclose").fadeTo(1500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
 
});

$("#formexport").submit(function () {

  var datepicker1 = $('#dpd1').val();
  var datepicker2 = $('#dpd2').val();

  if(datepicker1 == '' || datepicker2 == '')
  {
      alert('Please Fill all the required fields!');
      return false;
  }else{
    $("#img_spin").show();
    $('#btnexport').prop('disabled', true);
  }
  });

$("#formitem").submit(function () {

  var datepicker1 = $('#dpd1').val();

  if(datepicker1 == '')
  {
      alert('Please Fill all the required fields!');
      return false;
  }else{
    $('#btnexport').prop('disabled', true);
    $("#img_spin").show();
  }
  });

var options = {
  bg: '#5cb85c',

  // leave target blank for global nanobar
  target: document.getElementById('myDivId'),

  // id for new nanobar
  id: 'mynano'
};
var nanobar = new Nanobar( options );
nanobar.go( 30 ); 

nanobar.go(100);
