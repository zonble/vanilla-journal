var vj = Class.create();

vj.notsupport = 0;
vj.currarea;

//Cookie

// Copyright (c) 1996-1997 Athenia Associates.
// http://www.webreference.com/js/
// License is granted if and only if this entire
// copyright notice is included. By Tomer Shiran.

vj.cookie = {

setCookie: function (name, value, expires, path, domain, secure) {
	      var curCookie = name + "=" + escape(value) + ((expires) ? "; expires=" + expires.toGMTString() : "") + ((path) ? "; path=" + path : "") + ((domain) ? "; domain=" + domain : "") + ((secure) ? "; secure" : "");
	      document.cookie = curCookie;
	   },

getCookie: function (name) {
	      var prefix = name + '=';
	      var c = document.cookie;
	      var nullstring = '';
	      var cookieStartIndex = c.indexOf(prefix);
	      if (cookieStartIndex == -1)
		 return nullstring;
	      var cookieEndIndex = c.indexOf(";", cookieStartIndex + prefix.length);
	      if (cookieEndIndex == -1)
		 cookieEndIndex = c.length;
	      return unescape(c.substring(cookieStartIndex + prefix.length, cookieEndIndex));
	   },

deleteCookie: function (name, path, domain) {
		 if (vj.cookie.getCookie(name))
		    document.cookie = name + "=" + ((path) ? "; path=" + path : "") + ((domain) ? "; domain=" + domain : "") + "; expires=Thu, 01-Jan-70 00:00:01 GMT";
	      },

fixDate: function (date) {
	    var base = new Date(0);
	    var skew = base.getTime();
	    if (skew > 0)
	       date.setTime(date.getTime() - skew);
	    return date;
	 },

quickhide: function (f) {
	      var now = new Date();
	      vj.cookie.fixDate(now);
	      now.setTime(now.getTime() + 365 * 24 * 60 * 60 * 1000);
	      vj.cookie.setCookie('vjquickhide', 'hide', '', '/', '');
	   }
}


/* Utilities */

vj.util = {

exitconfirm: function() {
		var agree = window.confirm("使用此功能將會離開目前的頁面。如果您不儲存資料，您將無法保存您剛才所輸入的各項資料。您確定要離開嗎？");
		if (agree) return true ;
		else return false ;
	     },
viewpage: function() {
	     var links = document.getElementsByClassName("viewpage");
	     if(links.length) {
		for(i =0; i< links.length; i++ ) {
		   links[i].onclick = function() {
		      url = this.href;
		      return vj.block.show(url);
		   }
		}
	     }
	  }
}


/* Show Block */

