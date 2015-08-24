$(document).ready(function(){
  _post();
  function _post(){
    post_req(
      function(data){
        var data = JSON.parse(data);
        var pcData = data.pc;
        var mobileData = data.mobile;
        var jdata = pcData ? pcData.concat(mobileData) : mobileData;
        var rec = {};
        console.log(jdata)
        for(var j =0;j<jdata.length;j++){
          if(filter(jdata[j])){
            console.log(j)
            if(typeof rec [ jdata[j]["food"]] !== "object"){
              rec[ jdata[j]["food"] ] = {
                amount : 0,
                price : 0
              };
            }
            rec[jdata[j]["food"]]["remark"] = rec[jdata[j]["food"]]["remark"] || '';
            rec[jdata[j]["food"]]["remark"] += jdata[j]["remark"] ? jdata[j]["amount"] + '份 ' + jdata[j]['remark'] + '; ' : '';
            rec[jdata[j]["food"]]["amount"] += Number(jdata[j]["amount"]);
            rec[jdata[j]["food"]]["price"] = parseFloat(jdata[j]["price"]);
          }
        }
          $("table#realtime_table tfoot").html(gen_td(rec));
      }
    );
  }

  setInterval(function(){
    _post();
  },5000);
});

var gen_td = function(rec){
  var final_str = "";
  for(var i =0;i<_.keys(rec).length;i++){
    var key = _.keys(rec)[i];
    final_str += "<tr><td>"+key+"</td><td>"+rec[key]["amount"]+"</td><td>"+rec[key]["remark"]+"</td></tr>";
  }
  return final_str;
};
// "</td><td>"+rec[key]["price"] * rec[key]["amount"]+
var post_req = function(success_cb){
  $.ajax({
    url: APP + "/index.php/Admin/showMobileForm",
    method:"GET",
    success: function(data){
      success_cb(data);
    },
    error : function(data){
      alert("获取信息失败");
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
      if(Number(reg[1]) == date.getFullYear() &&
         Number(reg[2]) == date.getMonth()+1 &&
         Number(reg[3]) == date.getDate()
        ){
        //daytime filter
     //   console.log("reg[4]");
        if(
          Number(reg[4]) >= 13 &&
            date.getHours() >= 13 ||
            Number(reg[4]) <= 13 &&
            date.getHours() <= 13    
        ){
          return true;
        }else{
          return false;
        }
      }
      return false;
    }
  };

  if((jdata_o["status"] == "下单成功" || jdata_o["status"] == "烹饪中")   && _format(jdata_o["ordertime"]) == true){
    return true;
  }else{
    return false;
  }
};
