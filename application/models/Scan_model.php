<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Scan_model extends MY_Model
{
    public function get_doctype_list()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db
            ->select('dt.file_type,dt.alias')
            ->from('`user_permission` u')
            ->join('permission p', 'p.permission_id = u.permission_id')
            ->join('master_doctype dt', 'dt.alias = p.permission_name')
            ->where(['u.user_id' => $user_id])
            ->order_by('dt.file_type', 'asc');

        $result = $this->db->get()->result_array();
        return $result;
    }

    //==================not punched files=======================
    function get_my_lastest_scan()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->join('master_work_location', 'master_work_location.location_id = scan_file.Location', 'left');
        $this->db->where('Scan_By', $user_id);
        $this->db->where('File_Punched', 'N');
        $this->db->where('Scan_Resend', 'N');
        $this->db->order_by('Scan_Id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    //=======================Not Complete Scan File====================
    function get_my_lastest_temp_scan()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->join('master_work_location', 'master_work_location.location_id = scan_file.Location', 'left');
        $this->db->where('Temp_Scan_By', $user_id);
        $this->db->where('Scan_Complete', 'N');
        $this->db->where('temp_scan_reject', 'N');
        $this->db->order_by('Scan_Id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    //==================punch files=======================
    function get_my_scanned_files()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->where('Scan_By', $user_id);
        $this->db->where('File_Punched', 'Y');
        $this->db->where('Scan_Date', date('Y-m-d'));
        $this->db->order_by('Scan_Id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    function search_scanned_files($from_date, $to_date)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->where('Scan_By', $user_id);
        $this->db->where('File_Punched', 'Y');
        $this->db->where('Scan_Date >=', $from_date);
        $this->db->where('Scan_Date <=', $to_date);
        $this->db->order_by('Scan_Id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    //===============For Mobile API=========================
    function get_myscan_list($user_id)
    {
        $user_id = $user_id;
        $this->db->select('s.Scan_Id,s.Document_Name,s.File,s.File_Ext,s.File_Location,s.Final_Submit,s.File_Punched,s.Scan_Date');
        $this->db->from('scan_file s');
        $this->db->where('Scan_By', $user_id);
        $this->db->where('File_Punched', 'N');
        $this->db->order_by('Scan_Id', 'desc');

        $result = $this->db->get()->result_array();

        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $final[$key] = $value;
                $final[$key]['support_file'] = $this->get_support_file($value['Scan_Id']);
            }

            return $final;
        } else {
            return [];
        }
    }

    function get_myscan_punched_list($user_id)
    {
        $user_id = $user_id;
        $this->db->select('s.Scan_Id,s.Doc_Type,s.DocType_Id,s.Document_Name,s.File,s.File_Ext,s.File_Location,s.Final_Submit,s.File_Punched,Punch_Date');
        $this->db->from('scan_file s');
        $this->db->where('Scan_By', $user_id);
        $this->db->where('File_Punched', 'Y');
        $this->db->order_by('Scan_Id', 'desc');
        $result = $this->db->get()->result_array();
        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $final[$key] = $value;
                $final[$key]['support_file'] = $this->get_support_file($value['Scan_Id']);
            }

            return $final;
        } else {
            return [];
        }
    }

    function get_support_file($scan_id)
    {
        $this->db->select('*');
        $this->db->from('support_file');
        $this->db->where('Scan_Id', $scan_id);
        $result = $this->db->get()->result_array();
        return $result;
    }

    function get_scan_rejected_count()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->where('Scan_By', $user_id);
        $this->db->where('Scan_Resend', 'Y');
        $query = $this->db->get();
        return $query->num_rows();
    }

    function get_temp_scan_rejected_count()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->where('Temp_Scan_By', $user_id);
        $this->db->where('temp_scan_reject', 'Y');
        $query = $this->db->get();
        return $query->num_rows();
    }

    function scan_rejected_list()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->where('Scan_By', $user_id);
        $this->db->where('Scan_Resend', 'Y');
        $this->db->order_by('Scan_Id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    function temp_scan_rejected_list()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->where('Temp_Scan_By', $user_id);
        $this->db->where('	temp_scan_reject', 'Y');
        $this->db->order_by('Scan_Id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    function get_temp_scan_file_count()
    {
        $group_id = $this->session->userdata('group_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->where('Group_Id', $group_id);
        $this->db->where('Temp_Scan', 'Y');
        $this->db->where('Scan_Complete', 'N');
        $this->db->where('temp_scan_reject', 'N');
        $query = $this->db->get();
        return $query->num_rows();
    }

    function get_bill_rejected_count()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->where('Bill_Approved', 'R');
        $this->db->where('Is_Deleted', 'N');
        $this->db->where("((Temp_Scan = 'Y' AND Temp_Scan_By = $user_id) OR (Temp_Scan IS NULL AND Scan_By = $user_id))");
        $query = $this->db->get();
        return $query->num_rows();
    }

    function temp_scan_list_for_naming()
    {
        $group_id = $this->session->userdata('group_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->join('master_work_location', 'master_work_location.location_id = scan_file.Location', 'left');
        $this->db->where('Group_Id', $group_id);
        $this->db->where('Temp_Scan', 'Y');
        $this->db->where('Scan_Complete', 'N');
        $this->db->where('temp_scan_reject', 'N');
        $result = $this->db->get()->result_array();
        return $result;
    }

    function edit_bill_approver_list()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->where('Scan_By', $user_id);
        $this->db->or_where('Temp_Scan_By', $user_id);
        $this->db->where('Location IS NOT NULL');
        $this->db->where('Bill_Approved', 'N');
        $this->db->order_by('Scan_Id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    function bill_rejected_list()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->where('Bill_Approved', 'R');
        $this->db->where('Is_Deleted', 'N');
        $this->db->where("((Temp_Scan = 'Y' AND Temp_Scan_By = $user_id) OR (Temp_Scan IS NULL AND Scan_By = $user_id))");
        $result = $this->db->get()->result_array();

        return $result;
    }
    function bill_trashed_list()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->where('Bill_Approved', 'R');
        $this->db->where('Is_Deleted', 'Y');
        $this->db->where("((Temp_Scan = 'Y' AND Temp_Scan_By = $user_id) OR (Temp_Scan IS NULL AND Scan_By = $user_id))");
        $result = $this->db->get()->result_array();

        return $result;
    }
    public function all_trashed_bill_lists()
    {
        $this->db->select('sf.*, CONCAT(u.first_name, " ", u.last_name) AS deleted_by_name');

        $this->db->from('scan_file sf');

        $this->db->join('users u', 'sf.Deleted_By = u.user_id', 'inner');

        $this->db->where('sf.Is_Deleted', 'Y');

        $query = $this->db->get();

        return $query->result_array();
    }
    function get_document_type()
    {
        $this->db->select('type_id, file_type');
        $this->db->where('status', 'A');
        $query = $this->db->get('master_doctype');
        return $query->result_array();
    }
    public function get_firm_list()
    {
        $this->db->select('master_firm.*');
        $this->db->from('master_firm');
        $this->db->where('master_firm.is_deleted', 'N');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_department_list()
    {
        $this->db->select('master_department.*');
        $this->db->from('master_department');
        $this->db->where('master_department.is_deleted', 'N');
        $this->db->where('master_department.status', 'A');
        $result = $this->db->get()->result_array();
        return $result;
    }
}
