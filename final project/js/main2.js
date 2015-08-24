$(document).ready(function() {
  User.autoLogin(function() {
    KVrouter.start();
  });
  window.tabbar = new Tabbar();
  tabbar.loadCat();
  LiFocusEvent();
  reloadVerifyCodeImg();
  Area.init();

  KVrouter.bind('p', function(value) {
    switch(value){
      case 'myOrder':
        if (!User.isLogin()) {
          return false;
        }
        Order.generateList();
        $("#payBtn").html("支付");
        $("#payBtn").addClass("pay_btn");
        break;
      case 'food':
      case 'oOrder':
      case 'notice':
        $("#payBtn").html("结算");
    }
    var id = value;
    $('.left-col').hide();
    _(id).style.display = 'inline';
  });
  $("option.whitelist").css("display", "none");
  $("select").val("电子支付");
  $("form#pay").css("display", "none");
  $("#orderPay").html("<option value=\"电子支付\">电子支付</option>");

  (function _date(){
    var d = new Date();
    $('#year1').val(d.getFullYear());
    $('#month1').val(Number(d.getMonth()+1));
    $('#day1').val(Number(d.getDate()));
    $('#year2').val(d.getFullYear());
    $('#month2').val(Number(d.getMonth()+1));
    $('#day2').val(Number(d.getDate()));
  })();
});

var _loginframe = function(cb) {

  User.checkLogin(function(data) {

    if (data == 1) {
      User.checkUser(function(usrData) {
        if (usrData.manager == 1) {
          $("#enteradmin").show();
        }
        if (usrData.white == 1) {
          $("#orderPay").append("<option value=\"签单\">签单</option>");
        }
         else {
          $("a.whitelist").css("display", "none");
          $("orderPay").html("<option value=\"电子支付\">电子支付</option>");
        }

        cb();
      });
    }
  });
};

var Pay = {
  beforePay: function(jdata) {
      var _name = function(name) {
        return $("input[name=" + name + "]");
      };

      _name("app_id").val(jdata["app_id"]);
      _name("user_id").val(jdata["user_id"]);
      _name("user_name").val(jdata["user_name"]);
      _name("trade_date").val(jdata["trade_date"]);
      //        _name("trade_jnl").val(jdata["trade_jnl"]);
      _name("trade_jnl").val(jdata["trade_jnl"]);
      _name("iPlanetDirectoryPro").val(jdata["iPlanetDirectoryPro"]);
      _name("trade_mode").val(jdata["trade_mode"]);

      //xml.need to be more complete
      _name("trade_req").val(jdata["trade_req"]);

      _name("res_mode").val(jdata["res_mode"]);
      _name("res_notify").val(jdata["res_notify"]);
      _name("notify_url").val(jdata["notify_url"]);
      _name("trade_chars").val(jdata["trade_chars"]);
      _name("trade_type").val(jdata["trade_type"]);
      _name("sign_type").val(jdata["sign_type"]);
      _name("sign").val(jdata["sign"]);
  }
};

var User = {
  id: '',
  name: '',
  password: '',
  loginFlag: false,
  autoLogin: function(callback) {
    var uid = getCookie('user');
    var iPlanetDirectoryPro = getCookie('iPlanetDirectoryPro');
    if (iPlanetDirectoryPro) User.login(uid, iPlanetDirectoryPro, callback);
    else KVrouter.start();
  },
  login: function(uID, ssID, callback) {
    var iPlanetDirectoryPro = ssID || '';

    User.id = getCookie('user');
    User.password = _('password').value;
    _('loginDiv').style.display = 'none';
    _('loginLoading').style.display = 'inline';

    $.post("/fastfood/index.php/User/Login", {
      method: _('loginMethod').value,
      username: uID || _('userId').value,
      password: User.password,
      iPlanetDirectoryPro: iPlanetDirectoryPro
    }, function(data) {
      var user = eval('(' + data + ')');

      if (!user.status) {
        _('loginDiv').style.display = 'inline';
        _('loginLoading').style.display = 'none';
        alert('登录错误');
        location.reload();
        return false;
      } else {
        _loginframe(function() {});
      }
      User.loginFlag = true;
      User.name = user.name;
      _('userName').innerHTML = User.name;
      _('loginLoading').style.display = 'none';
      _('userDiv').style.display = 'inline';
      Order.loadOrder();
      if (callback) callback();
    });
  },
  isLogin: function() {
    if (!User.loginFlag) {
      alert('请先使用浙大通行证登录');
      KVrouter.remove();
      return false;
    }
    return true;
  },
  logout: function() {
    $.get("/fastfood/index.php/User/Logout", function() {

      _('loginDiv').style.display = 'inline';
      _('userDiv').style.display = 'none';
      _('loginLoading').style.display = 'none';
      User.loginFlag = false;

      location.reload();
    });

  },
  checkLogin: function(cb) {
    var iPlanetDirectoryPro = getCookie('iPlanetDirectoryPro') || "";

    var req = {
      iPlanetDirectoryPro: iPlanetDirectoryPro,
      username: "",
      password: ""
    };

    $.post("/fastfood/index.php/User/Login", {}, function(data) {

      var jdata = eval('(' + data + ')');

      if (jdata.status == 1) {
        cb(1);
      } else {
        cb(0);
      }

    });
  },
  checkUser: function(cb) {
    $.get("/fastfood/index.php/User/checkUser", function(data) {
      var jdata = eval('(' + data + ')');
      cb(jdata);
    });
  }
};

