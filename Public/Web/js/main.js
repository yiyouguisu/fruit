$(function() {
  var $window = $(window);
  var $stateSwitch = $('#state-switch');
  var sectionTop = $('.top').outerHeight() + 20;

  // initialize highlight.js
  hljs.initHighlightingOnLoad();

  // navigation
  $('a[href^="#"]').on('click', function(event) {
    event.preventDefault();
    var $target = $($(this).attr('href'));

    if ($target.length) {
      $window.scrollTop($target.offset().top - sectionTop);
    }
  });

  
   $('input[name="safebox"]').on({
    'init.bootstrapSwitch': function() {
      $('#safeHand').show();
       $('#safeNum0').hide();
        $('#safeNum1').show();
      
      
      
    },
    'switchChange.bootstrapSwitch': function(event, state) {
      $('#safeHand')[state ? 'show' : 'hide']();
       $('#safeNum0')[state ? 'hide' : 'show']();
        $('#safeNum1')[state ? 'show' : 'hide']();
    }
  });
  
  
     $('input[name="tpbox"]').on({
    'init.bootstrapSwitch': function() {
      $('#tpbox').hide();
    },
    'switchChange.bootstrapSwitch': function(event, state) {
      $('#tpbox')[state ? 'show' : 'hide']();
    }
  });
  
  

  // initialize all the inputs
  $('input[type="checkbox"],[type="radio"]').not('#create-switch').not('#events-switch').bootstrapSwitch();

  // state
  $('#state-switch-toggle').on('click', function () {
    $stateSwitch.bootstrapSwitch('toggleState');
  });
  $('#state-switch-on').on('click', function () {
    $stateSwitch.bootstrapSwitch('state', true);
  });
  $('#state-switch-off').on('click', function () {
    $stateSwitch.bootstrapSwitch('state', false);
  });
  $('#state-switch-state').on('click', function () {
    alert($stateSwitch.bootstrapSwitch('state'));
  });
});
