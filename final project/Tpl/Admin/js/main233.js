// For index/index
var AdminLogin = function(){
    $.post("/fastfood/index.php/User/CheckAdmin",
        {userId: document.getElementById("userId").value,
         password: document.getElementById("password").value,
         typeID: document.getElementById("typeID").value
        },
        function(data){
            var ret = eval('(' + data + ')');
            //console.log(ret.result);
            if (ret.result == 1){
                if (ret.type == "系统管理员") window.location.href = "/fastfood/index.php/Admin/index.html";
                    else window.location.href = "/fastfood/index.php/Admin/index2.html";
                } else {
                    window.location.href = "/fastfood/index.php";
                    return false;
                }
            });
}

// For Admin/index && Admin/index2
$.getJSON('/fastfood/index.php/User/ShowAdminInfo',function(data){
    var userinfo = data;
    //console.log(userinfo);
    document.getElementById("managerID").setAttribute("placeholder",userinfo.managerID);
    document.getElementById("manager_name").setAttribute("placeholder",userinfo.manager_name);
    document.getElementById("email").setAttribute("placeholder",userinfo.email);
    document.getElementById("address").setAttribute("placeholder",userinfo.address);
    document.getElementById("phone_number").setAttribute("placeholder",userinfo.phone_number);
    document.getElementById("manager_type").setAttribute("placeholder",userinfo.manager_type);
});

// For Admin/changepassword && Admin/changepassword2
$.getJSON('/fastfood/index.php/User/ShowChangePWD',function(data){
    var userinfo = data;
    //console.log(userinfo);
    document.getElementById("managerID").setAttribute("placeholder",userinfo.managerID);
    document.getElementById("email").setAttribute("placeholder",userinfo.email);
});

// For Admin/changepassword
var ChangePWD = function(){
    var password = document.getElementById("password").value;
    var new_password = document.getElementById("new_password").value;
    var confirm_new_password = document.getElementById("confirm_new_password").value;
    

    if (new_password != confirm_new_password) {
        alert("新密码前后两次输入不一致");
        window.location.href = "/fastfood/index.php/Admin/changepassword.html";
    }
    else {
        $.post("/fastfood/index.php/User/UpdatedAdminPWD",
                {userId: document.getElementById("managerID").placeholder,
                 old_password: document.getElementById("password").value,
                 new_password: new_password
                },
                function(data){
                    var ret = eval('(' + data + ')');
                    //console.log(ret);
                    if (ret.result){
                        alert("修改成功");
                        window.location.href = "/fastfood/index.php/Admin/changepassword.html";
                    } else {
                        alert("当前密码输入错误");
                        window.location.href = "/fastfood/index.php/Admin/changepassword.html";
                        return false;
                    }
                });
    }
}

// For Admin/changepassword2
var ChangePWD2 = function(){
    var password = document.getElementById("password").value;
    var new_password = document.getElementById("new_password").value;
    var confirm_new_password = document.getElementById("confirm_new_password").value;
    

    if (new_password != confirm_new_password) {
        alert("新密码前后两次输入不一致");
        window.location.href = "/fastfood/index.php/Admin/changepassword2.html";
    }
    else {
        $.post("/fastfood/index.php/User/UpdatedAdminPWD",
                {userId: document.getElementById("managerID").placeholder,
                 old_password: document.getElementById("password").value,
                 new_password: new_password
                },
                function(data){
                    var ret = eval('(' + data + ')');
                    //console.log(ret);
                    if (ret.result){
                        alert("修改成功");
                        window.location.href = "/fastfood/index.php/Admin/changepassword2.html";
                    } else {
                        alert("当前密码输入错误");
                        window.location.href = "/fastfood/index.php/Admin/changepassword2.html";
                        return false;
                    }
                });
    }
}

// For Admin/editinfo && Admin/editinfo2
$.getJSON('/fastfood/index.php/User/ShowEditInfo',function(data){
    var userinfo = data;
    //console.log(userinfo);
    document.getElementById("managerID").setAttribute("placeholder",userinfo.managerID);
    document.getElementById("manager_name").setAttribute("placeholder",userinfo.manager_name);
    document.getElementById("true_name").setAttribute("placeholder",userinfo.true_name);
    document.getElementById("email").setAttribute("placeholder",userinfo.email);
    document.getElementById("address").setAttribute("placeholder",userinfo.address);
    document.getElementById("phone_number").setAttribute("placeholder",userinfo.phone_number);
    document.getElementById("manager_type").setAttribute("placeholder",userinfo.manager_type);
});

