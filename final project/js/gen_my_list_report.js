var dep_key_value = {};
window.onload = function(){
  window.report_json = {};
  post_req(render);
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
  $("div._table").html('');
  report_json = {};
  post_req(render);
}
function checkQuery () {
  if (!$('#sid').val()) {
    alert('请输入设备号');
    return false;
  }else{
    return true;
  }
}
function render (data) {
  var jdata = JSON.parse(data);
  var food_record = [];
      for(var j=0;j<jdata.length ;j++){
        if(filter(jdata[j]) == true){          
          //dep_key_value
          if(typeof dep_key_value[ [jdata[j]["address"]] ] !== "object"){
            dep_key_value[ jdata[j]["address"]] = {
              area : "",
              contact:[]
            };
            dep_key_value[ jdata[j]["address"] ]["area"] = jdata[j]["area"];
            dep_key_value[ jdata[j]["address"] ]["contact"].push({
              username: jdata[j]["username"],
              phone: jdata[j]["phone"]
            });

          }else{
            var flag = 1;
            dep_key_value[ jdata[j]["address"] ]["area"] = jdata[j]["area"];

            for(var k =0;k<dep_key_value[jdata[j]["address"]]["contact"].length;k++){
              var ii = dep_key_value[jdata[j]["address"]]["contact"];

              if(ii[k]["username"] == jdata[j]["username"] && ii[k]['phone'] == jdata[j]["phone"]){
                flag = 0;
                break;
              }
            }
            if(flag == 1){
              dep_key_value[ jdata[j]["address"] ]["contact"].push({
                username: jdata[j]["username"],
                phone: jdata[j]["phone"]
              });

            }
          }


          if( typeof report_json [  jdata[j]["address"]  ] !== "object"){
            report_json[ jdata[j]["address"] ] = {};

          }

          if(report_json[ jdata[j]["address"] ][ jdata[j]["food"] ] == undefined){
            report_json[ jdata[j]["address"] ][ jdata[j]["food"] ] =
              {
                price : jdata[j]["price"],
                amount : Number(jdata[j]["amount"])
              };
          }else{
            report_json[ jdata[j]["address"] ][ jdata[j]["food"] ]["amount"] += Number(jdata[j]["amount"]);

          }
        }
      }
      var final_string = "";
      for(var i =0;i<_.keys(report_json).length;i++){

        var key = _.keys(report_json)[i];

        var info_string = "";

        info_string += gen_info(report_json[key]);

        final_string += gen_table_header(_.keys(report_json)[i],info_string,        gen_contact(_.keys(report_json)[i]));
      }

      $("div.select_district").html(gen_option_buttons(_.keys(report_json)));
      $("div._table").html(final_string);

      add_checkbox_listener();
}
var gen_table_header = function(jdata,info,contact){

  var final_str =
    '<table class="report list_report _area_'+dep_key_value[jdata]["area"]+'">'
                 + '<tr><td colspan="3"><strong>'+jdata+'</strong></td></tr>'+
    "<tr><td colspan='3'>"+contact+"</td></tr>"
                 + '<tr><td><strong>套餐</strong></td><td><strong>单价</strong></td><td><strong>数量</strong></td></tr>'

                 + '<tr class="info">'+info+'</tr>'
                 + '</table>';
  return final_str;
};

var gen_info = function(jdata){
  var final_str = "";

  for(var i =0 ;i<_.keys(jdata).length;i++){
    var key = _.keys(jdata)[i];

    var str= "<tr><td>"+key+"</td>"+"<td>"+jdata[key]["price"]+"</td><td>"+jdata[key]["amount"]+"</td></tr>";
    final_str += str;
  }
  return final_str;
};

var post_req = function(success_cb){
  $.ajax({
//    url: APP + "/index.php/Admin/showForm",
    url: APP + "/index.php/Sender/showMyOrder?sid=" + $('#sid').val(),
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
  var cache_arr = [];
  var final_str = "送货区域：";
  for(var i =0;i<keys.length;i++){
    var flag = 1;
    for(var k=0;k<cache_arr.length;k++){
      if(cache_arr[k] == dep_key_value[keys[i]]["area"]){
        flag = 0;
        break;
      }
    }

    if(flag){
      cache_arr.push(dep_key_value[keys[i]]["area"]);
      var model = '<div class="checkbox checkbox-inline huowu-list"><label><input type="checkbox" checked="checked">'+dep_key_value[keys[i]]["area"]+'</label></div>';
      final_str += model;

    }
  }
  return final_str;
};


var add_checkbox_listener = function(){
  $("input").bind("click",function(){
    if(this.checked == false){
      $("._area_"+ $(this).parent().text()).css("display","none");
    }else{
      $("._area_"+ $(this).parent().text()).css("display","table");
    }
  });
};

var gen_contact = function(address){
  var final_str = "";
  var ii = dep_key_value[address]["contact"];

  for(var k =0;k<ii.length;k++){
    final_str += (ii[k]["username"]+" "+ii[k]["phone"]+"<br/>");
  }
  return final_str;


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
