<html>
	<head>
		<style type="text/css">
			.name{
				width: 180px;
			}
			.number{
				width: 60px;
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

				var outerHtml = "";
				var outerComps = [];
				var k = 0;
				for(var i=0;i<instance;i++){
					var innerHtml = "";
					var innerComps = [];
					var price = document.getElementById("price"+i).value;
					var weight = document.getElementById("weight"+i).value;
					var serving = document.getElementById("serving"+i).value;
					var amount = document.getElementById("amount"+i).value;
					console.log(price+weight+serving+amount);
					var pricePerWeight = price / weight;
					var pricePerServing = pricePerWeight * serving;
					var pricePerAmount = pricePerWeight * amount;
					console.log(pricePerWeight+"ha"+pricePerServing+"ah"+pricePerAmount);
					innerHtml += "Price per weight unit: $"+pricePerWeight.toFixed(2)+"<br/>";
					innerHtml += "Price per label serving: $"+pricePerServing.toFixed(2)+"<br/>";
					innerHtml += "Price for your portion: $"+pricePerAmount.toFixed(2)+"<br/>";
					
					for(var j=0;j<boxes[i];j++){
						var comp = document.getElementById("text"+i+j).value;
						var compWeight = document.getElementById("amount"+i+j).value;
						console.log(comp+"ha"+compWeight+"ah");
						
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
										
						if(outerComps.contains(comp)===false){
							outerHtml += comp+"<br/>";
							outerComps[k] = comp;
							k++;
						}
					}
					document.getElementById("calc"+i).innerHTML = innerHtml;
				}
				//document.getElementById("calc").innerHTML = outerHtml;
			}

		</script>
	</head>
	<body>

		<input type="button" id="btnColAdd" value="Add Ingredient" onclick="addColumn(this);" />
		<input type="button" id="btnColDel" value="Remove Ingredient" onclick="delColumn();" />			
		<br/>
		<input type="button" id="btnCalc" value="Calc" onclick="calc();" />	
		<br/>
		<div id="calc"></div>
		<br/>
		<div id="result"></div>
	</body>
</html>