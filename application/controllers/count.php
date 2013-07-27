<?php


/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-2-28
 * Time: 下午8:30
 * 统计模块
 */
class count extends Stock__Controller {
	/**
	 * 传递到页面的参数载体
	 * @var
	 */
	private $_data;
	function __construct() {
		parent :: __construct();
		$this->load->library('form_validation');
		$this->load->library('stock_lib');
		$this->load->model('storehouse_model', "storehouse");
		$this->load->model('stock_model');
		/** 在继承的自定义父类，获取系统配置。 */
		$this->_data = $this->get_stock_config('0', '39');
		$this->_data['page_title'] = '统计模块';
		$this->_data['fun_path'] = "count/xsCount";
	}
	/**
	 *  销售统计
	 * @access public
	 * @return mixed
	 */
	public function xsCount() {
		$allcount = 0;
		if (isset ($_POST['start'])) {
			$stimes = strtotime($_POST['start']);
			$ntimes = strtotime($_POST['end']);
			$this->_data['start'] = $_POST['start'];
			$this->_data['end'] = $_POST['end'];
			$p = 0;
			$end = date("Y-m", mktime(0, 0, 0, date("m", $ntimes), date("d", $ntimes), date("Y", $ntimes)));
			do {
				$times = mktime(0, 0, 0, date("m", $stimes) + $p, date("d", $stimes), date("Y", $stimes));
				$data = date("Y-m", $times);
				$sql = 'SELECT count(*) count FROM `e_sell` s ,e_sell_content c where s.id=c.sellid and s.status != 1 and s.createtime  like "' . $data . '%"';
				$res = $this->db->query($sql)->result();
				$ydata[] = intval($res[0]->count);
				$xdata[] = date("y/m", $times);
				;
				$allcount += intval($res[0]->count);
				$start = date("Y-m", $times);
				$p++;
			} while ($start != $end);
		} else {
			for ($i = 12; $i > -1; $i--) {
				$times = mktime(0, 0, 0, date("m") - $i, date("d"), date("Y"));
				$data = date("Y-m", $times);
				$sql = 'SELECT count(*) count FROM `e_sell` s ,e_sell_content c where s.id=c.sellid and s.status != 1 and s.createtime  like "' . $data . '%"';
				$res = $this->db->query($sql)->result();
				$ydata[] = intval($res[0]->count);
				$xdata[] = date("y/m", $times);
				;
				$allcount += intval($res[0]->count);
			}
		}
		$this->_data['ydata'] = json_encode($ydata);
		$this->_data['xdata'] = json_encode($xdata);
		$this->_data['ydatas'] = $ydata;
		$this->_data['xdatas'] = $xdata;
		$this->_data['allcount'] = $allcount;
		$this->load->view("count/xsCount", $this->_data);
	}
	/**
	 *  采购统计
	 * @access public
	 * @return mixed
	 */
	public function cgCount() {
		$allcount = 0;
		$this->_data['fun_path'] = "count/cgCount";
		if (isset ($_POST['start'])) {
			$stimes = strtotime($_POST['start']);
			$ntimes = strtotime($_POST['end']);
			$this->_data['start'] = $_POST['start'];
			$this->_data['end'] = $_POST['end'];
			$p = 0;
			$end = date("Y-m", mktime(0, 0, 0, date("m", $ntimes), date("d", $ntimes), date("Y", $ntimes)));
			do {
				$times = mktime(0, 0, 0, date("m", $stimes) + $p, date("d", $stimes), date("Y", $stimes));
				$data = date("Y-m", $times);
				$sql = 'SELECT  sum(c.number) count FROM `b_buy`  b,b_buy_content c  where c.buyid=b.id and  createtime like "' . $data . '%"';
				$res = $this->db->query($sql)->result();
				$ydata[] = intval($res[0]->count);
				$xdata[] = date("y/m", $times);
				;
				$allcount += intval($res[0]->count);
				$start = date("Y-m", $times);
				$p++;
			} while ($start != $end);
		} else {
			for ($i = 12; $i > -1; $i--) {
				$times = mktime(0, 0, 0, date("m") - $i, date("d"), date("Y"));
				$data = date("Y-m", $times);
				$sql = 'SELECT  sum(c.number) count FROM `b_buy`  b,b_buy_content c  where c.buyid=b.id and  createtime like "' . $data . '%"';
				$res = $this->db->query($sql)->result();
				$ydata[] = intval($res[0]->count);
				$xdata[] = date("y/m", $times);
				;
				$allcount += intval($res[0]->count);
			}
		}
		$this->_data['ydata'] = json_encode($ydata);
		$this->_data['xdata'] = json_encode($xdata);
		$this->_data['ydatas'] = $ydata;
		$this->_data['xdatas'] = $xdata;
		$this->_data['allcount'] = $allcount;
		$this->load->view("count/cgCount", $this->_data);
	}
	/**
	*  库房统计
	* @access public
	* @return mixed
	*/
	public function kfCount() {
		$outdata = array ();
		$this->_data['fun_path'] = "count/kfCount";
		//获得所有产品
		$res = $this->_list($this->stock_model, array ('title'), $this->_data);
		//获得所有库房
		$stroelist = $this->storehouse->getAllByWhere();
		foreach ($res['list'] as $sval) {
			foreach ($stroelist as $val) {
				$sql = "select count(*) as count,sum(salesprice) as  totmoney  from  s_stock  where statuskey=1 and  title='" . addslashes($sval->title) . "' and storehouseid=" . $val->id;
				$dres = $this->db->query($sql)->result();
				$outdata[$sval->title]['count'][] = $dres[0]->count;
				$outdata[$sval->title]['money'][] = $dres[0]->totmoney;
			}
		}
		//总的数据 价值统计
		if(isset($_GET['tcount']) && isset( $_GET['tmoney'])){
			$this->_data['tcount'] = $_GET['tcount'];
			$this->_data['tmoney'] = $_GET['tmoney'];
		}else{
			$sql="select count(*) as count,sum(salesprice) as totmoney from s_stock where statuskey=1";//在库 的
			$dres = $this->db->query($sql)->result();
			$this->_data['tcount'] =$_GET['tcount'] = $dres[0]->count;
			$this->_data['tmoney'] =$_GET['tmoney'] = $dres[0]->totmoney;
		}
		//加入参数 重新生成  分页信息
		$this->_list($this->stock_model, array ('title'), $this->_data);
		unset ($res['list']);
		$this->_data['source'] = $outdata;
		$this->_data['store'] = $stroelist;
		$data = array_merge($this->_data, $res);
		$this->load->view("count/kfCount", $data);
	}
	/**
	*  财务统计
	* @access public
	* @return mixed
	*/
	public function cwCount() {
		$allcount = 0;
        $all_discount = 0;
		if (isset ($_POST['start'])) {
			$stimes = strtotime($_POST['start']);
			$ntimes = strtotime($_POST['end']);
			$this->_data['start'] = $_POST['start'];
			$this->_data['end'] = $_POST['end'];
			$p = 0;
			$end = date("Y-m", mktime(0, 0, 0, date("m", $ntimes), date("d", $ntimes), date("Y", $ntimes)));
			do {
				$times = mktime(0, 0, 0, date("m", $stimes) + $p, date("d", $stimes), date("Y", $stimes));
				$data = date("Y-m", $times);
				$sql = 'SELECT sum(totalmoney) count FROM `e_sell` s where s.status != 1 and s.createtime  like "' . $data . '%"';
				$res = $this->db->query($sql)->result();
				$ydata[] = intval($res[0]->count);
				$xdata[] = date("y/m", $times);
                //计算折扣总价
                $sql_dis = 'SELECT sum(discount) count FROM `e_sell` s where s.status != 1 and s.createtime  like "' . $data . '%"';
                $res_dis = $this->db->query($sql_dis)->result();
                $discountdata[] = intval($res_dis[0]->count);

				;
				$allcount += intval($res[0]->count);
                $all_discount += intval($res_dis[0]->count);
				$start = date("Y-m", $times);
				$p++;
			} while ($start != $end);
		} else {
			for ($i = 12; $i > -1; $i--) {
				$times = mktime(0, 0, 0, date("m") - $i, date("d"), date("Y"));
				$data = date("Y-m", $times);
			    $sql = 'SELECT sum(totalmoney) count FROM `e_sell` s  where s.status !=1 and   s.createtime  like "' . $data . '%"';
				$res = $this->db->query($sql)->result();
				$ydata[] = intval($res[0]->count);
				$xdata[] = date("y/m", $times);
				;
				$allcount += intval($res[0]->count);
                //计算折扣总价
                $sql_dis = 'SELECT sum(discount) count FROM `e_sell` s  where s.status != 1 and  s.createtime  like "' . $data . '%"';
                $res_dis = $this->db->query($sql_dis)->result();
                $discountdata[] = intval($res_dis[0]->count);
                $all_discount += intval($res_dis[0]->count);
			}
		}
		$this->_data['ydata'] = json_encode($ydata);
		$this->_data['xdata'] = json_encode($xdata);
		$this->_data['ydatas'] = $ydata;
		$this->_data['xdatas'] = $xdata;
		$this->_data['allcount'] = $allcount;
        //折扣总价
        $this->_data['discountdata'] = json_encode($discountdata);
        $this->_data['discountdatas'] = $discountdata;
        $this->_data['all_discount'] = $all_discount;
		//==================================================================
		$outdata = array ();
		$this->_data['fun_path'] = "count/cwCount";
		//获得所有产品
		$res = $this->_list($this->stock_model, array ('title'), $this->_data);
		//获得所有库房
		$stroelist = $this->storehouse->getAllByWhere();
		foreach ($res['list'] as $sval) {
			foreach ($stroelist as $val) {
				$sql = "select count(*) as count ,SUM(salesprice) al from  s_stock where title='" . addslashes($sval->title) . "' and storehouseid=" . $val->id;
				$dres = $this->db->query($sql)->result();
				$outdata[$sval->title][] = $dres[0];
			}
		}
		unset ($res['list']);
		$this->_data['source'] = $outdata;
		$this->_data['store'] = $stroelist;
		$data = array_merge($this->_data, $res);
		$this->load->view("count/cwCount", $data);
	}
	public function _list($model, $field, $other_data) {
		//返回的数据
		$data = array ();
		//获得记录总数 以共分页
		$this->db->from($model->tableName);
		$docount = clone $this->db;
		!empty ($field) && $docount->select($field);
		$count = $docount->distinct()->where(array("statuskey"=>1))->get()->num_rows();
		unset ($docount);

		$this->page->doconstruct($count, $other_data['page_offset'], '', '', $this->uri->segment(2));
		//样式定制
		//            $theme='%upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%';
		$theme = '%first% %upPage% %prePage%  %linkPage%  %downPage% %nextPage% %end%';
		$this->page->setConfig("theme", $theme);
		//分页跳转的时候保证查询条件
		foreach ($_GET as $key => $val) {
			if (!is_array($val)) {
				$this->page->parameter .= "$key=" . urlencode($val) . "&";
			}
		}
		//开始查询数据
		!empty ($field) && $this->db->where(array("statuskey"=>1))->select($field);
		$this->db->limit($this->page->listRows, $this->page->firstRow);
		$query = $this->db->distinct()->get();
		$data['list'] = $query->result();
		$data['info'] = $this->page->pageInfo();
		$data['page'] = $this->page->show();
		if (!isset ($data['list']))
			$data['list'] = array ();
		if (!isset ($data['page']))
			$data['page'] = '';
		if (!isset ($data['info']))
			$data['info'] = '';
		//设定当前页面的url到session
		$this->session->set_userdata('_currentUrl_', current_url());
		return $data;
	}

}