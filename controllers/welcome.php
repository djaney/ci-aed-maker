<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function __construct(){
		parent::__construct();
		
		$param = array(
			'label'=>'Article Module',
			'moduleName'=>'articlemodule',
			'controllerName'=>'Welcome',
			'tableName'=>'articles',
			'width'=>1000,
			'height'=>300,
			'sort'=>array('article_id','desc'),
			'fields'=>array(
				array(
					'autoIncrement'=>true,
					'label'=>'ID',
					'dbField'=>'article_id',
					'formName'=>'articleId',
					'input'=>'hidden',
					'width'=>50,
					'isPrimary'=>true,
				),
				array(
					'label'=>'Title',
					'dbField'=>'title',
					'formName'=>'title',
					'input'=>'text',
					'align'=>'left',
					'required'=>true,
					'maxChar'=>32,
					'width'=>200,
				),
				array(
					'label'=>'Content',
					'dbField'=>'content',
					'formName'=>'content',
					'input'=>'textarea',
					'align'=>'left',
					'required'=>true,
					'width'=>300,
				),
				array(
					'label'=>'Category',
					'dbField'=>'category_id',
					'formName'=>'categoryId',
					'input'=>'select',
					'align'=>'left',
					'required'=>true,
					'dropDown'=>array(
							'idField'=>'category_id',
							'labelField'=>'name',
							'dbTable'=>'categories',
							'sort'=>array('name','asc'),
					),
					'width'=>200,
				),
			),
		);
		
		$this->load->library('Aed_maker',$param);
		
	}
	 
	public function index()
	{
		$this->load->view('welcome_message');
	}
	public function grid(){
		die($this->aed_maker->grid());
	}
	public function add(){
		die($this->aed_maker->add());
	}
	public function edit(){
		die($this->aed_maker->edit());
	}
	public function delete(){
		die($this->aed_maker->delete());
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */