<?php defined("BASEPATH") or exit("No direct script access allowed");
function dd($data = null)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
}
function pr($data = null)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function last_query()
{
    $CI =& get_instance();
    $CI->load->database(); 
    echo "<pre>";
    echo $CI->db->last_query();
    echo "</pre>";
    die();
}


function getRoutePermission($route = '')
{
    $CI =& get_instance();
    $CI->load->database();
    $CI->load->library('session');
    if (empty($route)) {
        $route = uri_string();
    }
    $user_id = $CI->session->userdata('user_id');
    if (!$user_id) {
        return false;
    }
    $sql = "SELECT 1 FROM tbl_menus tm JOIN tbl_user_permissions up ON JSON_CONTAINS(tm.permission_ids, JSON_QUOTE(up.permission_value)) WHERE tm.url = ? AND up.user_id = ? AND up.permission_type = 'Permission' LIMIT 1";
    $query = $CI->db->query($sql, [$route, $user_id]);
    return $query->num_rows() > 0;
}

function getUserRolePermission($permission_value = null)
{
    $CI =& get_instance();
    $CI->load->database();
    $CI->load->library('session');
    $user_id = $CI->session->userdata('user_id');

    if (!$user_id || !$permission_value) {
        return false;
    }

    $CI->db->select('1');
    $CI->db->from('tbl_user_permissions');
    $CI->db->where('user_id', $user_id);
    $CI->db->where('permission_type', 'Permission');
    $CI->db->where('permission_value', $permission_value);
    $CI->db->limit(1);
    $query = $CI->db->get();

    return $query->num_rows() > 0;
}
function getPermissionNames()
{
    $CI =& get_instance();
    $CI->load->database();
    $CI->load->library('session');

    $user_id = $CI->session->userdata('user_id');
    if (!$user_id) {
        return '';
    }

    $CI->db->select('tbl_permissions.permission_name');
    $CI->db->from('tbl_user_permissions');
    $CI->db->join('tbl_permissions', 'tbl_permissions.permission_id = tbl_user_permissions.permission_value', 'left');
    $CI->db->where('tbl_user_permissions.user_id', $user_id);
    $CI->db->where('tbl_user_permissions.permission_type', 'Permission');

    $query = $CI->db->get();
    $results = $query->result_array();

    $permissions = array_column($results, 'permission_name');
    return implode(', ', $permissions);
}