// For Admin/editinfo
// warning: some of the keys may be null
var SaveInfo = function(){
    var managerID= document.getElementById("managerID").value;
    var manager_name= document.getElementById("manager_name").value;
    var true_name= document.getElementById("true_name").value;
    var email = document.getElementById("email").value;
    var address = document.getElementById("address").value;
    var phone_number = document.getElementById("phone_number").value;
    var manager_type = document.getElementById("manager_type").value;
    $.post("/fastfood/index.php/User/UpdatedAdminInfo",
            {managerID: managerID,
            manager_name: manager_name,
            true_name: true_name,
            email : email,
            address : address,
            phone_number : phone_number,
            manager_type : manager_type
            },
            function(data){
                    var ret = eval('(' + data + ')');
                    if (ret.result){
                        alert("修改成功");
                        window.location.href = "/fastfood/index.php/Admin/editinfo.html";
                    } else {
                        alert("修改失败");
                        window.location.href = "/fastfood/index.php/Admin/editinfo.html";
                    }
                });
}

// For Admin/editinfo2
// warning: some of the keys may be null
var SaveInfo2 = function(){
    var managerID= document.getElementById("managerID").value;
    var manager_name= document.getElementById("manager_name").value;
    var true_name= document.getElementById("true_name").value;
    var email = document.getElementById("email").value;
    var address = document.getElementById("address").value;
    var phone_number = document.getElementById("phone_number").value;
    var manager_type = document.getElementById("manager_type").value;
    $.post("/fastfood/index.php/User/UpdatedAdminInfo",
            {managerID: managerID,
            manager_name: manager_name,
            true_name: true_name,
            email : email,
            address : address,
            phone_number : phone_number,
            manager_type : manager_type
            },
            function(data){
                    var ret = eval('(' + data + ')');
                    if (ret.result){
                        alert("修改成功");
                        window.location.href = "/fastfood/index.php/Admin/editinfo2.html";
                    } else {
                        alert("修改失败");
                        window.location.href = "/fastfood/index.php/Admin/editinfo2.html";
                    }
                });
}

// For Admin/manager
$.getJSON("/fastfood/index.php/User/ShowManager",function(result){
    var cnt = 0;
    $.each(result, function(index, field){
        cnt++;
        var insertText = ""; 
        insertText += "<tr id=\"manager" + cnt + "\">";
        insertText += "<th>" + field.managerId      + "</th>";
        insertText += "<td>" + field.manager_name   + "</td>";
        insertText += "<td>" + field.password       + "</td>";
        insertText += "<td><button type=\"button\" class=\"btn btn-danger btn-xs\" onclick=\"DeleteManager(" + field.managerId + "); return false;\"> ";
        insertText += "删除账号</button></td>";
        insertText += "</tr>";
        document.getElementById("managerList").innerHTML += insertText; 
     });
});

// For Admin/manager && Admin/yudingmanager
var DeleteManager = function(managerId){
    //alert(managerId);
    $.post("/fastfood/index.php/User/DelManager",
        { managerId: managerId },
        function(data){
            var ret = eval('(' + data + ')');
            //console.log(ret);
            if (ret.result){
                alert("成功删除该管理员:  " + managerId);
                window.location.href = "/fastfood/index.php/Admin/manager";
            } else {
                alert("删除失败");
                window.location.href = "/fastfood/index.php/Admin/manager";
                return false;
            }
    });
}

// For Admin/manager
var AddManager = function(){
    var addID= document.getElementById("addID").value;
    var addName= document.getElementById("addName").value;
    var password= document.getElementById("password").value;
    $.post("/fastfood/index.php/User/AddManager",
        { addID: addID,
          addName: addName,
          password: password  
        },
        function(data){
            var ret = eval('(' + data + ')');
            //console.log(ret);
            if (ret.result){
                alert("成功添加该管理员:  " + addID);
                window.location.href = "/fastfood/index.php/Admin/manager";
            } else {
                alert("添加失败");
                window.location.href = "/fastfood/index.php/Admin/manager";
                return false;
            }
    });
}