vj.block = {

init: function() {
	 var overlay = document.createElement('div');
	 overlay.setAttribute('id','overlay');
	 overlay.style.display = 'none';
	 document.body.appendChild(overlay);

	 var vjblock = document.createElement('div');
	 vjblock.setAttribute('id','vjblock');
	 vjblock.style.display = 'none';
	 document.body.appendChild(vjblock);
      },


hide: function(url) {
	 if($('vjblock')) { new Effect.Fade('vjblock', { duration: 0.2}); }
	 if($('overlay')) { new Effect.Fade('overlay', { duration: 0.2}); }

	 if($('post_images')) {
	    var inputs = id=$('post_images').getElementsByTagName('input');
	    var id = inputs[inputs.length - 1].value;
	    var url = "upload-list.php?ajax=1&id=" + id;
	    new Ajax.Request(url, {onComplete: vj.post.images.update});
	 }

	 if($('volume_image')) {
	    var inputs = id=$('volform').getElementsByTagName('input');
	    var id = inputs[0].value;
	    var url = "volume-image.php?ajax=1&id=" + id;
	    new Ajax.Request(url, {onComplete: vj.volume.image.update});
	 }

	 if($('post_attach')) {
	    var inputs = id=$('post_attach').getElementsByTagName('input');
	    var id = inputs[inputs.length - 1].value;
	    var url = "attach-list.php?ajax=1&id=" + id;
	    new Ajax.Request(url, {onComplete: vj.post.attach.update});
	 }

	 if($('subscribe_table')) {
	    var inputs = id=$('subscribe_table').getElementsByTagName('input');
	    var opt = inputs[0].value;
	    var keyword = inputs[1].value;
	    var url = "subscriber-list.php?ajax=1&opt=" + opt + "&keyword=" + keyword;
	    new Ajax.Request(url, {onComplete: vj.subscribe.update});
	 }

	 if($('import_sel_span')) {
	    var url = "import-sel.php?ajax=1";
	    new Ajax.Request(url, {onComplete: vj.import_table.update});
	 }

	 if($('cat_sel_span')) {
	    var url = "post-cat.php?ajax=1";
	    new Ajax.Request(url, {onComplete: vj.post.cat.update});
	 }
      },

show: function(url) {

	 var arrayPageSize = getPageSize();
	 Element.setHeight('overlay', arrayPageSize[1]);
	 new Effect.Appear('overlay', { duration: 0.2, from: 0.0, to: 0.8 });
	 var arrayPageSize = getPageSize();
	 var arrayPageScroll = getPageScroll();
	 var lightboxTop = arrayPageScroll[1] + (arrayPageSize[3] / 15);
	 Element.setTop('vjblock', lightboxTop);
	 new Effect.Appear('vjblock', { duration: 0.2, from: 0.0, to: 1 });

	 $('vjblock').innerHTML = "<p><a id=\"closewin\" href=\"#\" accesskey=\"w\" title=\"快速鍵：PC 上為 Alt + W，麥金塔系統為 Ctrl + W\">關閉視窗</a></p><iframe id=\"vjiframe\"></iframe>";
	 $('vjiframe').style.width = "100%";
	 $('vjiframe').style.height = "430px";
	 $('vjiframe').style.border = "0";
	 if(url.lastIndexOf("?") >= 0) {
	    url = url + "&ajax=1";
	 } else {
	    url = url + "?ajax=1";
	 }
	 $('vjiframe').src = url;
	 $('overlay').onclick = function() { vj.block.hide(); }
	 $('closewin').onclick = function() { vj.block.hide(); return false; }
	 return false;
      }
}

/* Editing Post */

vj.post = new Object;

vj.post.show_area = function (area){
   if(vj.currarea == area) return false;
   $("post_basic").style.display = "none";
   $("post_images").style.display = "none";
   $("post_attach").style.display = "none";
   $(area).style.display = "block";
   vj.currarea = area;
   return false;
}

vj.post.images = {

init: function() {
	 var divs = $('image_block').getElementsByTagName('div');
	 for(i = 0; i < divs.length; i++ ) {
	    divs[i].onmouseover = function() {
	       this.style.backgroundColor = "#CCC";
	       this.style.cursor = "move";
	    }
	    divs[i].onmouseout = function() {
	       this.style.backgroundColor = "#FFF";
	    }
	 }

	 var links = $('image_block').getElementsByTagName('a');
	 for(i = 0; i < links.length; i++ ) {
	    var images = links[i].getElementsByTagName("img");
	    if(!images.length) {
	       links[i].onclick = function() {
		  url = this.href;
		  return vj.block.show(url);
	       }
	    }
	 }

	 Sortable.create('image_block', {dropOnEmpty:true, tag:'div', containment: ['image_block'], constraint: false, scroll: true, overlap: 'horizontal', onChange: function(el){vj.post.images.sort(el);} });
	 vj.post.images.sort();
	 if($('upload_img')) {
	    $('upload_img').onclick = function() {
	       return vj.block.show(this.href);
	    }
	 }
	 if($('image_form')) {
	    $('image_form').onsubmit = function() {
	       return vj.post.images.ordersubmit();
	    }
	 }
	 if($('image_form_submit')) {
	    $('image_form_submit').onclick = function() {
	       return vj.post.images.ordersubmit();
	    }
	 }
	 if($('image_form_reset')) {
	    $('image_form_reset').onclick = function() {
	       var inputs = id=$('post_images').getElementsByTagName('input');
	       var id = inputs[inputs.length - 1].value;
	       var url = "upload-list.php?ajax=1&id=" + id;
	       new Ajax.Request(url, {method: 'get', onComplete: vj.post.images.update});
	       return false;
	    }
	 }
      },

update: function(r){
	   $('post_images').innerHTML = r.responseText;
	   vj.post.images.init();
	   return false;
	},

sort: function (el){
	 var divs = $('image_block').getElementsByTagName('div');
	 if(divs.length == null) return;
	 for(i = 0; i < divs.length; i++ ) {
	    span = divs[i].getElementsByTagName('span');
	    input = divs[i].getElementsByTagName('input');
	    input[0].style.background = "red";
	    span[0].innerHTML = "[" +(i + 1) + "]";
	    input[0].value = (i + 1);
	 }
	 if($('image_form_submit')) {
	    $('image_form_submit').disabled = false;
	 }
      },

orderover: function(r) {
	      if(r.responseText == "1") {
		 alert('成功更新照片的排列順序！');
		 if($('image_form_submit')) {
		    $('image_form_submit').disabled = true;
		 }
	      } 
	      return false;
	   },

ordersubmit: function() {
		if(!($('image_form'))) {return false;} 
		var inputs = $('image_form').getElementsByTagName('input');
		if(!inputs.length) {
		   alert('沒有照片，無法更新圖片順序！');
		   return;
		}
		var pars = "submit=1&ajax=1&";
		for( i =0; i < inputs.length; i++ ) {
		   pars = pars + inputs[i].name + "=" + inputs[i].value + "&";
		}
		url = "upload-order.php";
		new Ajax.Request(url, {method: 'post',parameters: pars, onComplete: vj.post.images.orderover });
		return false;
	     }
}

