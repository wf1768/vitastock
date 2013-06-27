<?php
//if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @desc: 数据库模型基类
 * @date：2013.2.25
 * @author li jin
 */
class MY_Model extends CI_Model{
	public  $tableName = ""; //表名称
	public  $pk        ="id"; //表 主键
	//model 初始化
	public  function __construct(){
		$this->load->library('form_validation');
	}
	/**
	 * 通过主键获得一条数据
	 * @access public
	 * @param string $id   主键id
	 * @param array $field 查询字段
	 * @return mixed
	 */
	public function getOne($id,$field=array()){
		if(!$id) return false;
		$this->db->from($this->tableName);
		$this->db->where($this->pk,$id);
		!empty($field)&&$this->db->select($field);
		$this->db->limit(1);
        //limit（1，2）这样写取不到数据。过后问
//        $this->db->limit(1,2);
		$query = $this->db->get();
        $aa = $this->db->last_query();
		return $query->result();
	}
	/**
	 * 获得符合条件的一条数据
	 * @access public
	 * @param array $where  查询条件
	 * @param array $field  查询字段
	 * @param array $order  排序字段
	 * @return mixed
	 */
	public function getOneByWhere($where = array (), $field = array (), $order = array ()) {
		$this->db->from($this->tableName);
		!empty($where) && $this->db->where($where);
		!empty($field) && $this->db->select($field);
		if(!empty($order)) foreach ($order as $key=>$val) $this->db->order_by($key,$val);
		$this->db->limit(1);
		$query = $this->db->get();
		$res=$query->result();
        if (isset($res[0])) {
            return $res[0];
        }
		return false;
	}
	/**
	 * 获得符合条件的所有数据
	 * @access public
	 * @param array $where  查询条件
	 * @param array $field  查询字段
	 * @param array $order  排序字段
	 * @return mixed
	 */
	public function getAllByWhere($where = array (), $field = array (), $order = array ()) {
		$this->db->from($this->tableName);
		!empty($where) && $this->db->where($where);
		!empty($field) && $this->db->select($field);
		if(!empty($order)) foreach ($order as $key=>$val) $this->db->order_by($key,$val);
		$query = $this->db->get();
		return $query->result();
	}

	/*
	 * 返回模型验证错误
	 */
	public function getError(){
	    return validation_errors();
	}
    /*
	 * 数据验证
	 */
	public  function _validate(){}
	/**
	 * 数据验证
	 * @access public
	 * @return mixed
	 */
	public function dataCreate(){
		  $_validate=$this->_validate();
		  if(empty($_validate)) return  ;
		  $this->form_validation->set_message('required', '%s');
		  $this->form_validation->set_message('is_unique', '%s');
	      foreach($_validate as $key=>$val){
	          $this->form_validation->set_rules($val[0], $val[2], $val[1]);
	      }
	      return $this->form_validation->run();
	}

}