// For Admin/yudingmanager
$.getJSON("/fastfood/index.php/User/ShowYuDingManager",function(result){
    var cnt = 0;
    $.each(result, function(index, field){
        cnt++;
        var insertText = ""; 
        insertText += "<tr id=\"manager" + cnt + "\">";
        insertText += "<th>" + field.managerId      + "</th>";
        insertText += "<td>" + field.manager_name   + "</td>";
        insertText += "<td>" + field.password       + "</td>";
        insertText += "<td><button type=\"button\" class=\"btn btn-danger btn-xs\" onclick=\"DeleteManager2(" + field.managerId + "); return false;\"> ";
        insertText += "删除账号</button></td>";
        insertText += "</tr>";
        document.getElementById("yudingManagerList").innerHTML += insertText; 
     });
});

// For Admin/yudingmanager
var DeleteManager2 = function(managerId){
    //alert(managerId);
    $.post("/fastfood/index.php/User/DelManager",
        { managerId: managerId },
        function(data){
            var ret = eval('(' + data + ')');
            //console.log(ret);
            if (ret.result){
                alert("成功删除该管理员:  " + managerId);
                window.location.href = "/fastfood/index.php/Admin/yudingmanager";
            } else {
                alert("删除失败");
                window.location.href = "/fastfood/index.php/Admin/yudingmanager";
                return false;
            }
    });
}

// For Admin/yudingmanager
var AddYuDingManager = function(){
    var addID= document.getElementById("addID").value;
    var addName= document.getElementById("addName").value;
    var password= document.getElementById("password").value;
    $.post("/fastfood/index.php/User/AddManager",
        { addID: addID,
          addName: addName,
          password: password  
        },
        function(data){
            var ret = eval('(' + data + ')');
            //console.log(ret);
            if (ret.result){
                alert("成功添加该管理员:  " + addID);
                window.location.href = "/fastfood/index.php/Admin/yudingmanager";
            } else {
                alert("添加失败");
                window.location.href = "/fastfood/index.php/Admin/yudingmanager";
                return false;
            }
    });
}

// For Admin/user
$.getJSON("/fastfood/index.php/User/ShowUser",function(result){
    var cnt = 0;
    $.each(result, function(index, field){
        cnt++;
        var insertText = ""; 
        insertText += "<tr id=\"user" + cnt + "\">";
        insertText += "<th>" + field.user_id     + "</th>";
        insertText += "<td>" + field.user_name   + "</td>";
        insertText += "<td>" + field.user_type   + "     ";
        insertText += "<button type=\"button\" class=\"btn btn-danger btn-xs\" onclick=\"ChangeNormal(" + field.user_id + "); return false;\"> ";
        insertText += "普通用户</button>";
        insertText += "     ";
        insertText += "<button type=\"button\" class=\"btn btn-danger btn-xs\" onclick=\"ChangeVIP(" + field.user_id + "); return false;\"> ";
        insertText += "VIP用户</button></td>";
        insertText += "<td>" + field.user_valid  + "     ";
        insertText += "<button type=\"button\" class=\"btn btn-danger btn-xs\" onclick=\"Valid_it(" + field.user_id + "); return false;\"> ";
        insertText += "验证</button></td>";
        insertText += "</tr>";
        document.getElementById("userList").innerHTML += insertText; 
     });
});

// For Admin/User
var ChangeNormal = function(user_id){
    //alert(managerId);
    $.post("/fastfood/index.php/User/ChangeNormal",
        { user_id: user_id },
        function(data){
            var ret = eval('(' + data + ')');
            //console.log(ret);
            if (ret.result){
                alert("修改成功");
                window.location.href = "/fastfood/index.php/Admin/user";
            } else {
                alert("修改失败");
                window.location.href = "/fastfood/index.php/Admin/user";
                return false;
            }
    });
}

