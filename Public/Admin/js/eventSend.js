(function () {
  var domain = window.location.host;
  var es = new EventSource('http://' + domain + '/index.php/Autopush/today_weblog');
  es.onmessage = function(e) {
    var obj = JSON.parse(e.data);
    console.log(obj);
    var users=obj.users;
    var simpleorder_count = obj.simpleorder.count;
    var simpleorder = obj.simpleorder.money;
    var bookorder_count = obj.bookorder.count;
    var bookorder = obj.bookorder.money;
    var companyorder_count = obj.companyorder.count;
    var companyorder = obj.companyorder.money;
    var speedorder_count = obj.speedorder.count;
    var speedorder = obj.speedorder.money;
    var weighorder_count = obj.weighorder.count;
    var weighorder = obj.weighorder.money;
    var company = obj.company;
    var bill = obj.bill;

    $('#users').html(users);
    $('#simpleorder_count').html(simpleorder_count+"单");
    $('#simpleorder').html(simpleorder);
    $('#bookorder_count').html(bookorder_count+"单");
    $('#bookorder').html(bookorder);
    $('#companyorder_count').html(companyorder_count+"单");
    $('#companyorder').html(companyorder);
    $('#speedorder_count').html(speedorder_count+"单");
    $('#speedorder').html(speedorder);
    $('#weighorder_count').html(weighorder_count+"单");
    $('#weighorder').html(weighorder);
    $('#company').html(company);
    $('#bill').html(bill);
  };
  es.addEventListener('myevent', function(e) {
     console.log(e.data);
  });
})();
