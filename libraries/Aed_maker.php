<?php

class Aed_maker{
	
	/* 
		parameter format
		
		$param = array(
			'label'=>'',
			'moduleName'=>'',
			'controllerName'=>'',
			'tableName'=>'',
			'width'=>100,
			'height'=>100,
			'sort'=>arrray('field','desc'),
			'fields'=>array(
				array(
					'autoIncrement'=>false, // if column is an autoincrement
					'label'=>'', // form label
					'dbField'=>'', // corresponding column in database table
					'formName'=>'', //form field name
					'input'=>'text|password|textarea|select|hidden', // type of input field
					'maxChar'=>32, // maximum characters if column is varchar
					'align'=>'center', // grid alignment
					'required'=>true, // if field is required
					'isPrimary'=>,'' // if the field is a primary key
					'width'=>100, // width if the field
					'dropDown'=>array( // if fields is a foreign key for another table
							'idField'=>'', // the id field for the dropdown, corresponds to a database column
							'labelField'=>'', // label field for the dropdown, corresponds to a database column
							'dbTable'=>'', // table used as a dropdown
							'sort'=>arrray('field','desc'),
					),
					'dropDownDefaultLabel'==>'', // the label of the dropdown option has a default value not connected to a database
					'dropDownDefaultId'==>'', // the id of the dropdown option if has a default value not connected to a database
				),
			),
		);
		
	*/
	
	protected $label;
	protected $moduleName;
	protected $controllerName;
	protected $tableName;
	protected $width;
	protected $height;
	protected $sort;
	protected $fields = array();
	protected $CI;
	
	public function __construct($params){
		$this->label = $params['label'];
		$this->moduleName = $params['moduleName'];
		$this->controllerName = strtolower($params['controllerName']);
		$this->tableName = $params['tableName'];
		$this->width = isset($params['width'])?$params['width']:0;
		$this->height = isset($params['height'])?$params['height']:200;
		$this->sort = isset($params['sort'])?$params['sort']:false;
		
		foreach($params['fields'] as $field){
			$dds = array();
			if(isset($field['dropDown'])){
				$dds = $field['dropDown'];
			}else{
				$dds = array();
			}
			
			$this->fields[] = array(
					'autoIncrement'=>isset($field['autoIncrement'])?$field['autoIncrement']:false,
					'label'=>$field['label'],
					'dbField'=>$field['dbField'],
					'formName'=>$field['formName'],
					'width'=>$field['width'],
					'maxChar'=>isset($field['maxChar'])?$field['maxChar']:0,
					'align'=>isset($field['align'])?$field['align']:'center',
					'input'=>isset($field['input'])?$field['input']:'text',
					'dropDown'=>$dds,
					'dropDownDefaultLabel'=>isset($field['dropDownDefaultLabel'])?$field['dropDownDefaultLabel']:false,
					'dropDownDefaultId'=>isset($field['dropDownDefaultId'])?$field['dropDownDefaultId']:false,
					'required'=>isset($field['required'])?$field['required']:false,
					'isPrimary'=>isset($field['isPrimary'])?$field['isPrimary']:false,
			);
			

		}
		
		$this->CI = get_instance();
		$this->CI->load->database();
		$this->CI->load->helper('url');
	}
	
	/* 
		manager - prints a jqGrid with add/edit/delete function.
		Requires: jqueryui,jqgrid,jquery
	*/
	
