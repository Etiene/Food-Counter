<html>
	<head>
		<style type="text/css">
			.name{
				width: 180px;
			}
			.nameERROR{
				width: 180px;
				background-color: #FF0000;
			}
			.number{
				width: 60px;
			}
			.numberERROR{
				width: 60px;
				background-color: #FF0000;
			}
			.weightCheckERROR{
				text-color:  #FF0000;
			}
			.container0{
				width:240px;
				float: left;
			}
			.container{
				width:110px;
				float:left;
			}
		</style>
		<script type="text/javascript">
			var components = [];
			var componentsWeight = [];
			var instance = 0;
			var boxes = [];
			boxes[0] = 0;
			function newTextBox(button)
			{	
				var parent = button.parentNode;	
				var col = parent.id;
				
				var newInput = document.createElement("INPUT");

				parent.insertBefore(createInput("text","text" + col + boxes[col],"text" + col + boxes[col],"name","","","Component..."),button);

				parent.insertBefore(createInput("text","amount" + col + boxes[col],"amount" + col + boxes[col],"number","","","Weight..."),button);
				boxes[col]++;
				
			}

			function delTextBox(parent)
			{			
				var col = parent.id;
				var idx = boxes[col]-1;
				var child = document.getElementById("text" + col + idx);
				parent.removeChild(child);
				var child = document.getElementById("amount" + col + idx);
				parent.removeChild(child);
				boxes[col]--;
			}
			
			window.onload = function()
            {
				var button = document.getElementById("btnColAdd");
				addColumn(button);
            };

            function addColumn(button){
                boxes[instance] = 0;
                var newCol = document.createElement("DIV");
				newCol.innerHTML = "Ingredient "+(instance+1)+".<br/>";
                newCol.id = instance;
				newCol.className = "container0";
				var parent = button.parentNode;
				parent.insertBefore(newCol, button);

				newCol.appendChild(createInput("button","btnAdd"+instance,"","","Add component", function() { newTextBox(this,null); }));
				newCol.appendChild(createInput("button","btnDel"+instance,"","","Remove component", function() { delTextBox(this.parentNode); }));
				newCol.appendChild(createInput("text","price"+instance,"price"+instance,"number","","","Price..."));
				newCol.appendChild(createInput("text","serving"+instance,"serving"+instance,"number","","","Serving..."));
				newCol.appendChild(createInput("text","amount"+instance,"amount"+instance,"number","","","Amount..."));			
				newCol.appendChild(createInput("text","weight"+instance,"weight"+instance,"number","","","Weight..."));
				var innerCalc = document.createElement("DIV");
				innerCalc.id = "calc"+instance;
				innerCalc.className = "innercalc";
				newCol.appendChild(innerCalc);
				instance++;
            }

            function delColumn(){
				var column = document.getElementById(instance-1);
				document.body.removeChild(column);
				boxes[instance] = 0;
				instance--;
            }
			function createInput(type,id,name,className,value,onclick,placeholder){
				var newInput = document.createElement("INPUT");
				newInput.type = type;
				newInput.id = id;
				newInput.className = className;
				newInput.name = name;
				newInput.value = value;
				newInput.onclick = onclick;
				newInput.placeholder = placeholder;
				
				return newInput;
			}


			Array.prototype.contains = function(obj) {
			    var i = this.length;
			    while (i--) {
			        if (this[i] === obj) {
			            return i;
			        }
			    }
			    return false;
			};
						
			function calc(){
	//			var outerHtml = "";
	//			var outerComps = [];
	//			var k = 0;
				for(var i=0;i<instance;i++){
					var innerHtml = "";
					var innerComps = [];
					var price = document.getElementById("price"+i).value;
					var weight = document.getElementById("weight"+i).value;
					var serving = document.getElementById("serving"+i).value;
					var amount = document.getElementById("amount"+i).value;
					var pricePerWeight = price / weight;
					var pricePerServing = pricePerWeight * serving;
					var pricePerAmount = pricePerWeight * amount;
					innerHtml += "Price per weight unit: $"+pricePerWeight.toFixed(2)+"<br/>";
					innerHtml += "Price per label serving: $"+pricePerServing.toFixed(2)+"<br/>";
					innerHtml += "Price for your portion: $"+pricePerAmount.toFixed(2)+"<br/>";
					
					for(var j=0;j<boxes[i];j++){
						var comp = document.getElementById("text"+i+j).value;
						var compWeight = document.getElementById("amount"+i+j).value;						
						var weightInUnit = compWeight / serving ;
						var weightInAmount = weightInUnit * amount;
						var weightFull = weightInUnit * weight;
						var compPricePerWU = pricePerWeight * weightInUnit;
						var compPricePerServing =  pricePerServing * weightInUnit;
						var compPricePerAmount = pricePerAmount * weightInUnit;
						var compPricePerJar = price * weightInUnit;
						
						innerHtml += "% of "+comp+" in weight unit: "+(weightInUnit*100).toFixed(2)+"%<br/>";
						innerHtml += "Weight of "+comp+" in your portion: "+weightInAmount.toFixed(3)+"<br/>";
						innerHtml += "Weight of "+comp+" in the whole pack: "+weightFull.toFixed(2)+"<br/>";
						innerHtml += "Price of "+comp+" per Weight Unit: $"+compPricePerWU.toFixed(2)+"<br/>";
						innerHtml += "Price of "+comp+" per Serving: $"+compPricePerServing.toFixed(2)+"<br/>";
						innerHtml += "Price of "+comp+" for your portion: $"+compPricePerAmount.toFixed(2)+"<br/>";
						innerHtml += "Price of "+comp+" per pack: $"+compPricePerJar.toFixed(2)+"<br/>";
					}
					document.getElementById("calc"+i).innerHTML = innerHtml;
				}
			}

			function totalsCheck(){
				var calc = document.getElementById("calc");
				calc.innerHTML = "";
				fillComponents();
				for(var i = 0; i<components.length; i++){
					console.log(components[i]);
										
					calc.insertAdjacentHTML ('beforeEnd', components[i]+": ");
					calc.appendChild(createInput("text","min"+i,"min"+i,"number","","","Min..."));
					calc.appendChild(createInput("text","max"+i,"max"+i,"number","","","Max..."));
					calc.insertAdjacentHTML ('beforeEnd', "<br/>");							
				}
				calc.appendChild(createInput("button","totalsCalc","","","Go!", function() { totalsCalc(); }));
			}
			function fillComponents(){
				//console.log("oi");
				components = [];
				var k = 0;
				for(var i=0;i<instance;i++)
					for(var j=0;j<boxes[i];j++){
						var comp = document.getElementById("text"+i+j).value;	
						if(components.contains(comp)===false && comp !=""){
							components[k] = comp;
							k++;
						}
					}

			}
			function sumWeight(){
				components = [];
				var k = 0;
				for(var i=0;i<instance;i++){
					var serving = document.getElementById("serving"+i).value;
					var portion = document.getElementById("amount"+i).value;
					if(serving=="" || portion == ""){
						alert("Please fill the fields serving and amount for all ingredients.");
					}
						
					for(var j=0;j<boxes[i];j++){
						var comp = document.getElementById("text"+i+j).value;
						var weight = ((document.getElementById("amount"+i+j).value)/serving)*portion;
						var idx = components.contains(comp);
						if(idx===false && comp !=""){
							componentsWeight[k] = weight;
							components[k] = comp;
							k++;
						}else 
							componentsWeight[idx] += weight;
						console.log(weight);
					}
				}
			}
			function totalsCalc(){
				sumWeight();
				resetClass();
				for(var k = 0; k<components.length; k++){
					console.log(components[k]+" peso:"+componentsWeight[k]);
					var min = document.getElementById("min"+k).value;
					var max = document.getElementById("max"+k).value;
					var wC = document.getElementById("wC"+k);
					if(wC)
						wC.parentNode.removeChild(wC);

					document.getElementById("max"+k).insertAdjacentHTML ('afterEnd', " <span id='wC"+k+"'>"+componentsWeight[k]+"</span>");
					if((componentsWeight[k]>max && max!="") || (componentsWeight[k]<min && min != ""))
						changeClassERROR(components[k]);
					
				}
			}
			function changeClassERROR(value){
				for(var i=0;i<instance;i++)
					for(var j=0;j<boxes[i];j++)
						if(document.getElementById("text"+i+j).value==value){
							document.getElementById("text"+i+j).className = "nameERROR";
							document.getElementById("amount"+i+j).className = "numberERROR";
							document.getElementById("amount"+i).className = "numberERROR";
						}
		//		for(var k = 0; k<components.length; k++)
		//			if(components[k]==value){
		//				var wC = document.getElementById("wC"+k);
		//				if(wC) 
		//					className = "weightCheckERROR";
		//			}
			}
			function resetClass(){
				for(var i=0;i<instance;i++)
					for(var j=0;j<boxes[i];j++){
						document.getElementById("text"+i+j).className = "name";
						document.getElementById("amount"+i+j).className = "number";
						document.getElementById("amount"+i).className = "number";
					}
		//		for(var k = 0; k<components.length; k++){
		//			var wC = document.getElementById("wC"+k);
		//			if(wC) 
		//				className = "weightCheck";
		//		}
			}
		</script>
	</head>
	<body>

		<input type="button" id="btnColAdd" value="Add Ingredient" onclick="addColumn(this);" />
		<input type="button" id="btnColDel" value="Remove Ingredient" onclick="delColumn();" />			
		<br/>
		<input type="button" id="btnTotals" value="Check Totals" onclick="totalsCheck();" />	
		<br/>
		<input type="button" id="btnCalc" value="Calc" onclick="calc();" />	
		<br/>
		<div id="calc"></div>
		<br/>
		<div id="result"></div>
	</body>
</html>