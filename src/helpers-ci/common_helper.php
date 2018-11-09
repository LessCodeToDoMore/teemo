<?php
defined('BASEPATH') OR exit('No direct script access allowed');


// 菜单
function echo_url_by_id( $strId ) {
	echo select_value('tb_menu', 'strUrl', 'id', $strId);
}