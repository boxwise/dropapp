function cms_form_lowercase(field) {
	$('#field_'+field).val($('#field_'+field).val().toLowerCase()); 
}

function cms_form_uppercase(field) {
	$('#field_'+field).val($('#field_'+field).val().toUpperCase()); 
}

function cms_form_valuta(field) {
	value = $('#field_'+field).val();
	if(value.substr(0,2)=='€ ') value = value.replace(/\./g,'');
	if(value.substr(0,2)=='€ ') value = value.substr(1);
	if(value.substr(0,1)==' ') value = value.substr(1);
	value = parseFloat(value.replace(/,/g,'.')).toFixed(2).replace(/\./g,',');
	if(value=='NaN') value = '0,00';
	$('#field_'+field).val('€ '+PointPerThousand(value));
}

function cms_form_float(field) {
	value = $('#field_'+field).val();
	
	if(value.substr(0,2)=='€ ') value = value.replace(/\./g,'');
	value = parseFloat(value.replace(/,/g,'.')).toString();
	value = value.replace(/\./g,',');
	if(value=='NaN') value = '0';
	$('#field_'+field).val(PointPerThousand(value));
}

function cms_form_int(field) {
	value = $('#field_'+field).val();
	
	value = parseInt(value).toString();

	$('#field_'+field).val(value);
}

function cms_form_percentage(field) {
	value = $('#field_'+field).val();
	
	value = parseFloat(value.replace(/,/g,'.')).toString();
	value = value.replace(/\./g,',');
	if(value=='NaN') value = '0';
	
	$('#field_'+field).val(value+" %");
}

function cms_form_url(field) {
	value = $('#field_'+field).val();
	
	if(value=='') return;
	
	if(value.substr(0,4)!='http') value = 'http://'+value;
	
	$('#field_'+field).val(value);
}

function PointPerThousand(amount, test)
{
	amountfloat = parseFloat(value.replace(/,/g,'.')).toString();
	var delimiter = "."; // replace comma if desired
	var a = amount.split(',',2)
	if(a.length>1) {
		var d = a[1];
		var i = parseInt(a[0]);
	} else {
		var d = 0;
		var i = parseInt(a);
	}
	if(isNaN(i)) { return ''; }
	var minus = '';
	if(amountfloat<0) { minus = '-'; }
	i = Math.abs(i);
	var n = new String(i);
	var a = [];
	while(n.length > 3)
	{
		var nn = n.substr(n.length-3);
		a.unshift(nn);
		n = n.substr(0,n.length-3);
	}
	if(n.length > 0) { a.unshift(n); }
	n = a.join(".");
	if(d.length < 1) { amount = n; }
	else { amount = n + ',' + d; }
	amount = minus + amount;
	return amount;
}
