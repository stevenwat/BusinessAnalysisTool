<?php
	//"Sales Overview";
	try{
		$database = new Database();
		$db = $database->getConnection();
		$sales_mtd = new SalesMtd($db);
		$stmt_sales_mtd = $sales_mtd->grabRecentSales();

		//Data points are for the charts
		$dataPoints = array();
		$dataPoints2 = array();
		$tableData = array();
		while ($row = $stmt_sales_mtd->fetch(PDO::FETCH_ASSOC)){
			array_push($dataPoints, array("y"=> $row['TotalSales'], "label"=> $row['Branch']));
			array_push($dataPoints2, array("y"=> $row['TotalAdjMgn'], "label"=> $row['Branch']));
			$adjMgnPerc = 0.00;
			if(!empty($row['TotalSales']) && !empty($row['TotalAdjMgn'])){
				if (($row['TotalSales']) && !empty($row['TotalAdjMgn'])){
					$adjMgnPerc=$row['TotalAdjMgn']/$row['TotalSales'];
				}
				else {
					$adjMgnPerc = 0;
				}
			}
			else {
				$adjMgnPerc = 0;
			}			
			$adjMgnPerc = round($adjMgnPerc*100,2);
			array_push($tableData,array(
				"TotalSales"=> $row['TotalSales'],
				"Branch"=> $row['Branch'], 
				"TotalSales"=> $row['TotalSales'],
				"TotalAdjMgn"=> $row['TotalAdjMgn'], 
				"AdjMgnPerc"=>$adjMgnPerc,
				"Region"=> $row['Region']));
		}
		$companyAdjMgnTotal = array_sum(array_column($tableData, "TotalAdjMgn"));
		$companySalesTotal = array_sum(array_column($tableData, "TotalSales"));		
		$link = null;
	} catch(\PDOException $ex){
		print($ex->getMessage());
	}
?>
<div style="text-align: center; width: 100%; margin-left: auto;">
	<h3 style="font-family:calibri; font-weight:bold;">MTD Adj Mgn and Sales</h3><br><br>
	<div id="adj-mgn-table" style="height: 400px; width: 800px;margin-left: auto; margin-right: auto; margin-bottom: 0.5rem;"></div>
	<div style="text-align: right;">
		<button id="adjMgnOverviewCopyButton" style="border-radius: 5%; box-shadow: none; margin-right: auto; font-weight: bold;" onclick="copyAdjMgnTable('AdjMgn');">
			<i class="fa fa-copy"></i>
			Adj Mgn $
		</button>
		<button id="salesOverviewCopyButton" style="border-radius: 5%; box-shadow: none; font-weight: bold;" onclick="copyAdjMgnTable('Sales');">
			<i class="fa fa-copy"></i>
			Sales $
		</button>		
		<button id="downloadXlsxButton" style="border-radius: 5%; box-shadow: none; font-weight: bold;">
			<i class="fa fa-download"></i>
			.XLSX
		</button>			
		<button id="downloadPdfButton" style="border-radius: 5%; box-shadow: none; font-weight: bold;">
			<i class="fa fa-download"></i>
			.PDF
		</button>		
	</div>
	<div style="margin-top: 1em; font-weight: bold; text-align: left;" class="totalValues">Sales Total: <span style="margin-left: 2.5rem;">$<?php echo number_format($companySalesTotal,2);?></span></div>
	<div style="margin-bottom: 6em; font-weight: bold; text-align: left;" class="totalValues">Adj Mgn Total: <span style="margin-left: 0.6rem;">$<?php echo number_format($companyAdjMgnTotal,2);?></span></div>
	<div id="chartContainer" style="height: 400px; width: 800px; margin-left: auto; margin-right: auto;"></div>
