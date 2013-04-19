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

	parent.insertBefore(createInput("text","text" + col + boxes[col],"text" + col + boxes[col],"name","","","Ingredient Name..."),button);

	parent.insertBefore(createInput("text","amount" + col + boxes[col],"amount" + col + boxes[col],"numberComp","","","Weight..."),button);
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
	var div = '<div class="container0" id="'+instance+'"> Product '+(instance+1)+':<br/></div>';
	button.parentNode.insertAdjacentHTML('beforebegin',div);
	var newCol = document.getElementById(instance);
	
	newCol.appendChild(createInput("button","btnAdd"+instance,"","button","Add Ingredient", function() { newTextBox(this,null); }));
	newCol.appendChild(createInput("button","btnDel"+instance,"","button","Remove Ingredient", function() { delTextBox(this.parentNode); }));
	newCol.insertAdjacentHTML('beforeEnd','<br/>Price of product: ');
	newCol.appendChild(createInput("text","price"+instance,"price"+instance,"number","","","Price..."));
	newCol.insertAdjacentHTML('beforeEnd','<br/>Product weight: ');
	newCol.appendChild(createInput("text","weight"+instance,"weight"+instance,"number","","","Weight..."));
	newCol.insertAdjacentHTML('beforeEnd','<br/>Label serving: ');
	newCol.appendChild(createInput("text","serving"+instance,"serving"+instance,"number","","","Weight..."));
	newCol.insertAdjacentHTML('beforeEnd','<br/>Your portion: ');
	newCol.appendChild(createInput("text","amount"+instance,"amount"+instance,"number","","","Weight..."));			
	var innerCalc = document.createElement("DIV");
	innerCalc.id = "calc"+instance;
	innerCalc.className = "innercalc";
	newCol.appendChild(innerCalc);
	instance++;
}

function delColumn(){
	var column = document.getElementById(instance-1);
	column.parentNode.removeChild(column);
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
	for(var i=0;i<instance;i++){
		var innerHtml = "";
		var innerComps = [];
		var price = document.getElementById("price"+i).value;
		var weight = document.getElementById("weight"+i).value;
		var serving = document.getElementById("serving"+i).value;
		var amount = document.getElementById("amount"+i).value;
		if(isNaN(price) || isNaN(weight) || isNaN(serving) || isNaN(amount))
			alert("Please fill in the details of your product and make sure weights and prices are numbers");
		else{
			var pricePerWeight = price / weight;
			var pricePerServing = pricePerWeight * serving;
			var pricePerAmount = pricePerWeight * amount;
			
			innerHtml += "Price per weight unit: $"+pricePerWeight.toFixed(2)+"<br/>";
			innerHtml += "Price per label serving: $"+pricePerServing.toFixed(2)+"<br/>";
			innerHtml += "Price for your portion: $"+pricePerAmount.toFixed(2)+"<br/>";
		
			for(var j=0;j<boxes[i];j++){
				var comp = document.getElementById("text"+i+j).value;
				var compWeight = document.getElementById("amount"+i+j).value;
				if(comp!="" && !isNaN(compWeight)){						
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
				else
					alert("Make sure fields are filled and prices and weights are numbers");
			}
		}
		document.getElementById("calc"+i).innerHTML = innerHtml;
	}
}

function totalsCheck(){
	var calc = document.getElementById("calc");
	calc.innerHTML = "";
	fillComponents();
	for(var i = 0; i<components.length; i++){									
		calc.insertAdjacentHTML ('beforeEnd', components[i]+": ");
		calc.appendChild(createInput("text","min"+i,"min"+i,"numberComp","","","Min..."));
		calc.appendChild(createInput("text","max"+i,"max"+i,"numberComp","","","Max..."));
		calc.insertAdjacentHTML ('beforeEnd', "<br/>");							
	}
	if(components.length>0)
		calc.appendChild(createInput("button","totalsCalc","","","Go!", function() { totalsCalc(); }));
	else
		alert("There are no components to sum!");
}
function fillComponents(){
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
		var price = document.getElementById("price"+i).value;
		var weight = document.getElementById("weight"+i).value;
		if(serving=="" || portion == "" || price == ""){
			alert("Please fill the details for all products.");
		}
		else	
			for(var j=0;j<boxes[i];j++){
				var comp = document.getElementById("text"+i+j).value;
				var weight = ((document.getElementById("amount"+i+j).value)/serving)*portion;
				var idx = components.contains(comp);
				if(weight!=""){
					if(idx===false && comp !=""){
						componentsWeight[k] = weight;
						components[k] = comp;
						k++;
					}else 
						componentsWeight[idx] += weight;
				}
			}
	}
}
function totalsCalc(){
	sumWeight();
	resetClass();
	for(var k = 0; k<components.length; k++){
		var min = document.getElementById("min"+k).value;
		var max = document.getElementById("max"+k).value;
		var wC = document.getElementById("wC"+k);
		if(wC)
			wC.parentNode.removeChild(wC);
		if( isNaN(componentsWeight[k]))
			alert("Make sure all your ingredients' weights are numbers");
		else if((componentsWeight[k]>max && max!="") || (componentsWeight[k]<min && min != ""))
			changeClassERROR(components[k]);	
		else
			document.getElementById("max"+k).insertAdjacentHTML ('afterEnd', " <span id='wC"+k+"'> Total: "+componentsWeight[k].toFixed(2)+"</span>");
					
	}
	var pp = document.getElementById("pp");
	if(pp)
		pp.parentNode.removeChild(pp);
	var pprice = portionPrice();
	if(!isNaN(pprice))
		document.getElementById("calc").insertAdjacentHTML('beforeEnd', "<span id='pp'><br/>Portion price: $"+pprice+"</span>");	
}
function portionPrice(){
	var portion_price = 0;
	for(var i=0;i<instance;i++){
		var price = document.getElementById("price"+i).value;
		var weight = document.getElementById("weight"+i).value;
		var amount = document.getElementById("amount"+i).value;
		if(price && weight && amount)
			portion_price += (price/weight)*amount;
	}
	return portion_price.toFixed(2);
}
function changeClassERROR(value){
	for(var i=0;i<instance;i++)
		for(var j=0;j<boxes[i];j++)
			if(document.getElementById("text"+i+j).value==value){
				document.getElementById("text"+i+j).className = "nameERROR";
				document.getElementById("amount"+i+j).className = "numberCompERROR";
				document.getElementById("amount"+i).className = "numberERROR";
			}
}
function resetClass(){
	for(var i=0;i<instance;i++)
		for(var j=0;j<boxes[i];j++){
			document.getElementById("text"+i+j).className = "name";
			document.getElementById("amount"+i+j).className = "numberComp";
			document.getElementById("amount"+i).className = "number";
		}
}