	public function manager(){
		$colModel = array();
		$colNames = array();
		foreach($this->fields as $v){
			$colModel[] = array(
				'name'=>(!$v['dropDown'])?($this->tableName.'_'.$v['dbField']):($v['dropDown']['dbTable'].'_'.$v['dropDown']['labelField']),
				'index'=>(!$v['dropDown'])?($this->tableName.'_'.$v['dbField']):($v['dropDown']['dbTable'].'_'.$v['dropDown']['labelField']),
				'width'=>$v['width'],
				'sortable'=>false,
				'align'=>$v['align'],
			);
			$colNames[] = $v['label'];
			
			if($v['dropDown']){
				$colModel[] = array(
					'name'=>$v['dropDown']['dbTable'].'_'.$v['dropDown']['idField'],
					'index'=>$v['dropDown']['dbTable'].'_'.$v['dropDown']['idField'],
					'width'=>50,
					'sortable'=>false,
					'hidden'=>true,
				);
				$colNames[] = '';
			}
		}
	?>
	<script type="text/javascript">
	jQuery(function($){
		$('#<?php echo $this->moduleName ?>-grid').jqGrid({
			url:'<?php echo site_url($this->controllerName.'/grid') ?>',
			mtype: 'post',
			datatype: 'json',
			colNames:<?php echo json_encode($colNames); ?>,
			colModel:
			<?php echo json_encode($colModel); ?>,
			rowNum:10,
			rowList:[10,20,30],
			pager: '#<?php echo $this->moduleName ?>-pager',
			viewrecords: true,
			caption:"<?php echo $this->label ?>",
			<?php if(isset($this->width) && $this->width!=0): ?>
			width:<?php echo $this->width ?>,
			<?php endif; ?>
			height:<?php echo $this->height ?>
		});
		$('#<?php echo $this->moduleName ?>-grid').jqGrid('navGrid','#<?php echo $this->moduleName ?>-pager',{edit:false,add:false,del:false,search:false})
		$('#<?php echo $this->moduleName ?>-grid').jqGrid('navButtonAdd',"#<?php echo $this->moduleName ?>-pager",{
			caption:"Add", buttonicon:"ui-icon-plusthick", onClickButton:function(){
				$('#<?php echo $this->moduleName ?>-add-error').hide();
				$('#<?php echo $this->moduleName ?>-add-dialog').dialog('open');
			}, position: "last", title:"Add", cursor: "pointer"
		});
		$('#<?php echo $this->moduleName ?>-grid').jqGrid('navButtonAdd',"#<?php echo $this->moduleName ?>-pager",{
			caption:"Edit", buttonicon:"ui-icon-pencil", onClickButton:function(){
				if($("#<?php echo $this->moduleName ?>-grid").jqGrid('getGridParam','selrow') == null) return false;
				var rowData = $("#<?php echo $this->moduleName ?>-grid").jqGrid('getRowData',$("#<?php echo $this->moduleName ?>-grid").jqGrid('getGridParam','selrow'));
				<?php foreach($this->fields as $v): ?>
				$('#<?php echo $this->moduleName ?>-edit-form [name=<?php echo $v['formName'] ?>]').val(rowData.<?php echo (!$v['dropDown'])?$this->tableName.'_'.$v['dbField']:($v['dropDown']['dbTable'].'_'.$v['dropDown']['idField']) ?>);
				<?php endforeach; ?>
				$('#<?php echo $this->moduleName ?>-edit-error').hide();
				$('#<?php echo $this->moduleName ?>-edit-dialog').dialog('open');
			}, position: "last", title:"Edit", cursor: "pointer"
		});
		$('#<?php echo $this->moduleName ?>-grid').jqGrid('navButtonAdd',"#<?php echo $this->moduleName ?>-pager",{
			caption:"Delete", buttonicon:"ui-icon-trash", onClickButton:function(){
				if($("#<?php echo $this->moduleName ?>-grid").jqGrid('getGridParam','selrow') == null) return false;
				if(!confirm('Are you sure you want to remove this item?')) return false;
				var rowData = $("#<?php echo $this->moduleName ?>-grid").jqGrid('getRowData',$("#<?php echo $this->moduleName ?>-grid").jqGrid('getGridParam','selrow'));
				
				<?php foreach($this->fields as $v):if(!$v['isPrimary']) continue; ?>
				$('#<?php echo $this->moduleName ?>-edit-form [name=<?php echo $v['formName'] ?>]').val(rowData.<?php echo (!$v['dropDown'])?$v['dbField']:($v['dropDown']['dbTable'].'_'.$v['dropDown']['idField']) ?>);
				$.ajax({
					url:'<?php echo site_url($this->controllerName.'/delete') ?>',
					dataType:'json',
					type:'post',
					data:{
						<?php echo $v['formName'] ?>:rowData.<?php echo (!$v['dropDown'])?$this->tableName.'_'.$v['dbField']:($v['dropDown']['dbTable'].'_'.$v['dropDown']['idField']) ?>
					},
					success:function(msg){
						if(msg.return){
							$('#<?php echo $this->moduleName ?>-grid').trigger('reloadGrid');
							
							$('#<?php echo $this->moduleName ?>-successmessage').html(msg.message);
							$('#<?php echo $this->moduleName ?>-success').stop(true,true).show().delay(5000).fadeOut();
						}else{
							alert('Oops! '.msg.message);
						}
					}
				});
				<?php endforeach; ?>
				
			}, position: "last", title:"Delete", cursor: "pointer"
		});
		$('#<?php echo $this->moduleName ?>-add-dialog').dialog({autoOpen:false,modal:true});
		$('#<?php echo $this->moduleName ?>-edit-dialog').dialog({autoOpen:false,modal:true});
		$('#<?php echo $this->moduleName ?>-add-addBtn').button();
		$('#<?php echo $this->moduleName ?>-edit-editBtn').button();
		$('#<?php echo $this->moduleName ?>-add-form').submit(function(){
			$('#<?php echo $this->moduleName ?>-add-addBtn').attr('disabled',true);
			$.ajax({
				url:'<?php echo site_url($this->controllerName.'/add') ?>',
				dataType:'json',
				type:'post',
				data:$('#<?php echo $this->moduleName ?>-add-form').serialize(),
				success:function(msg){
					if(msg.return){
						$('#<?php echo $this->moduleName ?>-grid').trigger('reloadGrid');
						$('#<?php echo $this->moduleName ?>-add-dialog').dialog('close');
						$('#<?php echo $this->moduleName ?>-add-form')[0].reset();
						
						$('#<?php echo $this->moduleName ?>-add-successmessage').html(msg.message);
						$('#<?php echo $this->moduleName ?>-add-success').stop(true,true).show().delay(5000).fadeOut();
					}else{
						$('#<?php echo $this->moduleName ?>-add-error').show();
						$('#<?php echo $this->moduleName ?>-add-errormessage').html(msg.message);
					}
					$('#<?php echo $this->moduleName ?>-add-addBtn').attr('disabled',false);
				}
			});
			return false;
		});
		$('#<?php echo $this->moduleName ?>-edit-form').submit(function(){
			$('#<?php echo $this->moduleName ?>-edit-editBtn').attr('disabled',true);
			$.ajax({
				url:'<?php echo site_url($this->controllerName.'/edit') ?>',
				dataType:'json',
				type:'post',
				data:$('#<?php echo $this->moduleName ?>-edit-form').serialize(),
				success:function(msg){
					
					if(msg.return){
						$('#<?php echo $this->moduleName ?>-grid').trigger('reloadGrid');
						$('#<?php echo $this->moduleName ?>-edit-dialog').dialog('close');
						$('#<?php echo $this->moduleName ?>-edit-form')[0].reset();
						
						$('#<?php echo $this->moduleName ?>-successmessage').html(msg.message);
						$('#<?php echo $this->moduleName ?>-success').stop(true,true).show().delay(5000).fadeOut();
						
					}else{
						$('#<?php echo $this->moduleName ?>-edit-error').show();
						$('#<?php echo $this->moduleName ?>-edit-errormessage').html(msg.message);
					}
					$('#<?php echo $this->moduleName ?>-edit-editBtn').attr('disabled',false);
				}
			});
			return false;
		});
	});
	</script>
	<table id="<?php echo $this->moduleName ?>-grid"></table> 
	<div id="<?php echo $this->moduleName ?>-pager"></div> 
	<div class="ui-widget" id="<?php echo $this->moduleName ?>-success" style="display:none;">
		<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"> 
			<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
			<strong>Success!</strong> <span id="<?php echo $this->moduleName ?>-successmessage"></span></p>
		</div>
	</div>
	<div id="<?php echo $this->moduleName ?>-add-dialog" style="display:none;" title="<?php echo $this->label ?> Add">
		<?php $this->addForm() ?>
		<div class="ui-widget" id="<?php echo $this->moduleName ?>-add-error" style="display:none;">
			<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
				<p>
					<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error</strong><br/>
					<div id="<?php echo $this->moduleName ?>-add-errormessage"></div>
				</p>
			</div>
		</div>
	</div>
	<div id="<?php echo $this->moduleName ?>-edit-dialog" style="display:none;" title="<?php echo $this->label ?> Edit">
		<?php $this->editForm() ?>
		<div class="ui-widget" id="<?php echo $this->moduleName ?>-edit-error" style="display:none;">
			<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
				<p>
					<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error</strong><br/>
					<div id="<?php echo $this->moduleName ?>-edit-errormessage"></div>
				</p>
			</div>
		</div>
	</div>
	<?php
	}
	public function grid(){
		foreach($this->fields as $field){
			if(count($field['dropDown'])==0){
				$this->CI->db->select($this->tableName.'.'.$field['dbField'].' AS '.$this->tableName.'_'.$field['dbField']);
			}else{
				$this->CI->db->select($field['dropDown']['dbTable'].'.'.$field['dropDown']['labelField'].' AS '.$field['dropDown']['dbTable'].'_'.$field['dropDown']['labelField']);
				$this->CI->db->select($field['dropDown']['dbTable'].'.'.$field['dropDown']['idField'].' AS '.$field['dropDown']['dbTable'].'_'.$field['dropDown']['idField']);
				$this->CI->db->join($field['dropDown']['dbTable'],$field['dropDown']['dbTable'].'.'.$field['dropDown']['idField'].' = '.$this->tableName.'.'.$field['dbField'],'left');
			}
		}
		
		$this->CI->db->limit($this->CI->input->post('rows'),($this->CI->input->post('page')-1)*$this->CI->input->post('rows'));
		
		if($this->sort!==false){
			$this->CI->db->order_by($this->tableName.'.'.$this->sort[0],$this->sort[1]);
		}
		
		$query = $this->CI->db->get($this->tableName);

		$result = $query->result();
		$count = $this->CI->db->count_all($this->tableName);
		
		if( $count >0 ) {
			$total_pages = ceil($count/(int)$this->CI->input->post('rows'));
		} else {
			$total_pages = 0;
		}
				
		
		$response->page = (int)$this->CI->input->post('page');
		$response->total = $total_pages;
		$response->records = $count;
		foreach($result as $k=>$v) {
			$response->rows[$k]['id']=$k;
			
			$cells = array();
			
			foreach($v as $d){
				$cells[] = $d;
			}
			
			$response->rows[$k]['cell']=$cells;
		}        
		die( json_encode($response));
	}
	public function add(){
		
		$this->CI->load->library('form_validation');
		
		foreach($this->fields as $f){
			if($f['required']){
				$this->CI->form_validation->set_rules($f['formName'],$f['label'], 'required');
			}
		}
		
		if ($this->CI->form_validation->run() == false)
		{
			die(json_encode(array('return'=>false,'message'=>validation_errors())));
		}
		else
		{
			$data = array();
			foreach($this->fields as $k=>$v){
				$data[$v['dbField']] = $this->CI->input->post($v['formName']);
			}
			$return = $this->CI->db->insert($this->tableName, $data);
			if($return){
				$message = 'Item successfully added.';
			}else{
				$message = 'Failed to add item.';
			}
			die(json_encode(array(
			'return'=>$return,
			'message'=>$message,
			)));
		}
		
		

	}
	public function edit(){
		$this->CI->load->library('form_validation');
		
		foreach($this->fields as $f){
			if($f['required']){
				$this->CI->form_validation->set_rules($f['formName'],$f['label'], 'required');
			}
		}
		
		if ($this->CI->form_validation->run() == false)
		{
			die(json_encode(array('return'=>false,'message'=>validation_errors())));
		}
		else
		{
			$data = array();
			foreach($this->fields as $k=>$v){
				$data[$v['dbField']] = $this->CI->input->post($v['formName']);
				if($v['isPrimary']){
					$this->CI->db->where($v['dbField'],$this->CI->input->post($v['formName']));
				}
			}
			
			$return = $this->CI->db->update($this->tableName, $data);
			if($return){
				$message = 'Item successfully updated.';
			}else{
				$message = 'Failed to update item.';
			}
			die(json_encode(array(
				'return'=>$return,
				'message'=>$message,
			)));
		}
		
	}
	public function delete(){
			
			foreach($this->fields as $k=>$v){
				if($v['isPrimary']){
					$this->CI->db->where($v['dbField'],$this->CI->input->post($v['formName']));
				}
			}

			$return = $this->CI->db->delete($this->tableName); 
			if($return){
				$message = 'Item successfully deleted.';
			}else{
				$message = 'Failed to delete item.';
			}
			die(json_encode(array(
				'return'=>$return,
				'message'=>$message,
			)));
	}
	
	
	public function addForm(){
		?>
		<form id="<?php echo $this->moduleName ?>-add-form">
			<?php 
			foreach($this->fields as $k=>$v){
				if($v['autoIncrement']==true) continue;
				if($v['input']!='select' && $v['input']!='textarea'){
					?><label><?php echo $v['input']!='hidden'?($v['label'].':<br/>'):'' ?><input <?php echo $v['maxChar']>0?('maxlength="'.$v['maxChar'].'"'):'' ?> type="<?php echo $v['input'] ?>" name="<?php echo $v['formName'] ?>"/></label><?php echo $v['input']!='hidden'?'<br/>':'' ?><?php
				}elseif($v['input']=='textarea'){
					?><label><?php echo $v['label'] ?>:<br/><textarea name="<?php echo $v['formName'] ?>"></textarea></label><br/><?php
				}elseif($v['input']=='select'){
					if(isset($v['dropDown']['sort'])){
						$this->CI->db->order_by($v['dropDown']['dbTable'].'.'.$v['dropDown']['sort'][0],$v['dropDown']['sort'][1]);
					}else{
						$this->CI->db->order_by($v['dropDown']['dbTable'].'.'.$v['dropDown']['labelField'],'asc');
					}
					$selectData = $this->CI->db->get($v['dropDown']['dbTable'])->result();
					?><label><?php echo $v['label'] ?>:<br/>
					<select name="<?php echo $v['formName'] ?>">
						<?php if($v['dropDownDefaultId']!==false && $v['dropDownDefaultLabel']!==false): ?>
						<option value="<?php echo $v['dropDownDefaultId']; ?>"><?php echo $v['dropDownDefaultLabel']; ?></option>
						<?php endif; ?>
						<?php foreach($selectData as $selItem): ?>
							<option value="<?php echo $selItem->$v['dropDown']['idField'] ?>"><?php echo $selItem->$v['dropDown']['labelField'] ?></option>
						<?php endforeach; ?>
					</select>
					</label><br/><?php
				}
			} 
			?>
			<input type="submit" id="<?php echo $this->moduleName ?>-add-addBtn" value="Add" />
		</form>
		<?php
	}
	
