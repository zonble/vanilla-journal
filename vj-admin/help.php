<?php
   include("admin.php");
   admin_header("說明文件");
?>
   <div class="wrap">
   <h2>期刊網站暨發報系統簡易使用說明</h2>
   <div class="help">
   <h3>簡介</h3>
   <p><strong>Vanilla Journal</strong> 是一套陽春的中文期刊網站暨電子報發報系統，可以在單人單機作業的狀況下，輕鬆製作網路期刊，透過電子郵件發送期刊內容，並且提供文章搜尋、RSS 等功能。本系統的目標是設計一套簡易的流程，方便繁體中文環境的各種期刊使用，更希望能夠用於各種學術用途上。</p>
   </p>
   <h3>編輯流程</h3>
   <p>如果您剛架設好系統，您首先需要做的是<a href="info.php" title="設定期刊基本資料">設定期刊基本資料</a>，設定您的期刊名稱、網址、用來寄送電子郵件的信箱以及寄件人名稱等。您或許也會想要<a href="password.php" title="修改系統密碼">修改系統密碼</a>，目前這個系統只設計給單人使用，因此只有一組密碼，主要的考量是大多期刊主要還是由一人專門負責，而且還沒有想到在多人共同編輯的環境下的較佳編輯流程。</p>
   <p>之後，就是充實網站中的圖文內容。在這個系統中分成「期」以及「文章」兩種資料，您首先需要<a href="volume-add.php" title="新增一期期刊">新增一期期刊</a>，設定這一期期刊的期數、主題、出刊日期等資訊，也可以上傳當期期刊的主題照片，然後，您才可以在這一期的期刊下新增文章內容。請注意，在新增期刊的時候，這一期期刊並不會立刻出現在網路上，您必須在<a href="volumes.php" title="期數文章維護">期數文章維護</a>列表中，將當期期刊設定為「上線」。</p>
   <p>新增文章的方法，是在<a href="volumes.php" title="期數文章維護">期數文章維護</a>列表中，選擇您指定的某一期期刊，按下「新增文章」連結。在編輯文章的時候，您可以用像是 Microsoft Word 等文書軟體的介面，以即視即所得的方式編輯文章。您也可以在編輯文章的同時，上傳圖片檔案，上傳的圖片會自動出現在文章的右側。您也可以把某篇文章當成是一個線上相本使用，只要屬於這篇文章的照片超過一定的張數，就會自動以相本方式呈現，同時，系統也會幫你把圖片縮放到特定的大小，並且建立縮圖，大小可以在<a href="info.php" title="設定期刊基本資料">期刊基本資料中設定</a>。</p>
   <p>在將當期期刊上線之後，您就可以<a href="mail.php" title="執行發送作業">執行發送作業</a>，將當期期刊的索引頁寄送給您的訂戶。您可以先預覽您要發送的當期期刊的顯示效果，最好也先寄一次到您的個人測試信箱，看看在收信軟體中的效果，然後再正式寄送給您的訂戶。訂戶可以從網頁前端的訂閱介面中訂閱，在通過認證後，訂戶的電子郵件信箱會自動加入到資料庫中，您可以從<a href="subscribers.php" title="訂戶資料維護">訂戶資料維護</a>中看到訂戶名單，您也可以<a href="subscriber-add.php" title="新增一名訂戶">自行輸入新增</a>。</p>
<p>您也可以在 Vanilla Journal 專案網頁的 <a href="http://code.google.com/p/vanilla-journal/w/list">Wiki</a> 上，查看完整的使用說明。</p>
   </div>
   <div class="wrap">
   <h2>疑難雜症</h2>
   <p>如果您在使用這套系統的時候遇到任何問題，歡迎回報。我會盡最大的可能解決這套系統中的各項問題。您可以使用 Google Code 上面的<a href="http://code.google.com/p/vanilla-journal/issues/list">Vanilla Journal 問題回報頁面</a>，告訴我問題在那裡，只要按一下網頁上的 New Issue，就可以寄發錯誤通告了。</p>
   <p>所有跟 Vanilla Journal 相關的開發進度，都會發表在<a href="http://code.google.com/p/vanilla-journal/">Vanilla Journal 專案網頁</a>上，您也可以在我的<a href="http://zonble.twbbs.org/" title="zonble's promptbook">個人網誌</a>上，看到相關的資訊。</p>
   </div>
   <div class="wrap">
   <h2>關於本系統</h2>
   <p>系統開發：楊維中</p>
   <p>本系統使用了以下工具完成：</p>
   <ul>
      <li>資料庫連接物件 ezSQL：作者 Justin Vincent http://www.jvmultimedia.com/portal/</li>
      <li>檔案上傳物件 class.upload.php：作者 Colin Verot http://www.verot.net/php_class_upload.htm </li>
      <li>電子郵件傳送物件 PhpMailer：作者 Brent R. Matzelle http://phpmailer.sourceforge.net/</li>
      <li>prototype.js：http://prototype.conio.net/</li>
      <li>script.aculo.us 所提供的各項網頁效果：http://script.aculo.us/</li>
      <li>Light Box 效果：作者 Lokesh Dhakar http://www.huddletogether.com/projects/lightbox/</li>
      <li>部份程式碼取自 WordPress 專案： http://wordpress.org/</li>
   </ul>

   </div>
   </div>
<?php 
   admin_footer();
?>