var Order = {
  list: {},
  oldOrder: {},
  add: function(id, amount) {
    this.list[id] = parseFloat(this.list[id]) || 0;
    this.list[id] += parseFloat(amount);
    Order.countCost();
  },
  remove: function(id) {
    delete this.list[id];
  },
  set: function(id, amount) {
    this.list[id] = parseFloat(amount);
    Order.countCost();
  },
  get: function(id) {
    return this.list[id];
  },
  countCost: function() {

    var count = 0;
    var cost = 0;
    for (var key in this.list) {
      if (parseInt(this.list[key]) < 1) continue;
      var order = selectByKey(tabbar.lunchData, 'id', key);
      cost += order.price * parseInt(this.list[key]);
      count++;
    }
    _('orderCount').innerHTML = count;
    _('orderCost').innerHTML = cost;
  },
  loadOrder: function() {
    $.get(APP + '/index.php/Order/showOrderToUser', {
      stuid: User.id,
      iPlanetDirectoryPro: getCookie('iPlanetDirectoryPro')
    }, function(data) {
     // console.log(User.id + data);
      var jdata = eval('(' + data + ')');
      Order.oldOrder = jdata;
      Order.showOrder(jdata);
    });
  },
  showOrder: function(data, key) {

    var order = data;
    var btn;
    var len = 0;
    $('#oldOrder').html('');
    if (data) {
      len = data.length;
    }
    for (var i = 0; i < len; i++) {
      var inner = '<tr><td>' + order[i].ordertime + '</td><td>' + order[i].food + '</td><td>' + order[i].phone + '</td><td>' + order[i].address + '</td><td>' + order[i].amount + '</td><td>' + order[i].status + '</td></tr>';
      $('#oldOrder').html($('#oldOrder').html() + inner);
    }
  },
  selectOrder: function() {
    var month1 = parseInt($('#month1').val()) < 10 ? '0' + $('#month1').val() : $('#month1').val();
    var month2 = parseInt($('#month2').val()) < 10 ? '0' + $('#month2').val() : $('#month2').val();
    var day1 = parseInt($('#day1').val()) < 10 ? '0' + $('#day1').val() : $('#day1').val();
    var day2 = parseInt($('#day2').val()) < 10 ? '0' + $('#day2').val() : $('#day2').val();
  
    var date1 = parseInt($('#year1').val() + month1 + day1);
    var date2 = parseInt($('#year2').val() + month2 + day2);

    var order = [];
    for (var i = 0, j = 0; i < Order.oldOrder.length; i++) {
      var date = parseInt(Order.oldOrder[i].ordertime.split(' ')[0].split('-').join(''))
      if (date >= date1 && date <= date2) {
        order[j++] = Order.oldOrder[i];
      }
    }
    Order.showOrder(order);
  },
  generateList: function() {
    $('#newOrder').html('');
    for (var key in this.list) {
      if (parseInt(this.list[key]) < 1) continue;
      var order = selectByKey(tabbar.lunchData, 'id', key);
      var inner = '<tr><td>' + order.name + '</td><td>' + order.price + '</td><td><button style="position:relative;top:-5px;margin:0 5px;" onclick="_(\'order' + key + '\').value++;Order.set(' + key + ', _(\'order' + key + '\').value);">+</button><input id="order' + key + '" type="text" class="min-input" value="' + this.list[key] + '" onchange="Order.set(' + key + ', this.value)"><button style="position:relative;top:-5px;margin:0 5px;" onclick="if(_(\'order' + key + '\').value > 0)_(\'order' + key + '\').value--;Order.set(' + key + ', _(\'order' + key + '\').value);">-</button></td><td><button class="btn btn-danger" onclick="Order.set(' + key + ', 0);Order.generateList();">删除</button></td></tr>';
      $('#newOrder').html($('#newOrder').html() + inner);
    }
  },
  send: function(type,callback) {
    if (!User.isLogin()) {
      return false;
    }
    if (_('myOrder').style.display != 'inline') return false;
    // var username = User.name;
    //var stuid = User.id;

    var address = $("#campusSelect").find("option:selected").text() + $("#areaSelect").find("option:selected").text() + $("#roomSelect").find("option:selected").text();
    var area = $("#areaSelect").find("option:selected").text();
    var phone = _('orderPhone').value;
    //var remark = _('orderRemark').value;
    var pay = _('orderPay').value;
    if (!phone || parseInt(phone) != phone) return alert('联系方式格式错误');
    if ($('#orderPay').val() === '签单') {
      _('payBtn').onclick = null;
      _('payBtn').innerHTML = '正在支付...';
      _('payBtn').className = 'btn btn-large';
    }
    var payData = [];
    for (var key in this.list) {
      var j = {
        id: key,
        //   stuid: stuid,
        //    username: username,
        amount: Order.list[key],
        address: address || "",
        phone: phone || "",
        area: area || "",
        //    remark: remark,
        pay: pay
      };
      if(j.amount > 0) payData.push(j);
    }
    payData = {
      order: payData
    };

    $.post('/fastfood/index.php/Order/addOrder', payData, function(data) {
      if (data) data = eval('(' + data + ')');

      if(type=="qiandan"){
        if (data && data.error) {
          alert(data.error);
        } else modalAlert('', '支付成功！');
      }else if(type == "dianzi"){
        if(callback){
          if(data.error){
            callback(data.error, data);
          }else{
            callback(null,data);
          }
        }
      }
     });
  },

  generateHelp: function() {

  }
};