// For Admin/User
var ChangeVIP = function(user_id){
    //alert(managerId);
    $.post("/fastfood/index.php/User/ChangeVIP",
        { user_id: user_id },
        function(data){
            var ret = eval('(' + data + ')');
            //console.log(ret);
            if (ret.result){
                alert("修改成功");
                window.location.href = "/fastfood/index.php/Admin/user";
            } else {
                alert("修改失败");
                window.location.href = "/fastfood/index.php/Admin/user";
                return false;
            }
    });
}

// For Admin/User
var Valid_it = function(user_id){
    //alert(managerId);
    $.post("/fastfood/index.php/User/ValidUser",
        { user_id: user_id },
        function(data){
            var ret = eval('(' + data + ')');
            //console.log(ret);
            if (ret.result){
                alert("成功验证该用户:  " + user_id);
                window.location.href = "/fastfood/index.php/Admin/user";
            } else {
                alert("验证失败");
                window.location.href = "/fastfood/index.php/Admin/user";
                return false;
            }
    });
}

var deleteWhiteAccount;
var deleteBlackAccount;
var deleteFlightID;
var deleteHotelID;

// For Admin/white
function showWhite() { 
    $.getJSON("/fastfood/index.php/User/showWhiteList",function(result){
        $.each(result, function(index, field){
            var insertText = ""; 
            insertText += "<tr>";
            insertText += "<td>"  + field.user_id + "</td>";
            insertText += "<td>" + field.user_truename + "</td>";
            insertText += "<td>" + field.user_type + "</td>";
            insertText += "<td><button type='button' class='btn btn-danger btn-xs' ";
            insertText += 'onclick=confirmDeleteWhiteFunction(' + field.user_id +')>';
            insertText += "删除账号</button></td>";
            insertText += "</tr>";
            document.getElementById("whiteList").innerHTML += insertText; 
        });
    });                        
}

// For Admin/white
function confirmDeleteWhiteFunction(index) {
    deleteWhiteAccount = index;
    $('#confirmDeleteWhite').modal('show'); 
} 

// For Admin/white
function deleteWhite() {
    $.post("/fastfood/index.php/User/deleteWhiteList", 
        {whiteAccout: deleteWhiteAccount}, 
        function(data){
            var ret = eval('(' + data + ')');
            //console.log(ret);
            if (ret.result){
                alert("成功删除该用户:  " + deleteWhiteAccount);
                window.location.href = "/fastfood/index.php/Admin/white";
            } else {
                alert("删除失败");
                window.location.href = "/fastfood/index.php/Admin/white";
                return false;
            }
        }
    ); 
}

// For Admin/white
function addWhite() {
    var id = document.getElementById("accountID").value; 
    $.post("/fastfood/index.php/User/addWhiteList", {accountID: id}, 
        function(data){
            var ret = eval('(' + data + ')');
            //console.log(ret);
            if (ret.result){
                alert("成功添加该用户:  " + id);
                window.location.href = "/fastfood/index.php/Admin/white";
            } else {
                alert("添加失败");
                window.location.href = "/fastfood/index.php/Admin/white";
                return false;
            }
        }
    ); 
}

// For Admin/black
function showBlack() { 
    $.getJSON("/fastfood/index.php/User/showBlackList",function(result){
        $.each(result, function(index, field){
            var insertText = ""; 
            insertText += "<tr>";
            insertText += "<td>"  + field.user_id + "</td>";
            insertText += "<td>" + field.user_truename + "</td>";
            insertText += "<td>" + field.user_type + "</td>";
            insertText += "<td><button type='button' class='btn btn-danger btn-xs' ";
            insertText += 'onClick=confirmDeleteBlackFunction(' + field.user_id +')>';
            insertText += "删除账号</button></td>";
            insertText += "</tr>";
            document.getElementById("blackList").innerHTML += insertText; 
        });
    });                      
}

// For Admin/black
function confirmDeleteBlackFunction(index) {
    deleteBlackAccount = index;
    $('#confirmDeleteBlack').modal('show'); 
}

// For Admin/black
function deleteBlack() {
    $.post("/fastfood/index.php/User/deleteBlackList", {blackAccout: deleteBlackAccount}, function(data){
            var ret = eval('(' + data + ')');
            //console.log(ret);
            if (ret.result){
                alert("成功删除该用户:  " + deleteBlackAccount);
                window.location.href = "/fastfood/index.php/Admin/black";
            } else {
                alert("删除失败");
                window.location.href = "/fastfood/index.php/Admin/black";
                return false;
            }
        }
    ); 
}

