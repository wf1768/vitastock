<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * stock Controller Class
 *
 * 基于ci的商品管理系统
 *
 * @package		stock
 * @author		blues <blues0118@gmail.com>
 * @copyright	Copyright (c) 2013 - 2015, ussoft.net.
 * @license
 * @link
 * @version		0.1.0
 */

//=================================================

/**
 * stock role model Class
 *
 * 角色操作Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class role_model extends MY_Model {

    const TABLE_NAME = "sys_role";
    public $tableName= "sys_role";
    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();

        log_message('debug', "role Model Class Initialized");
    }

    /**
     * 获取单个角色信息
     *
     * @access public
     * @param string $id 角色id
     * @return array - 角色信息
     */
    public function get_role_by_id($id) {
        $data = array();

        $this->db->select('*')->from(self::TABLE_NAME)->where('id', $id)->limit(1);
        $query = $this->db->get();
        if($query->num_rows() == 1)
        {
            $data = $query->row_array();
        }
        //释放结果集
        $query->free_result();

        return $data;
    }

    /**
     * 获取所有角色信息
     *
     * @access public
     * @return array - 角色信息
     */
    public function get_roles() {
        return $this->db->get(self::TABLE_NAME);
    }

    //TODO 应该增加分页的查询

    /**
     * 删除一个角色
     *
     * @access public
     * @param string - $id 角色id
     * @return boolean - success/failure
     */
    public function remove_role($id) {

        //TODO 先移除角色与功能对应关系。再移除帐户和组的角色。

        $this->db->delete(self::TABLE_NAME, array('id' => $id));

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * 添加一个角色
     *
     * @access public
     * @param array - $data 角色信息
     * @return boolean - success/failure
     */
    public function add_role($data) {

        $this->db->insert(self::TABLE_NAME, $data);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * 修改角色信息
     *
     * @access public
     * @param string - $id 角色ID
     * @param array - $data 角色信息
     * @return boolean - success/failure
     */
    public function update_role($id, $data) {

        $this->db->where('id', $id);
        $this->db->update(self::TABLE_NAME, $data);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    /*
	 * 数据验证
	 */
	public  function _validate(){
		return array(
		   array('rolecode','required','角色名称必须'),
		);
	}

}
