<?php
// This script and data application were generated by AppGini 23.11
// Download AppGini for free from https://bigprof.com/appgini/download/

	include_once(__DIR__ . '/lib.php');
	@include_once(__DIR__ . '/hooks/gestion.php');
	include_once(__DIR__ . '/gestion_dml.php');

	// mm: can the current member access this page?
	$perm = getTablePermissions('gestion');
	if(!$perm['access']) {
		echo error_message($Translation['tableAccessDenied']);
		exit;
	}

	$x = new DataList;
	$x->TableName = 'gestion';

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = [
		"`gestion`.`CODGESTION`" => "CODGESTION",
		"`gestion`.`DESGESTION`" => "DESGESTION",
	];
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = [
		1 => 1,
		2 => 2,
	];

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = [
		"`gestion`.`CODGESTION`" => "CODGESTION",
		"`gestion`.`DESGESTION`" => "DESGESTION",
	];
	// Fields that can be filtered
	$x->QueryFieldsFilters = [
		"`gestion`.`CODGESTION`" => "CODGESTION",
		"`gestion`.`DESGESTION`" => "DESGESTION",
	];

	// Fields that can be quick searched
	$x->QueryFieldsQS = [
		"`gestion`.`CODGESTION`" => "CODGESTION",
		"`gestion`.`DESGESTION`" => "DESGESTION",
	];

	// Lookup fields that can be used as filterers
	$x->filterers = [];

	$x->QueryFrom = "`gestion` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 1;
	$x->HideTableView = ($perm['view'] == 0 ? 1 : 0);
	$x->AllowDelete = $perm['delete'];
	$x->AllowMassDelete = (getLoggedAdmin() !== false);
	$x->AllowInsert = $perm['insert'];
	$x->AllowUpdate = $perm['edit'];
	$x->SeparateDV = 1;
	$x->AllowDeleteOfParents = 0;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = (getLoggedAdmin() !== false);
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowPrintingDV = 1;
	$x->AllowCSV = 1;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation['quick search'];
	$x->ScriptFileName = 'gestion_view.php';
	$x->TableTitle = 'Gestion';
	$x->TableIcon = 'table.gif';
	$x->PrimaryKey = '`gestion`.`CODGESTION`';

	$x->ColWidth = [150, 150, ];
	$x->ColCaption = ['CODGESTION', 'DESGESTION', ];
	$x->ColFieldName = ['CODGESTION', 'DESGESTION', ];
	$x->ColNumber  = [1, 2, ];

	// template paths below are based on the app main directory
	$x->Template = 'templates/gestion_templateTV.html';
	$x->SelectedTemplate = 'templates/gestion_templateTVS.html';
	$x->TemplateDV = 'templates/gestion_templateDV.html';
	$x->TemplateDVP = 'templates/gestion_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HasCalculatedFields = false;
	$x->AllowConsoleLog = false;
	$x->AllowDVNavigation = true;

	// hook: gestion_init
	$render = true;
	if(function_exists('gestion_init')) {
		$args = [];
		$render = gestion_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: gestion_header
	$headerCode = '';
	if(function_exists('gestion_header')) {
		$args = [];
		$headerCode = gestion_header($x->ContentType, getMemberInfo(), $args);
	}

	if(!$headerCode) {
		include_once(__DIR__ . '/header.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/header.php');
		echo str_replace('<%%HEADER%%>', ob_get_clean(), $headerCode);
	}

	echo $x->HTML;

	// hook: gestion_footer
	$footerCode = '';
	if(function_exists('gestion_footer')) {
		$args = [];
		$footerCode = gestion_footer($x->ContentType, getMemberInfo(), $args);
	}

	if(!$footerCode) {
		include_once(__DIR__ . '/footer.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/footer.php');
		echo str_replace('<%%FOOTER%%>', ob_get_clean(), $footerCode);
	}
