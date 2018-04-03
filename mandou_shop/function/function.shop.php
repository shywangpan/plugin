<?php 
function  shop_upload($file , $path){
	$exname = strtolower(substr($_FILES[$file]['name'],(strrpos($_FILES[$file]['name'],'.')+1)));
	$fileupload = $path.'/'.md5(time()).'.'.$exname;
	move_uploaded_file($_FILES[$file]['tmp_name'], $fileupload);
	return $fileupload;
}
?>