vj.post.authordesc = {

init: function() {
	 new Ajax.Autocompleter("author", "autocomplete_choices", "post-author.php", {minChars: 1, paramName: "author", afterUpdateElement: vj.post.authordesc.update});
      },

update: function() {
	   if($('author_desc') && $('author')) {
	      author = $('author').value;
	      url = "post-author-desc.php";
	      pars = "author=" + author;
	      new Ajax.Request(url, {method: 'get',parameters: pars, onComplete: vj.post.authordesc.change });
	   }
	},

change: function(r) {
	   if($('author_desc') && $('author')) {
	      if($('author_desc').value != r.responseText) {
		 if(r.responseText == "") return;
		 if($('author_desc').value == "") {
		    $('author_desc').value = r.responseText;
		 } else {
		    message = "系統中已經有這位作者的簡介了，而且與您現在輸入的作者介紹不同。您想要使用之前的介紹文字嗎？";
		    if(window.confirm(message)) {
		       $('author_desc').value = r.responseText;
		    }
		 }
	      }
	   }
	}
}

vj.post.cat = {

init: function() {
	 if($('cat_add')) {
	    $('cat_add').onclick = function() {
	       url = this.href + "?";
	       return vj.block.show(url);
	    }
	 }
      },

update: function(r){
	   var index;
	   if($('cat')) { index = $('cat').selectedIndex; }
	   $('cat_sel_span').innerHTML = r.responseText;
	   if(index) { $('cat').selectedIndex = index; }
	   vj.post.cat.init();
	   return false;
	}
}

vj.post.attach = {

init: function() {
	 if($('attach_table')) {
	    var links = $('attach_table').getElementsByTagName('a');
	    for(i = 0; i < links.length; i++ ) {
	       var linkclass = String(links[i].getAttribute('class')).toLowerCase();
	       if (linkclass.match('attach_edit') || linkclass.match('attach_del')){ 
		  links[i].onclick = function() {
		     url = this.href;
		     return vj.block.show(url);
		  }
	       }
	    }
	 }
	 if($('upload-attach')) {
	    $('upload-attach').onclick = function() {
	       url = this.href;
	       return vj.block.show(url);
	    }
	 }
      },

update: function(r){
	   $('post_attach').innerHTML = r.responseText;
	   vj.post.attach.init();
	}
}

/* volume */

vj.volume = new Object;

vj.volume.show_area = function (area){
   if(vj.currarea == area) return false;
   $("vol_basic").style.display = "none";
   $("vol_extra").style.display = "none";
   $(area).style.display = "block";
   vj.currarea = area;
   return false;
}

vj.volume.copyright = {
update: function(r) {
	   $('copyright').value = r.responseText;
	},

get: function(url) {
	new Ajax.Request(url, {onComplete: vj.volume.copyright.update });
	html = $('copyright_wrap').getElementsByTagName('iframe')[0];
	html.src = url;
	return false;
     }
}