var Area = {
  data: {},
  init: function() {
    $.getJSON('/fastfood/index.php/Area/showArea?key=all', function(data) {
      Area.data = data;
      Area.loadArea(1);
    });
  },
  loadArea: function(root) {
    $('#areaSelect').html(Area.generateArea(root));
    Area.loadRoom(_('areaSelect').value);
  },
  loadRoom: function(father) {
    $('#roomSelect').html(Area.generateLocation(father));
  },
  generateArea: function(root) {
    var id = root;
    var inner = '';
    for (var i = 0; i < this.data.length; i++) {
      if (this.data[i].root == id && this.data[i].father <= 5) {
        inner += '<option value="' + this.data[i].id + '">' + this.data[i].name + '</option>';
      }
    }
    return inner;
  },
  generateLocation: function(father) {
    var id = father;
    var inner = '';
    for (var i = 0; i < this.data.length; i++) {
      if (this.data[i].father == id) {
        inner += '<option value="' + this.data[i].id + '">' + this.data[i].name + '</option>'
      };
    };
    return inner;
  }
}

var Tabbar = function() {
  var that = this;
  this.tabs = {};
  this.catData = {};
  this.lunchData = {};
  this.loadCat = function() {
    $.getJSON('/fastfood/index.php/Category/showCategory', function(data) {

      that.catData = data;
      that.addTab('-1', 'all', 0, -1);
      for (var i = 0; i < data.length; i++) {
        that.addTab(data[i].id, data[i].name + ' ', data[i].father, i);
      };
    });
  }
  this.addTab = function(id, name, father, n) {
    var tab = document.createElement('a');
    var subtab = document.createElement('div');
    tab.id = id + 'Nav';
    tab.innerHTML = name == 'all' ? '全部' : name;
    tab.onclick = function() {
      _('banner').style.display = "none"
      that.selectedTab(this.id.slice(0, -3));
      $('.sub-tab').hide();
      this.parentNode.style.display = 'block';
      _(this.id.slice(0, -3) + 'Sub').style.display = 'block';
    };

    subtab.id = id + 'Sub';
    subtab.className = 'sub-tab';
    subtab.style.display = 'none';

    var parent = father > 0 ? father + 'Sub' : 'tabList';
    _(parent).appendChild(tab);
    _('tabList').appendChild(subtab);
    var page = document.createElement('div');
    page.id = id;
    page.className = 'page';
    _('content').appendChild(page);
    that.initPage(id, father);
    // if (n < 0) {
    //   this.selected = id;
    //   tab.className = 'selected';
    //   $('#' + id).show();
    // }
  }
  this.initPage = function(id, father) {
    function init(id, data) {
      _(id).innerHTML = '';
      var lunches = data;
      //console.log(that.lunchData);
      if (lunches)
      for (var i = 0; i < lunches.length; i++) {
        var lunchItem = document.createElement('div');
        lunchItem.id = id + '/' + lunches[i]['id'] + '/' + i;
        lunchItem.className = 'lunch-item panel panel-success';
        lunchItem.onclick = function() {
          $('#catModal').modal('toggle');
          var cat = this.id.split('/')[0];
          var n = this.id.split('/')[2];
          _('foodId').value = that.lunchData[cat][n]['id'];
          _('foodImg').src = that.lunchData[cat][n]['picture'] || '/fastfood/img/default-lunch.jpg';
          _('foodName').innerHTML = that.lunchData[cat][n]['name'];
          _('foodPrice').innerHTML = parseFloat(that.lunchData[cat][n]['price']);
          _('foodIngredient').innerHTML = that.lunchData[cat][n]['ingredient'];
          _('foodRemark').innerHTML = that.lunchData[cat][n]['remark'];
          _('orderCat').value = cat;
          _('orderNum').value = n;
        };
        var img = lunches[i]['picture'] ? lunches[i]['picture'] : '/fastfood/img/default-lunch.jpg';
        //console.log(lunches[i]['amount']);
        lunchItem.innerHTML = '\
<div class="lunch-img"><img src="' + img + '"/></div>\
<div class="lunch-info">\
<p style="color:#7F3F;font-weight:bold;">' + lunches[i]['name'] + '</p>\
<p>' + parseFloat(lunches[i]['price']) + ' 元/份</p>\
<p>剩余 ' + lunches[i]['amount'] + ' 份</p>\
<a>&lt;&lt;详细信息</a>\
</div>';
        _(id).appendChild(lunchItem);
      };
    }

    function loadWithCat(id, father, callback) {
      var key = father <= 0 ? 'father' : 'category';
      _(id).innerHTML = '加载中...';
      $.getJSON("/fastfood/index.php/Food/showFood?" + key + "=" + id, function(data) {
        that.lunchData[id] = data;
        callback(id, data);
      });
    }
    loadWithCat(id, father, init);
  };
  this.selectedTab = function(item) {
    if (this.selected) {
      _(this.selected + 'Nav').className = '';
      $('#' + this.selected).hide();
    };
    _(item + 'Nav').className = 'selected';
    $('#' + item).fadeIn();
    this.selected = item;
  };
};
function modalAlert(title, body){
  $('#alertModalTitle').html(title);
  $('#alertModalBody').html(body);
  $('#alertModal').modal('show');
}
function setCookie(name, value) {
  var argv = setCookie.arguments;
  var argc = setCookie.arguments.length;
  var expires = (argc > 2) ? argv[2] : null;
  if (expires != null) {
    var LargeExpDate = new Date();
    LargeExpDate.setTime(LargeExpDate.getTime() + (expires * 1000 * 3600 * 24));
  }
  document.cookie = name + "=" + escape(value) + ((expires == null) ? "" : ("; expires=" + LargeExpDate.toGMTString()));
}

