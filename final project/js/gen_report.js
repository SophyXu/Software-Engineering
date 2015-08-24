$(document).ready(function(){
  $("table.price-info tbody").html(gen_tr({})+gen_total({}));

  var report_json = {};

  post_req(
    function(data){

      var jdata = JSON.parse(data);
      for(var j=0;j<jdata.length ;j++){

        if( typeof report_json [ jdata[j]["area"] ] !== "object")
          report_json [ jdata[j]["area"] ] = {};
        if(typeof report_json [ jdata[j]["area"] ][ ""+jdata[j]["dep"] ] != "object")
          report_json [ jdata[j]["area"] ][ ""+jdata[j]["dep"] ] = {
            "8" : 0,
            "10": 0,
            "15": 0
          };

        report_json [ jdata[j]["area"] ][ ""+jdata[j]["dep"] ][ ""+jdata[j]["price"] ] += Number(jdata[j]["amount"]);

      }


      function _getmax(){
        var max = 0;
        for (var i in report_json){
          if(_.keys(report_json[i]).length > max){
            max = _.keys(report_json[i]).length;

          }
        }
        return max;
      }

      var m = _getmax();

      $("table.price-info tbody").each(function(index,elem){

        $(elem).html(parse_addr(report_json[index],m));
      });
    });
});

var parse_addr = function(json_data,max){
  var final_str = "";

  var final_sta = {
    "8":0,
    "10":0,
    "15":0
  };

  if(typeof json_data === "object"){
    for(var i in json_data){
      final_str += gen_tr(
        {
          dep : i,
          "8" : json_data[i]["8"],
          "10" : json_data[i]["10"],
          "15" : json_data[i]["15"]
        }
      );

      final_sta["8"] += json_data[i]["8"];
      final_sta["10"] += json_data[i]["10"];
      final_sta["15"] += json_data[i]["15"];

    }
    var len = _.keys(json_data).length || 0;

    for(var k=0;k<max-len;k++){
      final_str += gen_tr_empty();

    }

    final_str += gen_total(final_sta);
    return final_str;

  }else{

    for(var k=0;k<max;k++){

      final_str += gen_tr_empty();

    }
    return final_str + gen_total({});
  }
};

var gen_tr_empty = function(){
  return "<tr>"
       + "<td>&nbsp;</td>"
       + "<td>&nbsp;</td>"
       + "<td>&nbsp;</td>"
       + "<td>&nbsp;</td>"
       + "</tr>";
};

var gen_tr = function(json){
  var json_data = {
    dep : json["dep"] || "",
    eight : json["8"] || 0,
    ten : json["10"] || 0,
    fifteen : json["15"] || 0
  };

  return "<tr>"
       + "<td>"+json_data["dep"]+"</td>"
       + "<td>"+json_data["eight"]+"</td>"
       + "<td>"+json_data["ten"]+"</td>"
       + "<td>"+json_data["fifteen"]+"</td>"
       + "</tr>";
};

var gen_total = function(json){
  var json_data = {
    eight : json["8"] || 0,
    ten : json["10"] || 0,
    fifteen : json["15"] || 0
  };

  return "<tr>"
       + "<td><strong>累计：</strong></td>"
       + "<td>"+json_data["eight"]+"</td>"
       + "<td>"+json_data["ten"]+"</td>"
       + "<td>"+json_data["fifteen"]+"</td>"
       + "</tr>";
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
