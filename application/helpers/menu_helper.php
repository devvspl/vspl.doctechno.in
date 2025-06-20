<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_menu')) {
    function get_menu($user_id, $role_id)
    {
        $CI =& get_instance();
        $CI->load->database();
        if ($role_id == 0) {
            $CI->db->select('*');
            $CI->db->from('tbl_menus');
            $CI->db->where('is_active', 1);
            $CI->db->order_by('parent_id ASC, `order` ASC');
            $query = $CI->db->get();
            $menus = $query->result_array();
        } else {
            $CI->db->select('permission_value');
            $CI->db->from('tbl_user_permissions');
            $CI->db->where('user_id', $user_id);
            $CI->db->where('permission_type', 'Permission');
            $permission_query = $CI->db->get();
            $permissions = $permission_query->result_array();
            $user_permissions = array_column($permissions, 'permission_value');

            if (empty($user_permissions)) {
                return '';
            }

            $CI->db->select('*');
            $CI->db->from('tbl_menus');
            $CI->db->where('is_active', 1);
            $CI->db->order_by('parent_id ASC, `order` ASC');
            $menu_query = $CI->db->get();
            $menus_all = $menu_query->result_array();

            $menus = [];
            foreach ($menus_all as $menu) {
                // Handle null or empty permission_ids
                $menu_permissions = !empty($menu['permission_ids']) && is_string($menu['permission_ids']) 
                    ? json_decode($menu['permission_ids'], true) 
                    : [];
                if (!is_array($menu_permissions)) {
                    $menu_permissions = []; // Fallback to empty array if decoding fails
                }

                if (array_intersect($user_permissions, $menu_permissions)) {
                    $menus[] = $menu;
                }
            }
        }
        $menuArr = [];
        foreach ($menus as $menu) {
            $menuArr[$menu['parent_id']][] = $menu;
        }
        return build_menu($menuArr);
    }
}

function build_menu($menu, $parent = NULL)
{
    $html = '';

    if (isset($menu[$parent])) {
        foreach ($menu[$parent] as $item) {
            $hasChildren = isset($menu[$item['id']]);
            $icon = !empty($item['icon']) ? '<i class="' . $item['icon'] . '"></i>' : '';
            $url = ($item['url'] && $item['url'] != '#') ? base_url($item['url']) : base_url('');

            if ($parent === NULL) {
                // Top-level menu item
                if ($hasChildren) {
                    $html .= '<li class="dropdown">';
                    $html .= '<a href="' . $url . '" class="dropdown-toggle" data-toggle="dropdown">' . $icon . ' <span>' . $item['name'] . '</span><span class="caret"></span></a>';
                } else {
                    $html .= '<li>';
                    $html .= '<a href="' . $url . '">' . $icon . ' <span>' . $item['name'] . '</span></a>';
                }
            } else {
                // Nested menu item
                if ($hasChildren) {
                    $html .= '<li class="dropdown dropdown-submenu">';
                    $html .= '<a href="' . $url . '" class="dropdown-toggle" data-toggle="dropdown">' . $item['name'] . '<span class="caret"></span></a>';
                } else {
                    $html .= '<li>';
                    $html .= '<a href="' . $url . '">' . $item['name'] . '</a>';
                }
            }

            if ($hasChildren) {
                $html .= '<ul class="dropdown-menu">';
                $html .= build_menu($menu, $item['id']);
                $html .= '</ul>';
            }

            $html .= '</li>';
        }
    }

    return $html;
}
?>