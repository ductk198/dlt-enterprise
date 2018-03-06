<?php

namespace Modules\Core\Helpers;

use DB;
use Log;
use Request;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

/**
 * Helper xử lý liên quan việc zend menu ra html.
 *
 * @author Duclt
 */
class MenuSystemHelper
{
	
	/**
     * Xuất menu ra html
     *
     * @param string $module : module của menu
     * @param string $menu : menu cha
     * @param boolean $active : là true là active trên form
     * @param string $parrenturl : url parent menu
     *
     * @return string html
     */
	public static function print_menu($module,$menu, $active = false,$parrenturl = '') {
		$html = '';
		$layout = 'system';
		if(MenuSystemHelper::check_permission_menu($menu)){
			$childrens = $menu['child'];
			// Xuat HTML $html .= '';
			$html .= '<li id="main_'.$module.'">';
			// check menu co menu cap 2 khong
			if(!$childrens){
				$html .= '<a role="button" href="'.url($layout.'/'.$module).'">';
				$html .= '<i class="'.$menu['icon'].'"></i>';
				$html .= '<span>'.$menu['name'].'</span>';
				$html .= '</a>';
			}else{
				$html .= '<a href="#">';
				$html .= '<i class="'.$menu['icon'].'"></i>';
				$html .= '<span>'.$menu['name'].'</span> <b class="caret"></b>';
				$html .= '</a>';
				// Menu cap 2
				$html .= '<ul class="treeview-menu">';
				foreach($childrens as $child_url => $children){
					$html .= '<li id="child_'.$child_url.'">';
					$html .= '<a role="button" href="'.url($layout.'/'.$module.'/'.$child_url).'">';
					$html .= '<i class="'.$children['icon'].'"></i>';
					$html .= '<span>'.$children['name'].'</span>';
					$html .= '</a>';
					$html .= '</li>';
				}
				$html .= '</ul>';
			}
			$html .= '</li>';
		}
		return $html;
	}

	public static function check_permission_menu($menu) {
		if($menu['check_permision']){
			if($menu['check_permision'] == $_SESSION["role"]){
				return true;
			}
			return false;
		}else{
			return true;
		}
	}
}