vj.volume.sortcat = function (el){
   var sels = document.getElementsByClassName('catorder');
   for(i = 0; i < sels.length; i++ ) {
      sels[i].selectedIndex = i;
   }
   if(!(el)) return;
   var imgs = el.getElementsByTagName('img');
   for(i = 0; i < imgs.length; i++ ) {
      imgs[i].onclick = function() {
	 return false;
      }
   }
}

vj.volume.sortpost = function (el){
   var sels = el.parentNode.getElementsByTagName('select');
   for(i = 0; i < sels.length; i++ ) {
      sels[i].selectedIndex = i;
   }
}

vj.volume.image = {

init: function() {
	 var links = $('volume_image').getElementsByTagName('a');
	 if(!links.length) return;
	 for(i = 0; i < links.length; i++ ) {
	    // Add a check to keep the lightbox effect 
	    var relAttribute = String(links[i].getAttribute('rel'));
	    if (relAttribute.toLowerCase().match('lightbox')){ 
	       links[i].onclick = function() {
		  myLightbox.start(this); 
		  return false;
	       }
	    } else {
	       links[i].onclick = function() {
		  url = this.href;
		  return vj.block.show(url);
	       }
	    }
	 }
      },

update: function(r){
	   $('volume_image').innerHTML = r.responseText;
	   vj.volume.image.init();
	}
}

/* Subscribe */

vj.subscribe = {

init: function() {
	 var links = $('subscribe_table').getElementsByTagName('a');
	 for(i = 0; i < links.length; i++ ) {
	    links[i].onclick = function() {
	       url = this.href;
	       return vj.block.show(url);
	    }
	 }
      },

update: function(r){
	   $('subscribe_table').innerHTML = r.responseText;
	   vj.subscribe.init();
	}
}

/* Info */

vj.info = new Object;

vj.info.show_area = function (area){
   if(vj.currarea == area) return false;
   $("info_basic").style.display = "none";
   $("info_detail").style.display = "none";
   $("info_upload").style.display = "none";
   $(area).style.display = "block";
   vj.currarea = area;
   return false;
}

/* File */

vj.file = new Object;

vj.file.show_area = function (area){
   if(vj.currarea == area) return false;
   $("file_theme").style.display = "none";
   $("file_edit").style.display = "none";
   $(area).style.display = "block";
   vj.currarea = area;
   return false;
}


/* RSS */

vj.import_form = {

init: function() {
	 $('import_sel').onchange = function() {
	    $('import_source_2').checked = true;
	 }
	 $('import_url').onfocus = function() {
	    $('import_source_1').checked = true;
	 }
	 $('import_add').onclick = function() {
	    var url = this.href + "?url=" + $('import_url').value;
	    return vj.block.show(url);
	 }
	 $('import_edit').onclick = function() {
	    if($('import_sel').value == "") {
	       alert("請先選擇您要修改的 RSS 匯入來源！");
	       return false;
	    } else {
	       var url = this.href + "?id=" + $('import_sel').value;
	       return vj.block.show(url);
	    }
	 }

	 $('import_delete').onclick = function() {
	    if($('import_sel').value == "") {
	       alert("請先選擇您要刪除的 RSS 匯入來源！");
	       return false;
	    } else {
	       var url = this.href + "?id=" + $('import_sel').value + "&ajax=2";
	       var agree = window.confirm("您確定要刪除嗎？");
	       if(agree) {
		  new Ajax.Request(url, {onComplete: vj.import_form.del });
	       }
	       return false;
	    }
	 }

      },

del: function(r) {
	alert(r.responseText);
	if(r.responseText == "1") {
	   alert("成功刪除這筆紀錄！");
	}
	if($('import_sel_span')) {
	   var url = "import-sel.php";
	   new Ajax.Request(url, {onComplete: vj.import_table.update});
	}
	return false;
     }
}

