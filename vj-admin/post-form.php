<?php
if ($post_status == "add") {
} else {
    ?>
<div id="edittool" class="tool">
    編輯工具：
    <a title="文章內容的基本設定" href="#basic" onclick="vj.post.show_area('post_basic'); return false;">基本設定</a> |
    <a title="上傳或是管理這篇文章的圖片" href="#images" onclick="vj.post.show_area('post_images'); return false;">圖片管理</a> |
    <a title="上傳或是管理這篇文章的附件" href="#attach" onclick="vj.post.show_area('post_attach'); return false;">附件管理</a> |
    <a title="檢視這篇文章的所在網頁" href="../index.php?id=<?php echo $id ?>" id="viewpage">這篇文章的所在網頁</a> |
    <a title="刪除這篇文章" href="post-delete.php?id=<?php echo $id ?>" onclick="return vj.util.exitconfirm();">刪除本文</a> |
    <a href="#" onclick="return document.postform.submit();">完成編輯</a>
</div>

<?php } ?>
<a name="basic"></a>
<form method="post" action="<?php echo $target; ?>" name="postform">
    <input type="hidden" name="action" value="<?php echo $action ?>" />
    <?php if ($id) { ?>
    <input type="hidden" name="id" value="<?php echo $id ?>" />
    <?php } ?>
    <div id="post_basic">
        <p>本文屬於：
            <?php sel_vol('volume', $volume); ?>
            ，單元為
            <span id="cat_sel_span">
                <?php sel_cat('cat', $cat); ?>
            </span>
            （單元不夠用嗎？您可以<a href="cat-add.php" id="cat_add">新增單元</a>）
        </p>

        <div id="post_block_1">

            <table>
                <tr>
                    <td class="post_options"><label for="author">作者名稱</label>：</td>
                    <td><input type="text" name="author" id="author" value="<?php echo $author; ?>" size="20" /><br />
                        <div id="autocomplete_choices" class="autocomplete"></div>
                        <small>如果有多位作者，請在作者名稱之間，用半形逗號隔開。</small>
                    </td>
                </tr>
                <tr>
                    <td class="post_options"><label for="author_desc">作者介紹</label>：</td>
                    <td><textarea name="author_desc" id="author_desc" rows="5"
                            cols="20"><?php echo $author_desc; ?></textarea><br />
                        <small>您可以在此為作者做簡單的介紹。</small>
                    </td>
                </tr>
                <tr>
                    <td class="post_options"><label for="keyword">關鍵字</label>：</td>
                    <td><textarea name="keyword" id="keyword"><?php echo $keyword; ?></textarea><br />
                        <small>如果有多位關鍵字，請在關鍵字之間，用半形逗號隔開。</small>
                    </td>
                </tr>

                <tr>
                    <td class="post_options"><label for="post_order">次序</label>：</td>
                    <td>
                        <select name="post_order" id="post_order">
                            <?php
                            for ($i = 0; $i < 20; $i++) {
                                echo "<option value=\"" . $i . "\"";
                                if ($i == $post_order) {
                                    echo ' selected="selected" ';
                                }
                                if ($i == 0) {
                                    echo ">不設定次序";
                                } else {
                                    echo ">該單元的第 " . $i . " 篇";
                                }
                                echo "</option>";
                            }
                            ?>
                        </select><br />
                        <small>您也可以在<a
                                href="volume-edit.php?volume=<?php echo $volume ?>">管理本期文章</a>頁面上，以拖拉方式快速調整整個單元中的文章次序。</small>
                    </td>
                </tr>
                <tr>
                    <td class="post_options"><label for="importance">文章重要性</label>：</td>
                    <td><select name="importance" id="importance">
                            <?php
                            for ($i = 0; $i < 5; $i++) {
                                echo '<option value="' . $i . '"';
                                if ($i == $importance) {
                                    echo ' selected="selected"';
                                }
                                echo '>';
                                for ($j = 0; $j < $i + 1; $j++) {
                                    echo "★";
                                }
                                echo '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="post_options">是否公開：</td>
                    <td>
                        <input type="radio" name="display" id="dis1" value="1" <?php if (!(empty($display))) {
                            echo 'checked="checked"';
                        } ?> /><label for="dis1">是、在網路上公開這篇文章</label><br />
                        <input type="radio" name="display" id="dis2" value="0" <?php if (empty($display)) {
                            echo 'checked="checked"';
                        } ?> /><label for="dis2">否、不要在網路上公開這篇文章</label>
                        <p><small>如果在發行後，發現這篇文章有版權問題之類的問題不適合發行，您又不想刪除文章，您可以選擇「否」，讓這篇文章先暫時隱藏起來。</small></p>
                    </td>
                </tr>
            </table>

            <div class="submit">
                <input type="submit" value="<?php echo $actionlabel; ?>" id="post-submit" />
            </div>
        </div>

        <div id="post_block_2">
            <table>
                <tr>
                    <td class="post_options"><label for="topic">標題</label>：</td>
                    <td><input type="text" name="topic" id="topic" value="<?php echo $topic; ?>" size="50" /></td>
                </tr>
                <tr>
                    <td class="post_options"><label for="topic_ext">副標題</label>：</td>
                    <td><input type="text" name="topic_ext" id="topic_ext" value="<?php echo $topic_ext; ?>"
                            size="50" /></td>
                </tr>

                <tr>
                    <td class="post_options"><label for="abstract">摘要</label>：</td>
                    <td><textarea class="mceEditor" style="width: 95%" name="abstract" id="abstract" rows="5"
                            cols="80"><?php echo $abstract; ?></textarea></td>
                </tr>
                <tr>
                    <td class="post_options"><label for="body">內文</label>：</td>
                    <td><textarea class="mceEditor" style="width: 95%" name="body" id="body" rows="15"
                            cols="80"><?php echo $body; ?></textarea></td>
                </tr>
            </table>
        </div>
        <br clear="all" />
    </div>
</form>