function getCookie(Name) {
  var search = Name + "=";
  if (document.cookie.length > 0) {
    offset = document.cookie.indexOf(search);
    if (offset != -1) {
      offset += search.length;
      end = document.cookie.indexOf(";", offset);
      if (end == -1) end = document.cookie.length;
      return unescape(document.cookie.substring(offset, end));
    } else return ""
  }
}

function deleteCookie(name) {
  var expdate = new Date();
  expdate.setTime(expdate.getTime() - (86400 * 1000 * 1));
  setCookie(name, "", expdate);
}

function _(id) {
  return document.getElementById(id);
}

function selectByKey(obj, key, value) {

  for (var i in obj) {
    if (obj[i])
      for (var k = 0; k < obj[i].length; k++)
        if (obj[i][k][key] == value) return obj[i][k];
  };
}

function find(key, value, arr) {
  for (var i = 0; i < arr.length; i++) {
    if (arr[i][key] == value) {
      return i;
    };
  };
};

$("#orderPay").click(function() {

  if ($(this).val() === "签单") {
    $("form#pay").css("display", "none");
  } else {
    $("form#pay").css("display", "block");
  }
});

function LiFocusEvent(num) {
  $("ul.nav-list li a").click(function() {
    $("ul.nav-list li a").removeClass("active");
    $(this).addClass("active");
  });

  var hash = window.location.hash;
  var o = $("ul.nav-list li a");

  $("ul.nav-list li a").removeClass("active");
  if (hash == "#!p=food" || num == 0) {
    o.eq(0).addClass("active");
  } else if (hash == "#!p=myOrder" || num == 1) {
    o.eq(1).addClass("active");
  } else if (hash == "#!p=oOrder" || num == 2) {
    o.eq(2).addClass("active");
  } else if (hash == "#!p=notice" || num == 3) {
    o.eq(3).addClass("active");
  }
}