vj.import_table = {

init: function() {
	 var boxs = document.getElementsByClassName('rss_check');
	 var cats = document.getElementsByClassName('sel_cat');
	 var vols = document.getElementsByClassName('sel_vol');
	 $('sel-all').onclick = function() {
	    if(boxs) { for(i = 0; i < boxs.length; i++) { boxs[i].checked  = true; } }
	 }

	 $('sel-none').onclick = function() {
	    if(boxs) { for(i = 0; i < boxs.length; i++) { boxs[i].checked  = false; } }
	 }

	 $('cat-all').onchange = function() {
	    if(cats) { for(i = 0; i < cats.length; i++) { cats[i].selectedIndex  = this.selectedIndex; } }
	 }

	 $('volume').onchange = function() {
	    if(vols) { for(i = 0; i < vols.length; i++) { vols[i].selectedIndex  = this.selectedIndex; } }
	 }
      },

update: function(r){
	   var index;
	   if($('import_sel')) {
	      index = $('import_sel').selectedIndex;
	   }
	   $('import_sel_span').innerHTML = r.responseText;
	   if(index) {
	      $('import_sel').selectedIndex = index;
	   }
	   vj.import_form.init();
	   return false;
	}
}



/* Init */

vj.init = function() {
   BrowserDetect.init();
   if(!(BrowserDetect.browser in { Explorer:1, Firefox:1, Camino:1, Mozilla:1, Safari:1 } )){ 
      vj.notsupport = 1;
   }

   //編輯文章

   if($('edittool')) { 
      vj.post.show_area('post_basic');
      if(!vj.notsupport) {
	 if($('post_images')) { vj.post.images.init(); }
	 if($('post_attach')) { vj.post.attach.init(); }
      }
   }

   // 文章基本設定（新增或編輯文章）

   if($('post_basic')) { 
      if(!vj.notsupport) {
	 if($('cat_add')) { vj.post.cat.init(); }
	 if($('author'))  { vj.post.authordesc.init(); }
      }
   }

   //修改某期資料    
   if($('voltool')) { 
      vj.volume.show_area('vol_basic');
      if(!vj.notsupport) {
	 if($('import_copyright')) {
	    $('import_copyright').onclick = function() {
	       url = this.href;
	       return vj.volume.copyright.get(url); 
	    }
	 }
	 if($('volume_image')) { vj.volume.image.init(); }
	 if($('vol_extra')) {
	    Sortable.create('vol_extra', {dropOnEmpty:true, tag:'div', containment: ["vol_extra"], constraint: false, overlap: 'horizontal', onChange: function(el){vj.volume.sortcat(el);} }); vj.volume.sortcat(); }		 
      }
   }

   //修改設定

   if($('infotool')) { vj.info.show_area('info_basic'); }
   
   if($('filetool')) { vj.file.show_area('file_theme'); }

   // 以下是某些瀏覽器不支援的功能

   if(vj.notsupport) { return; }
   vj.block.init();
   if($('msg')) {new Effect.Fade('msg', { duration: 3.0 }); }   
   if($('quicklink')) {
      $('quicklink').style.display = 'none';
      if(!vj.cookie.getCookie('vjquickhide')) {
	 new Effect.Appear('quicklink');
      }
      $('quicklink-hide').onclick = function() {
	 new Effect.Fade('quicklink');
	 vj.cookie.quickhide();
	 return false;
      }
   }

   //某期文章列表

   if($('post_table')) {
      var tables = document.getElementsByClassName('post_table');
      for(i = 1; i <= tables.length; i++ ) {
	 var tbodyname = 'post_table-' + i;
	 var trs = $(tbodyname).getElementsByTagName('tr');
	 for(j = 0; j < trs.length; j++ ) {
	    trs[j].onmouseover = function() {
	       this.style.backgroundColor = "#CCC";
	       this.style.cursor = "move";
	    }
	    trs[j].onmouseout = function() {
	       this.style.backgroundColor = "#FFF";
	    }
	 }

	 Sortable.create(tbodyname, {dropOnEmpty:true, tag:'tr', containment: [tbodyname], constraint: false, handle:'handle', onChange: function(el){vj.volume.sortpost(el);} });
      }
   }

   //訂戶管理

   if($('subscribe_table')) { vj.subscribe.init(); }

   if($('viewpage')) {
      $('viewpage').onclick = function() {
	 return vj.block.show(this.href);
      }
   }
   vj.util.viewpage();

   if($('import_form')) {
      vj.import_form.init();
   }
   if($('import_table')) {
      vj.import_table.init();
   }
}

Event.observe(window, 'load', vj.init, false);
