<?php
	try{
		/*ORIGINAL ODBC CONNECTION CODE-- commented out in case the connection is made active again
		$dbName = "\\\\dc01\\data\\marketing\\margin analysis\\NewAdjustedSalesMargin.mdb";
		if (!file_exists($dbName)) {
			die("Could not find database file.");
		}
		//Just requires that php_pdo_odbc.dll is uncommented in the php.ini file in the apache directory 
		$db_username = "stevenwat";
		$db_password = "kzw67....72tmm7"; //RESET PASSWORD HERE
		$database = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb, *.accdb)}; DBQ=$dbName; Uid=$db_username; Pwd=$db_password;");
		
		$sql  = "SELECT Branch, DebAcc, Name, OpenBalance, StopCredit, CreditLimit, CurrentBalance, Thirty, Sixty, Ninety, OneTwenty, MTDSales, MTDAdjMgn FROM tblBiDebtors";
		$handle = $database->prepare($sql);
		$handle->execute();
		$result = $handle->fetchAll(\PDO::FETCH_OBJ);
		$debtorTableData = array();
		
		foreach($result as $row){
			array_push($debtorTableData, array("Branch"=> $row->Branch, 
					"DebAcc"=> $row->DebAcc, 
					"Name"=> $row->Name, 
					"OpenBal"=>$row->OpenBalance, 
					"Stop"=> $row->StopCredit,
					"CredLimit"=> $row->CreditLimit,
					"CurrBal"=>$row->CurrentBalance,
					"Thirty"=> $row->Thirty,	
					"Sixty"=> $row->Sixty,
					"Ninety"=>$row->Ninety,
					"OneTwenty"=> $row->OneTwenty,
					"MTDSales"=>$row->MTDSales,
					"MTDAdjMgn"=> $row->MTDAdjMgn														
			));
		}	
		*/
		$database = new Database();
		$db = $database->getConnection();
		$debtor_data = new Debtors($db);
		$stmt_debtors = $debtor_data->grabDebtors();
		
		/* Associative array for the table data */
		$debtorTableData = array();
		while ($row = $stmt_debtors->fetch(PDO::FETCH_ASSOC)){
			array_push($debtorTableData, array("Branch"=> $row['Branch'], 
				"DebAcc"=> $row['DebAcc'], 
				"Name"=> $row['Name'], 
				"OpenBal"=>$row['OpenBalance'], 
				"Stop"=> $row['StopCredit'],
				"CredLimit"=> $row['CreditLimit'],
				"CurrBal"=>$row['CurrentBalance'],
				"Thirty"=> $row['Thirty'],	
				"Sixty"=> $row['Sixty'],
				"Ninety"=>$row['Ninety'],
				"OneTwenty"=> $row['OneTwenty'],
				"MTDSales"=>$row['MTDSales'],
				"MTDAdjMgn"=> $row['MTDAdjMgn']														
			));
		}		
		
		$link = null;
		$database = null;
	} catch(\PDOException $ex){
		print($ex->getMessage());
	}
?>
<div style="text-align: center; width: 100%; margin-left: auto; margin-bottom: 4.5rem;">
	<h3 style="font-family:calibri; font-weight:bold;">Debtor Details and Monthly Sales</h3><br><br>
	<div id="debtor-table" style="height: 600px; width: 1140px;margin-left: auto; margin-right: auto; margin-bottom: 0.5rem;"></div>
	<div style="text-align: right;">
		<button id="refreshTableButton" style="border-radius: 5%; box-shadow: none; font-weight: bold;">
			<i class="fa fa-refresh"></i>
		</button>	
		<button id="debtorXlsxButton" style="border-radius: 5%; box-shadow: none; margin-right: auto; font-weight: bold;">
			<i class="fa fa-download"></i>
			.XLSX
		</button>
		<button id="debtorPdfButton" style="border-radius: 5%; box-shadow: none; font-weight: bold;">
			<i class="fa fa-download"></i>
			.PDF
		</button>		
	</div>
</div>
<script>
	var debtorTable = new Tabulator("#debtor-table", {
		height: 600, // set height of table (in CSS or here), this enables the Virtual DOM and improves render speed dramatically (can be any valid css height value)
		//width: 200,
		responsiveLayout:"hide",
		clipboardCopySelector: "table",
		selectable: true,
		clipboard:true,
		data: <?php echo json_encode($debtorTableData, JSON_NUMERIC_CHECK);?>, //assign data to table
		layout:"fitColumns", //fit columns to width of table (optional)
		//responsiveLayout:"hide",
 		groupHeader:function(value, count, data, group){				 //customize the groupHeader to remove the count value be default
			return value;
 		},
		groupBy: "Branch",
		columns:[ //Define Table Columns
			{title:"DebAcc", field:"DebAcc", widthGrow:5, responsive:0, headerFilter:"input", headerFilterPlaceholder:"..."},
			{title:"Name", field:"Name", widthGrow:10, responsive:0, headerFilter:"input", headerFilterPlaceholder:"..."},
			{title:"Branch", field:"Branch", widthGrow:5, headerFilter:"input", headerFilterPlaceholder:"..."},
			{title:"OpenBal", field:"OpenBal",widthGrow:5},
			{title:"Stop", field:"Stop", widthGrow:4},
			{title:"CredLimit", field:"CredLimit", widthGrow:5},
			{title:"30", field:"Thirty", widthGrow:3, sorter:"number"},
			{title:"60", field:"Sixty", widthGrow:3, sorter:"number"},
			{title:"90", field:"Ninety", widthGrow:3, sorter:"number"},
			{title:"120", field:"OneTwenty", widthGrow:4, sorter:"number"},
			{title:"MTD Sales", field:"MTDSales", widthGrow:6, sorter:"number"},
			{title:"MTD AdjMgn", field:"MTDAdjMgn", widthGrow:5, sorter:"number"}
			]
	});	
	//console.log(Object.keys(debtorTable));
	
	//Refresh Data in the table if it is not displayed correctly
	$("#refreshTableButton").click(function(){
		debtorTable.data = <?php echo json_encode($debtorTableData, JSON_NUMERIC_CHECK);?>; 
		debtorTable.redraw();
	});

	//Download Debtor Data
	$("#debtorXlsxButton").click(function(){
		debtorTable.download("xlsx", "DebtorData.xlsx", {sheetName:"DebtorData"});
		swal("Download", "Debtor Download Finished.", "success");
	});

	$("#debtorPdfButton").click(function(){
		debtorTable.download("pdf", "DebtorData.pdf", {
			title:"Debtor Data",
			orientation: "landscape"	
		});
		swal("Download", "Debtor Download Finished.", "success");
	});

</script>

