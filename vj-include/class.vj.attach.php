<?php

/* Vanilla Journal
 * class.vj.attach.php
 * Weizhong Yang
 */

class attach
{
	var $attach_id = "";
	var $filename = "";
	var $tagline = "";
	var $filelink = "";
	var $filetype = "";
	var $filesize = 0;
	var $display = 0;
	var $editlink = "";
	var $dellink = "";
	var $upload_date;

	function attach()
	{
	}

	function get_attachinfo($info = "")
	{
		global $config;
		if ($info) {
			$this->attach_id = $info['ID'];
			$this->filename = $info['FILENAME'];
			$this->tagline = $info['TAGLINE'];
			$this->filetype = $info['FILETYPE'];
			$this->filesize = $info['FILESIZE'];
			$this->display = $info['DISPLAY'];
			$this->upload_date = mysql2date('U', $info['UPLOAD_DATE']);
			$this->filelink = $config['attach_url'] . $info['FILEPATH'] . $info['FILENAME'];
			return true;
		}
		return false;
	}

	function get_icon()
	{
		$icons = array(
			"application/arj" => "archive.png",
			"application/excel" => "other.png",
			"application/gnutar" => "archive.png",
			"application/msword" => "doc.png",
			"application/mspowerpoint" => "ppt.png",
			"application/vnd.ms-powerpoint" => "ppt.png",
			"application/vnd.ms-works" => "doc.png",
			"application/octet-stream" => "text.png",
			"application/pdf" => "pdf.png",
			"application/powerpoint" => "ppt.png",
			"application/postscript" => "other.png",
			"application/plain" => "text.png",
			"application/rtf" => "doc.png",
			"application/vnd.ms-excel" => "other.png",
			"application/vocaltec-media-file",
			"application/wordperfect" => "doc.png",
			"application/x-bzip" => "archive.png",
			"application/x-bzip2" => "archive.png",
			"application/x-compressed" => "archive.png",
			"application/x-excel" => "other.png",
			"application/x-gzip" => "archive.png",
			"application/x-latex" => "other.png",
			"application/x-midi" => "audio.png",
			"application/x-msexcel" => "other.png",
			"application/x-rtf" => "doc.png",
			"application/x-sit" => "archive.png",
			"application/x-stuffit" => "archive.png",
			"application/x-shockwave-flash" => "flash.png",
			"application/x-troff-msvideo" => "flash.png",
			"application/x-zip-compressed" => "archive.png",
			"application/xml" => "xml.png",
			"application/zip" => "archive.png",
			"audio/aiff" => "audio.png",
			"audio/basic" => "audio.png",
			"audio/midi" => "audio.png",
			"audio/mod" => "audio.png",
			"audio/mpeg" => "audio.png",
			"audio/mpeg3" => "audio.png",
			"audio/wav" => "audio.png",
			"audio/x-aiff" => "audio.png",
			"audio/x-au" => "audio.png",
			"audio/x-mid" => "audio.png",
			"audio/x-midi" => "audio.png",
			"audio/x-mod" => "audio.png",
			"audio/x-mpeg-3" => "audio.png",
			"audio/x-wav" => "audio.png",
			"audio/xm" => "audio.png",
			"image/bmp" => "image.png",
			"image/gif" => "image.png",
			"image/jpeg" => "image.png",
			"image/pjpeg" => "image.png",
			"image/png" => "image.png",
			"image/tiff" => "image.png",
			"image/x-tiff" => "image.png",
			"image/x-windows-bmp" => "image.png",
			"multipart/x-zip" => "archive.png",
			"multipart/x-gzip" => "archive.png",
			"music/crescendo" => "audio.png",
			"text/richtext" => "doc.png",
			"text/plain" => "text.png",
			"text/xml" => "xml.png",
			"video/avi" => "video.png",
			"video/mpeg" => "video.png",
			"video/msvideo" => "wmv.png",
			"video/quicktime" => "quicktime.png",
			"video/x-mpeg" => "video.png",
			"video/x-ms-asf-plugin" => "wmv.png",
			"video/x-msvideo" => "wmv.png",
			"x-music/x-midi" => "audio.png"
		);

		if ($icons[$this->filetype]) {
			return $icons[$this->filetype];
		} else {
			return "other.png";
		}
	}

	function get_icon_link()
	{
		$url = vjinfo('url') . "vj-images/icon/mime/";
		$url .= $this->get_icon();
		return $url;
	}

	function icon_html()
	{
		if ($this->filetype) {
			$url = $this->get_icon_link();
			$alt = $this->get_icon();
			$html = '<img src="' . $url . '" alt="' . $alt . '" />&nbsp;';
			return $html;
		}
	}

	function link_html()
	{
		echo $this->icon_html();
		echo '<a href="' . $this->filelink . '">';
		if ($this->tagline) {
			echo $this->tagline;
		} else {
			echo $this->filename;
		}
		echo '</a>';
	}

	function image_html()
	{
		echo '<a href="' . $this->filelink . '" title="';
		if ($this->tagline) {
			echo $this->tagline;
		} else {
			echo $this->filename;
		}
		echo '"><img src="' . $this->filelink . '" alt="';
		if ($this->tagline) {
			echo $this->tagline;
		} else {
			echo $this->filename;
		}
		echo '/ ></a>';
	}

	function attach_list_item()
	{
		$str = "<li>" . $this->icon_html($this->filetype);
		$str .= '<a href="' . $this->filelink . '">';
		if ($this->tagline) {
			$str .= $this->tagline;
		} else {
			$str .= $this->filename;
		}
		$str .= '</a>（' . size_hum_read($this->filesize) . '）</li>';
		return $str;
	}

	function attach_table_row()
	{
		echo '<tr>';
		echo '<td><a href="' . $this->filelink . '" title="下載附件檔案">' . $this->filename . '</a></td>';
		if ($this->tagline) {
			echo '<td>' . $this->tagline . '</td>';
		} else {
			echo '<td>&nbsp;</td>';
		}
		if ($this->display) {
			echo '<td>是</td>';
		} else {
			echo '<td>否</td>';
		}
		echo '<td>' . $this->filetype . '</td>';
		echo '<td><a class="attach_edit" href="attach-edit.php?id=' . $this->attach_id . '">編輯</a></td>';
		echo '<td><a class="attach_del" href="attach-delete.php?id=' . $this->attach_id . '">刪除</a></td>';
		echo '</tr>';
	}

} // class ends here

function post_attachlist($before = "", $after = "")
{
	global $post;
	$attaches = $post->post_attaches;
	if (empty($attaches))
		return;
	foreach ($attaches as $attach) {
		$myattach = new attach();
		$myattach->get_attachinfo($attach);
		$str .= $myattach->attach_list_item();
	}
	if ($str) {
		echo $before;
		echo "<ul>" . $str . "</ul>";
		echo $after;
	}
}

?>