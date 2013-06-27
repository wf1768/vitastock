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
 * stock account model Class
 *
 * 帐户操作Model
 *
 * @package		stock
 * @subpackage	model
 * @category	model
 * @author		blues <blues0118@gmail.com>
 * @link
 */
class account_model extends MY_Model
{

//    const TABLE_NAME = "sys_account";
     public $tableName= "sys_account";

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();

        log_message('debug', "Account Model Class Initialized");
    }
   /*
	 * 数据验证
	 */
	public  function _validate(){
		return array(
		   array('accountcode','is_unique[sys_account.accountcode]','登陆名称已经存在了，换一个吧'),
		   array('email','is_unique[sys_account.email]','邮箱已经存在了，换一个吧'),
		);
	}
//    /**
//     * 获取单个帐户信息
//     *
//     * @access public
//     * @param varchar $id 帐户id
//     * @return array - 帐户信息
//     */
//    public function get_account_by_id($id) {
//        $data = array();
//
//        $this->db->select('*')->from(self::TABLE_NAME)->where('id', $id)->limit(1);
//        $query = $this->db->get();
//        if($query->num_rows() == 1) {
//            $data = $query->row_array();
//        }
//        //释放结果集
//        $query->free_result();
//
//        return $data;
//    }
//
//    /**
//     * 获取所有帐户信息
//     *
//     * @access public
//     * @return array - 帐户信息
//     */
//    public function get_accounts() {
//        return $this->db->get(self::TABLE_NAME);
//    }
//
//    /**
//     * 删除一个帐户
//     *
//     * @access public
//     * @param varchar - $id 帐户id
//     * @return boolean - success/failure
//     */
//    public function remove_account($id) {
//        $this->db->delete(self::TABLE_NAME, array('id' => $id));
//
//        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
//    }
//
//    /**
//     * 添加一个帐户
//     *
//     * @access public
//     * @param  array $data 帐户信息
//     * @return boolean - success/failure
//     */
//    public function add_account($data) {
//        //将密码进行md5加密
//        $data['password'] = Common::do_hash($data['password']);
//
//        $this->db->insert(self::TABLE_NAME, $data);
//
//        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
//    }
//
//    /**
//     * 修改帐户信息
//     *
//     * @access public
//     * @param varchar - $id 帐户ID
//     * @param varchar - $data 帐户信息
//     * @param boolean - $hashed 密码是否hash
//     * @return boolean - success/failure
//     */
//    public function update_account($id, $data, $hashed = TRUE) {
//        if(!$hashed) {
//            $data['password'] = Common::do_hash($data['password']);
//        }
//
//        $this->db->where('id', $id);
//        $this->db->update(self::TABLE_NAME, $data);
//
//        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
//    }

    /**
     * 检查是否存在相同{帐户名/邮箱 等}
     *
     * @access public
     * @param varchar - $key {name,mail}
     * @param varchar - $value {帐户名/邮箱}的值
     * @param varchar - $exclude_id 需要排除的id
     * @return boolean - success/failure
     */
    public function check_exist($key = 'accountcode',$value = '', $exclude_id = '') {
        //如果值不为空
        if(!empty($value)) {
            $this->db->select('id')->from(self::TABLE_NAME)->where($key, $value);

            //如果要排除的id不为空
            if(!empty($exclude_id)) {
                $this->db->where('id <>', $exclude_id);
            }

            $query = $this->db->get();
            $num = $query->num_rows();

            $query->free_result();

            return ($num > 0) ? TRUE : FALSE;
        }

        return FALSE;
    }

    /**
     * 检查用户是否通过验证
     * @param $accountcode  帐户名
     * @param $password     密码
     * @return bool         boolean / array
     */
    public function validate_account($accountcode,$password) {

        $data = $this->getOneByWhere($where = array ("accountcode"=>$accountcode), $field = array (), $order = array ());


        if(!empty($data)){
            $data = (Common::hash_Validate($password, $data->password)) ? $data : FALSE;
        }

//        $query->free_result();

        return $data;
    }
}
