<?php /* 管理頁面頁尾 */ ?>
<div id="credit">
<p>Vanilla Journal - 香草期刊系統 
<?php
if($config['version']) {
   echo $config['version'];
}
?>
 / 2006 - 2007 © Weizhong Yang</p>
<?php if(is_logined()) { ?>
<p><a href="http://zonble.twbbs.org/" title="zonble's promptbook">zonble's promptbook</a>
<span class="sep">|</span>
<a href="http://code.google.com/p/vanilla-journal/issues/list">回報錯誤</a>
<span class="sep">|</span>
<a href="help.php" title="系統輔助說明">系統輔助說明</a>
<? } ?>
</p>
</div>
</div>
</body>
</html>
