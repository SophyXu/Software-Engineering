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
  var foods = {};
  console.log(jdata.length)
      for(var j=0;j<jdata.length ;j++){
        if(filter(jdata[j]) == true){          
          //dep_key_value
          console.log(jdata[j])
           foods[jdata[j].food] = foods[jdata[j].food] || 0;
           foods[jdata[j].food] += parseInt(jdata[j].amount);
        }
      }
      var inner = '<table><thead><td width=300>套餐</td><td width=300>数量</td></thead></tbody>';
      for (var food in foods) {
        inner += '<tr><td>' + food + '</td><td>' + foods[food] + '</td></tr>'
      }
      inner += '</tbody></table>'
      $("div._table").html(inner);
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
