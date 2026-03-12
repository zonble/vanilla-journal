vj.scroll = new Object;
var vj.scroll.itemBeingDragged;
var vj.scroll.scrollPoll;

vj.scroll.getWindowScroll = function() {
  var T, L, W, H;
  var w = window;
  with (w.document) {
    if (w.document.documentElement && documentElement.scrollTop) {
      T = documentElement.scrollTop;
      L = documentElement.scrollLeft;
    } else if (w.document.body) {
      T = body.scrollTop;
      L = body.scrollLeft;
    }
    if (w.innerWidth) {
      W = w.innerWidth;
      H = w.innerHeight;
    } else if (w.document.documentElement && documentElement.clientWidth) {
      W = documentElement.clientWidth;
      H = documentElement.clientHeight;
    } else {
      W = body.offsetWidth;
      H = body.offsetHeight
    }
  }
  return { top: T, left: L, width: W, height: H };
}

vj.scroll.findTopY = function(obj) {
  var curtop = 0;
  if (obj.offsetParent) {
    while (obj.offsetParent) {
      curtop += obj.offsetTop;
      obj = obj.offsetParent;
    }
  }
  else if (obj.y)
    curtop += obj.y;
  return curtop;
}

vj.scroll.findBottomY = function(obj) {
  return vj.scroll.findTopY(obj) + obj.offsetHeight;
}

vj.scroll.scrollSome = function() {
  var scroller = vj.scroll.getWindowScroll();
  var yTop = vj.scroll.findTopY(itemBeingDragged);
  var yBottom = vj.scroll.findBottomY(itemBeingDragged);

  if (yBottom > scroller.top + scroller.height - 20)
    window.scrollTo(0,scroller.top + 30);
    else if (yTop < scroller.top + 20)
    window.scrollTo(0,scroller.top - 30);
}

vj.scroll.scrollStart = function(e) {
  vj.scroll.itemBeingDragged = e;
  vj.scroll.scrollPoll = setInterval(vj.scroll.scrollSome, 100);
}

vj.scroll.scrollEnd =function(e) {
  clearInterval(vj.scroll.scrollPoll);
}

