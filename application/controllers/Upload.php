<?php
class Upload extends CI_Controller {
	function __construct() {
		parent :: __construct();
		$this->load->model('files_model','model');
		$this->load->helper(array ('form','url'));
	}
	function do_upload() {
		$this->load->database();
//		print_r($_FILES);
		$config['upload_path'] = './upload/notice/';
//		$config['allowed_types'] = 'gif|jpg|png';
		$config['allowed_types'] = '*';
	//	$config['max_size'] = '100';
		$config['max_width'] = '2024';
		$config['file_name'] =md5(time()).uniqid();
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('Filedata')) {
			$error = array (
				'error' => $this->upload->display_errors()
			);
			echo 112;
//			print_r($error);
		} else {
			$data = $this->upload->data();
			$insdata['filename']=$data['client_name'];
			$insdata['fileext']=$data['file_ext'];
			$insdata['filepath']=$data['file_name'];
			$insdata['filesize']=$data['file_size'];
			$insdata['messageid']='null';
			$insdata['id']= md5(uniqid(rand(), true)); //uuid有问题。先采用这种试试
		    $insdata['filename']&&$this->db->insert('i_files', $insdata);
			echo $insdata['id'];
		}
	}
}
?>