	public function editForm(){
		?>
		<form id="<?php echo $this->moduleName ?>-edit-form">
			<?php 
			foreach($this->fields as $k=>$v){
				if($v['input']!='select' && $v['input']!='textarea'){
					?><label><?php echo $v['input']!='hidden'?($v['label'].':<br/>'):'' ?><input <?php echo $v['maxChar']>0?('maxlength="'.$v['maxChar'].'"'):'' ?> type="<?php echo $v['input'] ?>" name="<?php echo $v['formName'] ?>"/></label><?php echo $v['input']!='hidden'?'<br/>':'' ?><?php
				}elseif($v['input']=='textarea'){
					?><label><?php echo $v['label'] ?>:<br/><textarea name="<?php echo $v['formName'] ?>"></textarea></label><br/><?php
				}elseif($v['input']=='select'){
					if(isset($v['dropDown']['sort'])){
						$this->CI->db->order_by($v['dropDown']['dbTable'].'.'.$v['dropDown']['sort'][0],$v['dropDown']['sort'][1]);
					}else{
						$this->CI->db->order_by($v['dropDown']['dbTable'].'.'.$v['dropDown']['labelField'],'asc');
					}
					$selectData = $this->CI->db->get($v['dropDown']['dbTable'])->result();
					?><label><?php echo $v['label'] ?>:<br/>
					<select name="<?php echo $v['formName'] ?>">
						<?php if($v['dropDownDefaultId']!==false && $v['dropDownDefaultLabel']!==false): ?>
						<option value="<?php echo $v['dropDownDefaultId']; ?>"><?php echo $v['dropDownDefaultLabel']; ?></option>
						<?php endif; ?>
						<?php foreach($selectData as $selItem): ?>
							<option value="<?php echo $selItem->$v['dropDown']['idField'] ?>"><?php echo $selItem->$v['dropDown']['labelField'] ?></option>
						<?php endforeach; ?>
					</select>
					</label><br/><?php
				}elseif($v['isPrimary']){
					?><input type="hidden" name="<?php echo $v['formName'] ?>" value=""/><?php
				}
			} 
			?>
			<input type="submit" id="<?php echo $this->moduleName ?>-edit-editBtn" value="Update" />
		</form>
		<?php
	}
}