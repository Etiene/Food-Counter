<html>
	<head>
		<link href="lackofstyle.css" rel="stylesheet" type="text/css">
		<title>Simple Food Stuff Calculator v1.0 - By Etiene Dalcol</title>
	</head>
	<body>
	<div class="wrapper">
	<h1>Food Calculator</h1>
		<div class="container0">
			<input type="button" class="button" id="btnColAdd" value="Add Product" onclick="addColumn(this);" />
			<input type="button" class="button" id="btnColDel" value="Remove Product" onclick="delColumn();" />			
			<br/>
			<input type="button" class="button" id="btnTotals" value="Check Totals" onclick="totalsCheck();" />	
			<br/>
			<input type="button" class="button" id="btnCalc" value="Check Comparisons" onclick="calc();" />	
			<br/>
			<div id="calc"></div>
			<br/>
			<div id="result"></div>
		</div>
		</div>
		<script src="fc.js"></script>
	</body>
</html>