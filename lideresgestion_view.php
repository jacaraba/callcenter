<?php
// This script and data application were generated by AppGini 23.11
// Download AppGini for free from https://bigprof.com/appgini/download/

	include_once(__DIR__ . '/lib.php');
	@include_once(__DIR__ . '/hooks/lideresgestion.php');
	include_once(__DIR__ . '/lideresgestion_dml.php');

	// mm: can the current member access this page?
	$perm = getTablePermissions('lideresgestion');
	if(!$perm['access']) {
		echo error_message($Translation['tableAccessDenied']);
		exit;
	}

	$x = new DataList;
	$x->TableName = 'lideresgestion';

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = [
		"`lideresgestion`.`LLAVE`" => "LLAVE",
		"IF(    CHAR_LENGTH(`gestion1`.`CODGESTION`), CONCAT_WS('',   `gestion1`.`CODGESTION`), '') /* CODGESTION */" => "CODGESTION",
		"IF(    CHAR_LENGTH(`lideres1`.`CEDULA`) || CHAR_LENGTH(`lideres1`.`NOMBRE`), CONCAT_WS('',   `lideres1`.`CEDULA`, ' - ', `lideres1`.`NOMBRE`), '') /* LIDER */" => "CEDULA",
		"IF(    CHAR_LENGTH(`lideres2`.`CELULAR`) || CHAR_LENGTH(`lideres2`.`NOMBRE`), CONCAT_WS('',   `lideres2`.`CELULAR`, ' - ', `lideres2`.`NOMBRE`), '') /* CELULAR */" => "CELULAR",
		"`lideresgestion`.`OBSERVACIONES`" => "OBSERVACIONES",
		"`lideresgestion`.`ESTADO`" => "ESTADO",
	];
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = [
		1 => '`lideresgestion`.`LLAVE`',
		2 => '`gestion1`.`CODGESTION`',
		3 => 3,
		4 => 4,
		5 => 5,
		6 => 6,
	];

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = [
		"`lideresgestion`.`LLAVE`" => "LLAVE",
		"IF(    CHAR_LENGTH(`gestion1`.`CODGESTION`), CONCAT_WS('',   `gestion1`.`CODGESTION`), '') /* CODGESTION */" => "CODGESTION",
		"IF(    CHAR_LENGTH(`lideres1`.`CEDULA`) || CHAR_LENGTH(`lideres1`.`NOMBRE`), CONCAT_WS('',   `lideres1`.`CEDULA`, ' - ', `lideres1`.`NOMBRE`), '') /* LIDER */" => "CEDULA",
		"IF(    CHAR_LENGTH(`lideres2`.`CELULAR`) || CHAR_LENGTH(`lideres2`.`NOMBRE`), CONCAT_WS('',   `lideres2`.`CELULAR`, ' - ', `lideres2`.`NOMBRE`), '') /* CELULAR */" => "CELULAR",
		"`lideresgestion`.`OBSERVACIONES`" => "OBSERVACIONES",
		"`lideresgestion`.`ESTADO`" => "ESTADO",
	];
	// Fields that can be filtered
	$x->QueryFieldsFilters = [
		"`lideresgestion`.`LLAVE`" => "LLAVE",
		"IF(    CHAR_LENGTH(`gestion1`.`CODGESTION`), CONCAT_WS('',   `gestion1`.`CODGESTION`), '') /* CODGESTION */" => "CODGESTION",
		"IF(    CHAR_LENGTH(`lideres1`.`CEDULA`) || CHAR_LENGTH(`lideres1`.`NOMBRE`), CONCAT_WS('',   `lideres1`.`CEDULA`, ' - ', `lideres1`.`NOMBRE`), '') /* LIDER */" => "LIDER",
		"IF(    CHAR_LENGTH(`lideres2`.`CELULAR`) || CHAR_LENGTH(`lideres2`.`NOMBRE`), CONCAT_WS('',   `lideres2`.`CELULAR`, ' - ', `lideres2`.`NOMBRE`), '') /* CELULAR */" => "CELULAR",
		"`lideresgestion`.`OBSERVACIONES`" => "OBSERVACIONES",
		"`lideresgestion`.`ESTADO`" => "ESTADO",
	];

	// Fields that can be quick searched
	$x->QueryFieldsQS = [
		"`lideresgestion`.`LLAVE`" => "LLAVE",
		"IF(    CHAR_LENGTH(`gestion1`.`CODGESTION`), CONCAT_WS('',   `gestion1`.`CODGESTION`), '') /* CODGESTION */" => "CODGESTION",
		"IF(    CHAR_LENGTH(`lideres1`.`CEDULA`) || CHAR_LENGTH(`lideres1`.`NOMBRE`), CONCAT_WS('',   `lideres1`.`CEDULA`, ' - ', `lideres1`.`NOMBRE`), '') /* LIDER */" => "CEDULA",
		"IF(    CHAR_LENGTH(`lideres2`.`CELULAR`) || CHAR_LENGTH(`lideres2`.`NOMBRE`), CONCAT_WS('',   `lideres2`.`CELULAR`, ' - ', `lideres2`.`NOMBRE`), '') /* CELULAR */" => "CELULAR",
		"`lideresgestion`.`OBSERVACIONES`" => "OBSERVACIONES",
		"`lideresgestion`.`ESTADO`" => "ESTADO",
	];

	// Lookup fields that can be used as filterers
	$x->filterers = ['CODGESTION' => 'CODGESTION', 'CEDULA' => 'LIDER', 'CELULAR' => 'CELULAR', ];

	$x->QueryFrom = "`lideresgestion` LEFT JOIN `gestion` as gestion1 ON `gestion1`.`CODGESTION`=`lideresgestion`.`CODGESTION` LEFT JOIN `lideres` as lideres1 ON `lideres1`.`CEDULA`=`lideresgestion`.`CEDULA` LEFT JOIN `lideres` as lideres2 ON `lideres2`.`CEDULA`=`lideresgestion`.`CELULAR` ";
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
	$x->ScriptFileName = 'lideresgestion_view.php';
	$x->TableTitle = 'Gestion Lideres';
	$x->TableIcon = 'table.gif';
	$x->PrimaryKey = '`lideresgestion`.`LLAVE`';

	$x->ColWidth = [150, 150, 150, 150, 150, ];
	$x->ColCaption = ['LLAVE', 'CODGESTION', 'LIDER', 'CELULAR', 'ESTADO', ];
	$x->ColFieldName = ['LLAVE', 'CODGESTION', 'CEDULA', 'CELULAR', 'ESTADO', ];
	$x->ColNumber  = [1, 2, 3, 4, 6, ];

	// template paths below are based on the app main directory
	$x->Template = 'templates/lideresgestion_templateTV.html';
	$x->SelectedTemplate = 'templates/lideresgestion_templateTVS.html';
	$x->TemplateDV = 'templates/lideresgestion_templateDV.html';
	$x->TemplateDVP = 'templates/lideresgestion_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HasCalculatedFields = false;
	$x->AllowConsoleLog = false;
	$x->AllowDVNavigation = true;

	// hook: lideresgestion_init
	$render = true;
	if(function_exists('lideresgestion_init')) {
		$args = [];
		$render = lideresgestion_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: lideresgestion_header
	$headerCode = '';
	if(function_exists('lideresgestion_header')) {
		$args = [];
		$headerCode = lideresgestion_header($x->ContentType, getMemberInfo(), $args);
	}

	if(!$headerCode) {
		include_once(__DIR__ . '/header.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/header.php');
		echo str_replace('<%%HEADER%%>', ob_get_clean(), $headerCode);
	}

	echo $x->HTML;

	// hook: lideresgestion_footer
	$footerCode = '';
	if(function_exists('lideresgestion_footer')) {
		$args = [];
		$footerCode = lideresgestion_footer($x->ContentType, getMemberInfo(), $args);
	}

	if(!$footerCode) {
		include_once(__DIR__ . '/footer.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/footer.php');
		echo str_replace('<%%FOOTER%%>', ob_get_clean(), $footerCode);
	}
