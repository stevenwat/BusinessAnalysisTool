<?php
	try{
		//grabInventoryTotals
		$inventoryDataPoints = array();
		$inventoryData = array();
				
		$database = new Database();
		$db = $database->getConnection();
		$inventory_totals = new InventoryTotals($db);
		$stmt_inventory_totals = $inventory_totals->grabInventoryTotals();			

		while ($row = $stmt_inventory_totals->fetch(PDO::FETCH_ASSOC)){
			array_push($inventoryDataPoints, array("y"=> $row['TotalCost'], "label"=> $row['Branch']));
			array_push($inventoryData, array("TotalCost"=> $row['TotalCost'], "Branch"=> $row['Branch'], "Region"=> $row['Region']));
		}	
		$database = null;
		//$database->close();
		$link = null;
	} catch(\PDOException $ex){
		print($ex->getMessage());
	}		
?>
<div style="text-align: center; width: 100%; margin-left: auto;">
	<h3 style="font-family:calibri; font-weight:bold;">Inventory Valuation</h3><br><br>	
	<div style="text-align: left;">
		<div style= "display: inline-block;">
			<div id="invTable" style="height: 400px; width: 300px; margin-right: 2.5rem;"></div>	<!-- display: inline-block; required in javascript- overwritten in canvasjs library -->
			<button id="testButton" style="position: absolute; margin-left: 4rem; border-radius: 5%; box-shadow: none; font-weight: bold;"> <!-- onclick="copyInvTable()"> -->
				<i class="fa fa-copy"></i> Total Cost $
			</button>
			<button id="invXlsxDownloadButton" style="position: absolute; margin-left: 15.8rem; border-radius: 5%; box-shadow: none; font-weight: bold;">
				<i class="fa fa-download"></i> .XLSX
			</button>
			<button id="invPdfDownloadButton" style="position: absolute; margin-left: 23.7rem; border-radius: 5%; box-shadow: none; font-weight: bold;">
				<i class="fa fa-download"></i> .PDF
			</button>			
		</div>
		<div id="invChartContainer" style="height: 400px; width: 500px; display: inline-block;"></div>	
		<div id="invTableDetailed" style="height: 600px; width: 1140px;margin-left: auto; margin-right: auto; margin-bottom: 0.5rem;"></div>
	</div>
</div>
<script>

		var fontType = "Calibri";
		var chartNew = new CanvasJS.Chart("invChartContainer", {
			height: 400, //in pixels
			width: 800,	//Remove to make responsive
			title: {
				//text: "MTD Adj Mgn and Sales",
				fontSize: 18,
				fontFamily: fontType
			},
			theme: "light2",	//"light1", "dark1", "dark2"
			animationEnabled: true,
			toolTip:{
				shared: true,
				reversed: true
			},
			axisX: {
				interval: 1,
				labelFontSize: 14,
				titleFontSize: 14,
				titleFontFamily: fontType,
				labelFontFamily: fontType
			},	
			axisY: {
				title: "Cost $",
				titleFontSize: 14,
				labelFontSize: 14,
				titleFontFamily: fontType,
				labelFontFamily: fontType,
				prefix: "$ "
			},
			legend: {
				cursor: "pointer",
				//itemclick: toggleDataSeries,
				fontFamily: fontType
			},
			data: [
			    {
			    	type: "stackedColumn",
					name: "Inventory Valuation $",
					showInLegend: true,
					yValueFormatString: "$ #,##0",
					dataPoints: <?php echo json_encode($inventoryDataPoints, JSON_NUMERIC_CHECK); ?>
			    }				
			]
		});		
		chartNew.render();	
	
	var table = new Tabulator("#invTable", {
		height: 400, // set height of table (in CSS or here), this enables the Virtual DOM and improves render speed dramatically (can be any valid css height value)
		//width: 200,
		clipboardCopySelector: "table",
		selectable: true,
		clipboard:true,
		data: <?php echo json_encode($inventoryData, JSON_NUMERIC_CHECK);?>, //assign data to table
		layout:"fitColumns", //fit columns to width of table (optional)
 		groupHeader:function(value, count, data, group){				 //customize the groupHeader to remove the count value be default
			return value;
 		},
		groupBy: "Region",
		columns:[ //Define Table Columns
			{title:"Branch", field:"Branch"},
			{title:"Total Cost $", field:"TotalCost", formatter:"money", align:"left", widthGrow:1, sorter:"number", bottomCalc:"sum",bottomCalcFormatter:"money",bottomCalcFormatterParams:{formatter: "money"}, bottomCalcParams:{precision:2}},
			],
			/*rowClick:function(e, row){ //trigger an alert message when the row is clicked
				alert("Row " + row.getData().id + " Clicked!!!!");
			},*/
	});	

	function copyInvTable(){
		var dataToCopy = "";
		for (var i = 0; i < table.rowManager.rows.length; i++){
			dataToCopy = dataToCopy + table.rowManager.rows[i].data.Branch + "	" + table.rowManager.rows[i].data.TotalCost + "\n";
		}
		const el = document.createElement('textarea');
		el.value = dataToCopy;
		el.setAttribute('readonly', '');
		el.style.position = 'absolute';
		el.style.left = '-9999px';
		document.body.appendChild(el);
		el.select();
		document.execCommand('copy');
		document.body.removeChild(el);	
		swal("Data Copied", "Inventory Data was copied to the clipboard.", "success");
	}
	document.getElementById("invTable").style.display = "inline-block";	

	//Download Inventory Summary Data
	$("#invXlsxDownloadButton").click(function(){
		table.download("xlsx", "InventorySummaryData.xlsx", {sheetName:"Inventory Summary"});
		swal("Download", "Inventory Summary Download Finished.", "success");
	});

	$("#invPdfDownloadButton").click(function(){
		table.download("pdf", "InventorySummaryData.pdf", {
			title:"Inventory Summary Data",
			orientation: "portrait"	
		});
		swal("Download", "Inventory Summary Download Finished.", "success");
	});
</script>
