var ema = new Object();

ema.init = function () {
  if ($("cites")) {
    new Effect.Fade("cites", { duration: 0.1 });
  }
  if ($("cite-link")) {
    $("cite-link").onclick = function () {
      new Effect.toggle("cites", "slide");
      return false;
    };
  }
};

Event.observe(window, "load", ema.init, false);
