$(document).ready(function(){
  var search_model = {
    username : "",
    stuid:"",
    dep:"",
    food:"",
    ordertime:"",
    address:"",
    phone:"",
    status:"",
    pay:""
  };

  $("input[name='search_submit']").click(function(){
    var key = $("select#search_type").val() || "username";
    var val = $("input#query").val() || "";

    search_model[key] = val;

    $.post(APP+"/index.php/Order/searchOrder",search_model,function(data){
      var jdata = JSON.parse(data) || {};
      showOrder(jdata,'all');
    });
  });
});