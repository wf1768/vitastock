<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * stock Login Controller Class
 *
 * 基于ci的商品管理系统2323
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
 * stock pages Controller Class
 *
 * 通用页面。
 *
 * @package		stock
 * @subpackage	Controller
 * @category	Controller
 * @author		blues <blues0118@gmail.com>
 * @link
 */

class Pages extends CI_Controller {
    public function view($page = 'home'){
        if (!file_exists('application/views/pages/'.$page.'.php')) {
            //this page is not exists
            show_404();
        }
        $data['title'] = ucfirst($page);
        $this->load->view('common/header',$data);
        $this->load->view('pages/'.$page,$data);
        $this->load->view('common/footer',$data);
    }

}