</div>
	<script>
	
			var fontType = "Calibri";
			var chart = new CanvasJS.Chart("chartContainer", {
				height: 600, //in pixels
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
					title: "Sales + Adj Mgn",
					titleFontSize: 14,
					labelFontSize: 14,
					titleFontFamily: fontType,
					labelFontFamily: fontType,
					prefix: "$ "
				},
				legend: {
					cursor: "pointer",
					itemclick: toggleDataSeries,
					fontFamily: fontType
				},
				data: [
				    {
				    	type: "stackedColumn",
						name: "Sales",
						showInLegend: true,
						yValueFormatString: "$ #,##0",
						dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
				    },{
						
						type: "stackedColumn",
						name: "Adj Mgn",
						showInLegend: true,
						yValueFormatString: "$#,##0",
						dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
					},					
				]
			});		
				
			chart.render();
			
			function toggleDataSeries(e) {
				if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
					e.dataSeries.visible = false;
				} else {
					e.dataSeries.visible = true;
				}
				e.chart.render();
			}			

			
		var adjMgnTable = new Tabulator("#adj-mgn-table", {
		 	height: 400, // set height of table (in CSS or here), this enables the Virtual DOM and improves render speed dramatically (can be any valid css height value)
			//width: 200,
		 	data: <?php echo json_encode($tableData, JSON_NUMERIC_CHECK);?>, //assign data to table
		 	groupHeader:function(value, count, data, group){				 //customize the groupHeader to remove the count value be default
				return value;
		 	},
			groupBy: "Region",
			clipboard:true,
		 	layout:"fitColumns", //fit columns to width of table (optional)
		 	//responsiveLayout:"hide",
		 	columns:[ //Define Table Columns
			 	{title:"Branch", field:"Branch"},
			 	{title:"Total Sales $", field:"TotalSales", formatter:"money", align:"left", widthGrow:2, sorter:"number", bottomCalc:"sum",bottomCalcFormatter:"money",bottomCalcFormatterParams:{formatter: "money"}, bottomCalcParams:{precision:2}},
			 	{title:"Total Adj Mgn $", field:"TotalAdjMgn", formatter:"money", widthGrow:2, sorter:"number", bottomCalc:"sum",bottomCalcFormatter:"money", bottomCalcFormatterParams:{formatter: "money"}, bottomCalcParams:{precision:2} },
			 	{title:"Total Adj Mgn %", field:"AdjMgnPerc", widthGrow:2, sorter:"number"}
			],
		});	

		//Copy the sales or adjusted margin values to the windows clipboard
		function copyAdjMgnTable(rowType){
			var dataToCopy = "";
			//rows + 1 for the admin account
			for (var i = 0; i < table.rowManager.rows.length; i++){
				if(rowType === "AdjMgn"){
					dataToCopy = dataToCopy + adjMgnTable.rowManager.rows[i].data.Branch + "	" + adjMgnTable.rowManager.rows[i].data.TotalAdjMgn + "\n";
				} else {
					dataToCopy = dataToCopy + adjMgnTable.rowManager.rows[i].data.Branch + "	" + adjMgnTable.rowManager.rows[i].data.TotalSales + "\n";
				}
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
			if(rowType === "AdjMgn"){
				swal("Data Copied", "The Adj Mgn Data was copied to the clipboard.", "success");
			} else {
				swal("Data Copied", "The Sales Data was copied to the clipboard.", "success");
			}
		}	
		//Adds a blank space below the javascript generated chart (could not move to the CSS file) 
		document.getElementById("chartContainer").style.marginBottom = "27rem";

		$("#downloadXlsxButton").click(function(){
			adjMgnTable.download("xlsx", "DebtorData.xlsx", {sheetName:"AdjMgnData"});
			swal("Download", "Sales Download Finished.", "success");
		});

		$("#downloadPdfButton").click(function(){
			adjMgnTable.download("pdf", "DebtorData.pdf", {
				title:"MTD Sales and Adjusted Margin",
				orientation: "portrait"	
			});
			swal("Download", "Sales Download Finished.", "success");
		});
	</script>		
<!--  Canvas JS library 
 <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://canvasjs.com/assets/script/jquery-ui.1.11.2.min.js"></script>-->

<!-- <div id="example-table"></div>-->
