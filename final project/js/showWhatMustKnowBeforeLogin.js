
function showWhatMustKnowBeforeLogin() {

	var shield = document.createElement("DIV");
	shield.id = "shield";
	shield.style.position = "absolute";
	shield.style.left = "0px";
	shield.style.top = "0px";
	shield.style.width = "100%";
	shield.style.height = document.body.scrollHeight + "px";
	shield.style.background = "#333";
	shield.style.textAlign = "center";
	shield.style.zIndex = "10000";
	shield.style.filter = "alpha(opacity=0)";
	shield.style.opacity = 0;
	
	var alertFram = document.createElement("DIV");
	alertFram.id = "alertFram";
	alertFram.style.position = "absolute";
	alertFram.style.left = "50%";
	alertFram.style.top = "50%";
	alertFram.style.marginLeft = "-225px";
	alertFram.style.marginTop = -75 + document.documentElement.scrollTop + "px";
	alertFram.style.width = "450px";
	alertFram.style.height = "200px";
	alertFram.style.background = "#fff";
	alertFram.style.textAlign = "center";
	alertFram.style.lineHeight = "150px";
	alertFram.style.zIndex = "10001";
	
	var strHtml = "";
	strHtml = "<div style=\"list-style:none;height:200px;margin:0px;padding:0px;width:100%;text-align:left;font-size:12px;line-height:28px;border:1px solid #5c7e4c;\">";
	strHtml += "<div style='text-align:center;background:#5c7e4c;font-weight:bold;font-size:14px;'>浙大通行证登录须知</div>";
	strHtml += "<div style='padding-left:5px;'>1.首次使用浙大通行证登录，请先激活帐号，教职工用户名为职工号，</div>";
	strHtml += "<div style='padding-left:5px;'>学生为学号，请<a href='http://zuinfo.zju.edu.cn/ActiveChoose.jsp'>点击此处激活帐号，获取密码</a></div>";
        strHtml += "<div style='padding-left:5px;'>2.为了您使用更加方便，建议<a href='https://zjuam.zju.edu.cn:8443/amserver/config/auth/default/cert_zjuam.cer'>安装浙江大学根证书</a></div>";
        strHtml += "<div style='padding-left:5px;'>3.如果无法正常登录，请致电87951669</li></div>";
        strHtml += "<div style='padding-left:5px;'>4.如需进一步帮助，可查询<a href='http://zuinfo.zju.edu.cn/handbook.do'>帮助手册</a></div>";
        strHtml += "<div style='float:right;'><img src='http://zuinfo.zju.edu.cn/know/close.gif' onclick=\"doCancel()\" /></div>";
	strHtml += "</div>\n";
	
	alertFram.innerHTML = strHtml;
	document.body.appendChild(alertFram);
	document.body.appendChild(shield);
	
	this.setOpacity = function (obj, opacity) {
		if (opacity >= 1) {
			opacity = opacity / 100;
		}
		try {
			obj.style.opacity = opacity;
		}
		catch (e) {
		}
		try {
			if (obj.filters.length > 0 && obj.filters("alpha")) {
				obj.filters("alpha").opacity = opacity * 150;
			} else {
				obj.style.filter = "alpha(opacity=\"" + (opacity * 150) + "\")";
			}
		}
		catch (e) {
		}
	};
	var c = 0;
	this.doAlpha = function () {
		if (++c > 20) {
			clearInterval(ad);
			return 0;
		}
		setOpacity(shield, c);
	};
	var ad = setInterval("doAlpha()", 1);
	
	this.doCancel = function () {
		document.body.removeChild(alertFram);
		document.body.removeChild(shield);
	};

	document.body.onselectstart = function () {
		return false;
	};
	document.body.oncontextmenu = function () {
		return false;
	};
}

 