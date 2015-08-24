window.onload = function(){

  window.report_json = {};
  post_req(
    function(data){
      window.jdata = JSON.parse(data);
      render(jdata);
    }
  );
  function _date(){
    var d = new Date();
    // $("p#time").html(d.getFullYear() + " 年 " + Number(d.getMonth()+1) + " 月 " + d.getDate() + " 日 ");
    $('#year').val(d.getFullYear());
    $('#month').val(Number(d.getMonth()+1));
    $('#day').val(d.getDate());
    if (d.getHours() +1 < 12) {
      $('#am').attr('selected', 'selected')
    }else{
      $('#pm').attr('selected', 'selected')
    }
  };
  _date();

};

function genReport () {
  if(!checkQuery()) return false;
  report_json = {};
  render(jdata);
}
function checkQuery () {
  return true;
}
function render (jdata) {
  
      var food_record = [];
      for(var j=0;j<jdata.length ;j++){
        if(filter(jdata[j]) == true){
          if( typeof report_json [  jdata[j]["area"]  ] !== "object"){
            report_json[ jdata[j]["area"] ] = {};
          }

          if(report_json[ jdata[j]["area"] ][ jdata[j]["food"] ] == undefined){
            report_json[ jdata[j]["area"] ][ jdata[j]["food"] ] = {
              amount:Number(jdata[j]["amount"]),
              price : jdata[j]["price"]
            };

          }else{
            report_json[ jdata[j]["area"] ][ jdata[j]["food"] ]["amount"] += Number(jdata[j]["amount"]);
          }
        }
      }
      var final_string = "";
      for(var i =0;i<_.keys(report_json).length;i++){

        var key = _.keys(report_json)[i];

        var info_string = "";

        info_string += gen_info(report_json[key]);


        final_string += gen_table_header(_.keys(report_json)[i],info_string);
      }

      $("div.select_area").html(gen_option_buttons(_.keys(report_json)));
      $("div._table").html(final_string);
      add_checkbox_listener();
}
var gen_table_header = function(jdata,info){
  var final_str =
    '<table class="report list_report" id=_area_'+jdata+'>'
                 + '<tr><td colspan="3"><strong>'+jdata+'</strong></td></tr>'
                 + '<tr><td><strong>套餐</strong></td><td><strong>单价</strong></td><td><strong>数量</strong></td></tr>'
                 + '<tr class="info">'+info+'</tr>'
                 + '</table>';
  return final_str;
};

var gen_info = function(jdata){
  var final_str = "";

  for(var i =0 ;i<_.keys(jdata).length;i++){
    var key = _.keys(jdata)[i];

    var str= "<tr><td>"+key+"</td><td>"+jdata[key]["price"]+"</td>"+"<td>"+jdata[key]["amount"]+"</td></tr>";
    final_str += str;
  }
  return final_str;
};

var post_req = function(success_cb){
  $.ajax({
    url: APP + "/index.php/Admin/showForm",
    method:"GET",
    success: function(data){
      success_cb(data);
    },
    error : function(data){
      alert("获取信息失败");
    }
  });
};

var gen_option_buttons = function(keys){
  var keys = keys || [];
  var final_str = "区域：";
  for(var i =0;i<keys.length;i++){
    var model = '<div class="checkbox checkbox-inline huowu-list"><label><input type="checkbox" checked="checked">'+keys[i]+'</label></div>';
    final_str += model;
  }
  return final_str;
};


var add_checkbox_listener = function(){
  $("input").bind("click",function(){
    if(this.checked == false){
      $("#_area_"+ $(this).parent().text()).css("display","none");
    }else{
      $("#_area_"+ $(this).parent().text()).css("display","table");
    }
  });
};


var filter = function(jdata_o){
  var date = new Date();
  function _format(string){
    if(string == undefined){
      return false;
    }else{
      var reg = /([0-9]*)-([0-9]*)-([0-9]*) ([0-9]+)/.exec(string);
        if(Number(reg[1]) == $('#year').val() &&
         Number(reg[2]) == $('#month').val() &&
         Number(reg[3]) == $('#day').val()
        ){
        //daytime filte
          return (Number(reg[4]) < 13 && $('#m').val() == 'am') || (Number(reg[4]) >= 13 && $('#m').val() == 'pm');
      }
      return false;
    }
  };

  if((jdata_o["status"] == "送餐途中" || jdata_o["status"] == "餐已收到") && _format(jdata_o["ordertime"]) == true){

    return true;
  }else{
    return false;
  }
};
