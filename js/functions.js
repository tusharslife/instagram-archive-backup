function change() {
	if($('.select-all').hasClass("select-all")) {
		$('.select-all').addClass('unselect-all');
		$('.select-all').removeClass('select-all');
		$('.check-box').each(function() {
			this.checked=true;
		});
	}
	else {
		$('.unselect-all').addClass('select-all');
		$('.unselect-all').removeClass('unselect-all');
		$('.check-box').each(function() {
			this.checked=false;
		});
	}
}

function getData() {
	var loaded = false;
	out = [];
	var posData = '';
	$('.check-box').each(function() {
		if($(this).is(":checked")) {
			out.push(($(this).attr("id")).substring(2));
			if(loaded == false) {
				loaded = true;
				$("#loading").attr("src", "js/loading.gif");
				$('body').css('background-color', 'rgba(73, 114, 147, 0.5)');
				$('#loading').css('display', 'inline');
				$('.dummy').addClass('fadeIn');
			}
		}
	});
	$.ajax({        
       type: "POST",
       url: "classes/ajaxHandler.php",
       data: {out: out},
       success: (function() {
       		$("#loading").removeAttr("src");
       		$('body').css('background-color', 'rgba(73, 114, 147, 1)');
			$('#loading').css('display', 'none');
			$('.dummy').removeClass('fadeIn');
       		if(out.length == 0) alert("No media selected!");
       		else $('#trigger-download').get(0).click();
       })
   });
}