function reloadVerifyCodeImg() {
  $("img#verify").click(function() {
    $(this).attr("src", "/fastfood/index.php/Index/verify");
  });
};

function CheckOrder() {
  if($("#orderPay").val() == "签单"){
    Order.send("qiandan");
    LiFocusEvent(1);
  }else if($("#orderPay").val() == "电子支付" && $("#payBtn").hasClass("pay_btn")){
    Order.send("dianzi",function(err,data){
      if(err){
       alert(err);
       location = '/fastfood/';
      }else{
        Pay.beforePay(data);
        //document.getElementById("post_pay").submit();
        $('#payModal').modal('show');
      }
    });

    $("#payBtn").removeClass("pay_btn");
  }
}

/**
 * KVrouter
 * @type {Object}
 */
window.KVrouter = {
    options: {
      hashFormat: '#!',
      interval: true
    },
    oldHash: location.hash,
    interval: null,
    noHash: true,
    keys: {},
    kvdb: {},
    start: function(options) {

      // check hash automatically when hash changes
      if (typeof(window.onchange) == 'object') {
        window.addEventListener("hashchange", function() {
          KVrouter.checkHash();
        }, false);
      }
      else if (KVrouter.options.interval && !KVrouter.interval) {
        this.interval = setInterval(function() {
          if (location.hash != KVrouter.oldHash) {
            KVrouter.checkHash();
            KVrouter.oldHash = location.hash;
          };
        }, 200); // for ie6, ie7
      }

      // fire up the router by checking current URL
      this.checkHash();
    },
    checkHash: function(h) {

      //return false when hash doesn't match the format
      if (location.hash.substring(0, this.options.hashFormat.length) != this.options.hashFormat) {
        if (!this.noHash) {
          location.reload();
        };
        return false;
      }
      this.noHash = false;

      var hash = h || location.hash.substring(this.options.hashFormat.length);
      var kv = hash.split('&');
      var keyVal = {};

      //call function for the key with the value
      for (i = 0; i < kv.length; i++){
        keyVal[kv[i].split('=')[0]] = kv[i].split('=')[1];
      }
      for (var key in this.kvdb){
        if (!keyVal[key]) {
          delete this.kvdb[key];
        };
      }
      for (var key in keyVal){
        if (typeof(this.keys[key]) != 'undefined' && this.kvdb[key] != keyVal[key]) {
          this.kvdb[key] = keyVal[key];
          this.keys[key](keyVal[key]);
        }
      }
    },
    // bind function for key in hash
    bind: function(key, callback) {
      this.keys[key] = callback;
    },
    // key-value options
    get: function(key) {

      var hash = location.hash.substring(this.options.hashFormat.length);
      var kv = hash.split('&');
      var keyVal = {};

      //call function for the key with the value
      for (i = 0; i < kv.length; i++){
        keyVal[kv[i].split('=')[0]] = kv[i].split('=')[1];
      }
      return keyVal[key];
    },
    set: function(key, value) {
      var oldVal = this.kvdb[key];
      this.kvdb[key] = value;
      this._generateHash();
      if (typeof(this.keys[key]) != 'undefined') {
        this.keys[key](value);
      };
    },
    remove: function(key) {
      if (!key) return false;
      delete this.kvdb[key];
      this._generateHash();
    },
    clear: function() {
      this.kvdb = {};
      this.keys = {};
      this._generateHash();
    },
    _generateHash: function(){
      var hash = '';
      for (var key in this.kvdb){
        hash +=  key + '=' + this.kvdb[key] + '&';
      };
      location.hash = this.options.hashFormat.substring(1) + hash;
    }
};