// For Admin/black
function addBlack() {
    var id = document.getElementById("accountID").value; 
    $.post("/fastfood/index.php/User/addBlackList", {accountID: id}, function(data){
            var ret = eval('(' + data + ')');
            //console.log(ret);
            if (ret.result){
                alert("成功添加该用户:  " + id);
                window.location.href = "/fastfood/index.php/Admin/black";
            } else {
                alert("添加失败");
                window.location.href = "/fastfood/index.php/Admin/black";
                return false;
            }
        }); 
}

// For Admin/ordermanagehotel
$.getJSON("/fastfood/index.php/User/showHotel",function(result){
    $.each(result, function(index, field){
        var insertText = ""; 
        insertText += "<tr>";
        insertText += "<th scope='row'>" + field.orderID +"</th>";
        insertText += "<td>"  + field.hotelName + "</td>";
        insertText += "<td>" + field.hotelLocation + "</td>";
        insertText += "<td>" + field.roomType + "</td>";
        insertText += "<td>" + field.checkInTime + "</td>";
        insertText += "<td>" + field.checkOutTime + "</td>";
        insertText += "<td>" + field.price + "</td>";
        insertText += "<td><button type='button' class='btn btn-danger btn-xs' ";
        insertText += 'onClick=confirmDeleteHotelFunction(' + field.orderID +')>';
        insertText += "删除订单</button></td>";
        insertText += "</tr>";
        document.getElementById("hotelList").innerHTML += insertText;       
    });
}); 

// For Admin/ordermanagehotel
function confirmDeleteHotelFunction(index) {
    deleteHotelID = index;
    //alert(deleteHotelID);
    $('#confirmDeleteHotel').modal('show'); 
}

// For Admin/ordermanagehotel
function deleteHotel() {
    $.post("/fastfood/index.php/User/deleteHotel", {hotelID: deleteHotelID}, 
        function(data){
            var ret = eval('(' + data + ')');
            //console.log(ret);
            if (ret.result){
                alert("成功删除该订单:  " + deleteHotelID);
                window.location.href = "/fastfood/index.php/Admin/ordermanagehotel";
            } else {
                alert("删除失败");
                window.location.href = "/fastfood/index.php/Admin/ordermanagehotel";
                return false;
            }
        }
    ); 
}

// For Admin/ordermanageflight
$.getJSON("/fastfood/index.php/User/showFlight",function(result){
    $.each(result, function(index, field){
        var insertText = ""; 
        insertText += "<tr>";
        insertText += "<th scope='row'>" + field.orderID +"</th>";
        insertText += "<td>"  + field.flightID + "</td>";
        insertText += "<td>" + field.flightCompanyName + "</td>";
        insertText += "<td>" + field.planeType + "</td>";
        insertText += "<td>" + field.takeOffTime + "</td>";
        insertText += "<td>" + field.landTime + "</td>";
        insertText += "<td>" + field.departure + "</td>";
        insertText += "<td>" + field.destination + "</td>";
        insertText += "<td>" + field.price + "</td>";
        insertText += "<td><button type='button' class='btn btn-danger btn-xs' ";
        insertText += 'onClick=confirmDeleteFlightFunction(' + field.orderID +')>';
        insertText += "删除订单</button></td>";
        insertText += "</tr>";
        document.getElementById("flightList").innerHTML += insertText;          
    });
}); 

// For Admin/ordermanageflight
function confirmDeleteFlightFunction(index) {
    deleteFlightID = index;
    $('#confirmDeleteFlight').modal('show'); 
}

// For Admin/ordermanageflight
function deleteFlight() {
    $.post("/fastfood/index.php/User/deleteFlight", {flightID: deleteFlightID}, 
        function(data){
            var ret = eval('(' + data + ')');
            //console.log(ret);
            if (ret.result){
                alert("成功删除该订单:  " + deleteFlightID);
                window.location.href = "/fastfood/index.php/Admin/ordermanageflight";
            } else {
                alert("删除失败");
                window.location.href = "/fastfood/index.php/Admin/ordermanageflight";
                return false;
            }
        }
    ); 
}
