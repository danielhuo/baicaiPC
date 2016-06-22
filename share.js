var d = document,inputTimer,list,texts = {},
	_s = null,
	codehost = "",
	codehost = 'http://v3.jiathis.com/code';
	sc = false;
function parselist(){
	if(!list){
		list = d.getElementById('ckelist');
		var clist = list.cloneNode(true);
		var as = clist.getElementsByTagName('input');
		for (var i = 0, ci; ci = as[i++];) {
			texts[ci.value] = ci.parentNode;
		}
	}
}
function creElm (o, t, a) {
    var b = document.createElement(t || "div");
    for (var p in o) {
        p == "style" ? (b[p].cssText = o[p]) : (b[p] = o[p]);
    }
    return (a || document.body).insertBefore(b, (a || document.body).firstChild);
}
		
$CKE = {
	choose: function(o) {
		parselist();
		clearTimeout(inputTimer);
		inputTimer = setTimeout(function() {
			var s = o.value.replace(/^\s+|\s+$/, ''),
			frag = d.createDocumentFragment();
			for (var p in texts) {
				eval("var f = /" + (s || '.') + "/ig.test(p)");
				!!texts[p].cloneNode && (f && frag.appendChild(texts[p].cloneNode(true)));
			}
			list.innerHTML = '';
			list.appendChild(frag);
		},
		100)
	},
	open: function(A){
		var head = document.getElementsByTagName("head")[0] || document.documentElement;
		creElm({
            src: A,
            charset: "utf-8"
        }, "script", head);
	}
};

function jiathis_sendto(a) {
	try{var conf=jiathis_config||{};}catch(e){var conf={};};
    var ec = encodeURIComponent,
		U = document.getElementById('url').value,
		T = document.getElementById('title').value,
		S = document.getElementById('summary').value,
		I = parseInt(document.getElementById('uid').value),
		J = parseInt(document.getElementById('jtss').value),
		P = document.getElementById('pic').value,
		A = 'http://www.jiathis.com/send/',
		B = 'send.php',
		C = '?webid='+a+'&url='+ec(U || document.location)+'&title='+ec(T || document.title)+(I?'&uid='+I:'')+(J?'&jtss=1':'')+(S?'&summary='+S:'')+(P?'&pic='+P:'');
		
    if (a == 'copy' || a == 'fav' || a == 'print') {
		$CKE.open(A+B+C);
		if(a == 'copy'){
			jiathis_copyUrl();
		} else if(a == 'fav'){
			jiathis_addBookmark();
		} else {
			window.print();
		}
	} else {
		window.open(A+C, '');
	}
	
	return false;
	
}
function jiathis_addBookmark() {
	try{var conf=jiathis_config||{};}catch(e){var conf={};};
	var a = conf.title || document.title;
    var b = conf.url || parent.location.href;
    if (window.sidebar) {
        window.sidebar.addPanel(a, b, "");
    } else if (document.all) {
        window.external.AddFavorite(b, a);
    } else {
		alert('请按 Ctrl + D 为你的浏览器添加书签！');
	}
}
function jiathis_copyUrl() {
	try{var conf=jiathis_config||{};}catch(e){var conf={};};
    var a = conf.url || this.location.href;
    var b = conf.title || document.title;
    var c = b + " " + a;
    var userAgent = navigator.userAgent.toLowerCase();
    var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
    var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
    if(is_ie) {
		clipboardData.setData('Text', c);
		alert("复制成功,请粘贴到你的QQ/MSN上推荐给你的好友！");
	} else if(prompt('你使用的是非IE核心浏览器，请按下 Ctrl+C 复制代码到剪贴板', c)) {
		alert('复制成功,请粘贴到你的QQ/MSN上推荐给你的好友！');
	} else {
		alert('目前只支持IE，请复制地址栏URL,推荐给你的QQ/MSN好友！');
	}
}

function get(a) {
    if (typeof(a) == "object") {
        return a
    } else {
        return document.getElementById(a)
    }
}
function show(a) {
    a = get(a);
    if (a) {
        a.style.display = "block"
    }
}
function hide(a) {
    a = get(a);
    if (a) {
        a.style.display = "none"
    }
}
function filt(m, q, c) {
    var t = 0,
    e = "ati_",
    h = "no-match",
    j = "div";
    //var b = m.replace(/ /g, "") != "" ? m.replace(/\W+/g, "").replace(/ /g, "").toLowerCase() : "";
    var b = m.replace(/^\s+|\s+$/, '').toLowerCase();
    hide(h);
    show(c);
    for (var i in q) {
        var g = get(e + i.replace("@", "_")),
        a = i.toLowerCase(),
        l = (q[i]).toLowerCase(),
        r = b == "" ? ++t: 0;
        if (b != "") {
            if ((a.indexOf(m) > -1 || a.indexOf(b) > -1 || l.indexOf(b) > -1 || l.indexOf(m) > -1)) {
                r = 1;
                t++
            }
        }
        if (r > 0) {
            show(g)
        } else {
            hide(g)
        }
    }
    if (t === 0) {
        show(h);
        document.getElementById(h).height = document.documentElement.clientHeight-50;
        hide(c)
    }
}
function rhv (o) { o.className = o.className.replace('athov', ''); }
function shv (o) { if (o.className.indexOf("athov") == -1) o.className += ' athov'; };
function get_des(){
	var description = '',
		meta = document.getElementsByTagName("meta");
	for (k in meta) {
	  if(meta[k].name == 'description'){
		  description = meta[k].content;
	  }
	}
	return description;
}