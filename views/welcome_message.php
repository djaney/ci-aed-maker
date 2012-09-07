<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>AddEditDelete Library for Codeigniter</title>
	<script type="text/javascript" src="<?php echo base_url('res/jqgrid').'/js/jquery-1.4.2.min.js' ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('res/jqueryui').'/js/jquery-ui-1.8.23.custom.min.js' ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('res/jqgrid').'/js/i18n/grid.locale-en.js' ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('res/jqgrid').'/js/jquery.jqGrid.min.js' ?>"></script>
	<link rel="stylesheet" href="<?php echo base_url('res/jqgrid').'/css/ui.jqgrid.css' ?>" />
	<link rel="stylesheet" href="<?php echo base_url('res/jqueryui').'/css/ui-lightness/jquery-ui-1.8.23.custom.css' ?>" />
	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px auto;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
		width: 1037px;
	}
	</style>
</head>
<body>

<div id="container">
	<h1>CI AED Maker Library for Codeigniter</h1>

	<div id="body">
	
		<?php $this->aed_maker->manager() ?>
	
		<p>Allows rapid development of simple add/edit/delete modules in Codeigniter.</p>
		<p>It works by defining the fields that correspods to a database structure by passing them as an array parameter.</p>
		<p>
			Requirements
			<ol>
				<li>jQuery 1.4.2</li>
				<li>jQueryUI 1.8.23</li>
				<li>jqGrid 3.8.2</li>
			</ol>
		</p>
		<p>
			<h3>Beta Version</h3>
			<h3>Features</h3>
			<ol>
				<li>Generates a Grid</li>
				<li>Generates a Add/Edit/Delete functionality with validation</li>
			</ol>
		</p>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

</body>
</html>