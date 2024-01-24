<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class systemadmin_m extends MY_Model {

	protected $_table_name = 'systemadmin';
	protected $_primary_key = 'systemadminID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "systemadminID";

	function __construct() {
		parent::__construct();
	}

	function get_systemadmin_by_usertype($systemadminID = null) {
		$this->db->select('*');
		$this->db->from('systemadmin');
		$this->db->join('usertype', 'usertype.usertypeID = systemadmin.usertypeID', 'LEFT');
		if($systemadminID) {
			$this->db->where(array('systemadminID' => $systemadminID));
			$query = $this->db->get();
			return $query->row();
		} else {
			$query = $this->db->get();
			return $query->result();
		}
	}

	function get_username($table, $data=NULL) {
		$query = $this->db->get_where($table, $data);
		return $query->result();
	}

	function get_systemadmin($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_systemadmin($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_systemadmin($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_select_systemadmin($select = NULL, $array=[]) {
		if($select == NULL) {
			$select = 'systemadminID, name, photo';
		}

		$this->db->select($select);
		$this->db->from($this->_table_name);

		if(customCompute($array)) {
			$this->db->where($array);
		}

		$query = $this->db->get();
		return $query->result();
	}

	function insert_systemadmin($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_systemadmin($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	function delete_systemadmin($id){
		parent::delete($id);
	}

	function hash($string) {
		return parent::hash($string);
	}	
	function get_systemadmin_in($array){
		if($array['usertypeId'] == 1){
			$table = 'systemadmin';
			$primary_id = 'systemadminID';
		}else if($array['usertypeId'] == 2){
			$table = 'teacher';
			$primary_id = 'teacherID';
		}else if($array['usertypeId'] == 3){
			$table = 'student';
			$primary_id = 'studentID';
		}else if($array['usertypeId'] == 4){
			$table = 'parents';
			$primary_id = 'parentsID';
		} else{
			$table = 'user';
			$primary_id = 'userID';
		}
		 $sql = "select ".$primary_id.",phone,name from ".$table." where ".$primary_id." in (".$array['u_ids'].")";
		return $this->db->query($sql)->result();
		//  echo $this->db->last_query();die;	
	}
}

/* End of file systemadmin_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/systemadmin_m.php */