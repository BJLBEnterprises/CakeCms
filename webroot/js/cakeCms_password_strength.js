$('input[type="password"').on('keyup', function(){
	var id = $(this).prop('id'), pw = $(this).val(), fl = pw.length,
			ll = pw.match(/[a-z]/g), ul = pw.match(/[A-Z]/g), n = pw.match(/[0-9]/g),
			sc = pw.match(/[\W|_]/g), c, score, cls, txt;

	ll = (ll !== null) ? ll : [];
	ul = (ul !== null) ? ul : [];
	n = (n !== null) ? n : [];
	sc = (sc !== null) ? sc : [];
	c = Number(ll.length>0) + Number(ul.length>0) + Number(n.length>0) + Number(sc.length>0);
	score = ((ll.length*26 + ul.length*26 + n.length*10 + sc.length*66)*c)*fl;

	switch (true){
		case String(score).length <= 3:
			cls = 'weak';
			txt = 'Weak';
			break;
		case String(score).length == 4:
			cls = 'fair';
			txt = 'Fair';
			break;
		case String(score).length >= 5:
			cls = 'strong';
			txt = 'Strong';
			break;
	}

	$('span#' + id + '-strength').remove();
	
	if (fl > 0) {
		$(this).after('<span id="'+ id +'-strength" class="pw-strength '+ cls +'">'+ txt +'</span>');
	}
});