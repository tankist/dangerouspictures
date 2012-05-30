$(function(){
	var loginForm = $('#login'),
        inputs = loginForm.find('input[name=username], input[name=password]'),
		button = loginForm.find('button');
		
	inputs.each(function(){
		
	});
	
	inputs.focus(function(){
		$(this).addClass('active');
		if (this.value == $(this).attr('data-placeholder')) {
            this.value = '';
        }
	});
	
	inputs.blur(function(){
		$(this).removeClass('active');
		if (this.value == '') {
            this.value = $(this).attr('data-placeholder');
        }
	});
	
	button.mouseover(function(){
		$(this).addClass('active');
	});
	
	button.mouseout(function(){
		$(this).removeClass('active');
	});
	
	$('#login input').each(function(){
        if (this.value == '') {
            this.value = $(this).attr('data-placeholder');
        }
    });
})