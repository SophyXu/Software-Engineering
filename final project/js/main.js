$(document).ready(function(){
	var tabbar = new Tabbar();
	tabbar.addTab('8rmb' , '8元套餐', 'selected');
	tabbar.addTab('10rmb', '10元套餐');
	tabbar.addTab('12rmb', '12元套餐');

	/*document.body.onmousemove = function(e){
		var mouseX = e.pageX;
		var mouseY = e.pageY;
		var width = document.documentElement.clientWidth;
		var height = document.documentElement.clientHeight;
		var left = (width / 2 - mouseX) / width * 10 - 5;
		var top = (height / 2 - mouseY) / height * 10 - 5;
		_('bg').style.left = left + '%';
		_('bg').style.top = top + '%';
	}*/
	$('.lunch-item').click(function(){
		showLunch(this);
	});
});
function _(id){
	return document.getElementById(id);
}
var Tabbar = function(){
	var that = this;
	this.tabs = {};
	this.addTab = function(id, name, selected){
		var tab = document.createElement('li');
		tab.id = id + 'Nav';
		tab.innerHTML = name;
		tab.onclick = function(){ that.selectedTab(this.id.slice(0, -3)); };
		_('tabList').appendChild(tab);
		var page = document.createElement('div');
		page.id = id;
		page.className = 'page';
		_('content').appendChild(page);
		that.initPage(id);
		if (selected) {
			this.selected = id;
			tab.className = 'selected';
			$('#' + id).show();
		}
	}
	this.initPage = function(id){
		function init(id, data){
			var lunches = data;
			for (var i = 0; i < lunches.length; i++) {
				var lunchItem = document.createElement('div');
				lunchItem.id = id + '/' + lunches[i]['id'];
				lunchItem.className = 'lunch-item';
				lunchItem.onclick = function(){$('#catModal').modal('toggle')};
				var img = lunches[i]['picture'] ? lunches[i]['picture'] : 'img/default-lunch.jpg';
				lunchItem.innerHTML = '<div class="lunch-img"><img src="' + img + '"/></div><div class="lunch-info">' + lunches[i]['name'] + '</div>';
				_(id).appendChild(lunchItem);
			};
		}
		function load(id, callback){
			var xmlhttp;
			if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					callback(id, xmlhttp.responseText);
				}
			}
			xmlhttp.open("GET","/ajax/test1.txt",true);
			xmlhttp.send();
		}
		//load(id, init);
		var data = [
			{'id':'001', 'name':'A套餐', 'img':'img/default-lunch.jpg', 'ingredient':'米饭100克 + 糖醋里脊 + 炒油菜 + 荷包蛋'},
			{'id':'002', 'name':'B套餐', 'img':'img/default-lunch.jpg', 'ingredient':'米饭100克 + 糖醋里脊 + 炒油菜 + 荷包蛋'},
			{'id':'003', 'name':'C套餐', 'img':'img/default-lunch.jpg', 'ingredient':'米饭100克 + 糖醋里脊 + 炒油菜 + 荷包蛋'},
			{'id':'004', 'name':'D套餐', 'img':'img/default-lunch.jpg', 'ingredient':'米饭100克 + 糖醋里脊 + 炒油菜 + 荷包蛋'},
			{'id':'005', 'name':'E套餐', 'img':'img/default-lunch.jpg', 'ingredient':'米饭100克 + 糖醋里脊 + 炒油菜 + 荷包蛋'},
		];
		init(id, data);
	}
	this.selectedTab = function(item){
		_(this.selected + 'Nav').className = '';
		_(item + 'Nav').className = 'selected';
		$('#' + this.selected).fadeOut();
		$('#' + item).fadeIn();
		this.selected = item;
	}
	this.showPage = function(id){

	}
}