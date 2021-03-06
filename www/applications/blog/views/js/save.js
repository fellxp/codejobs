function addslashes(str) {
    str = str.replace(/\'/g,'\\\'');
    str = str.replace(/\"/g,'\\"');
    str = str.replace(/\\/g,'\\\\');
    str = str.replace(/\0/g,'\\0');
    
    return str;
}

function stripslashes(str) {
    str = str.replace(/\\'/g,'\'');
    str = str.replace(/\\"/g,'"');
    str = str.replace(/\\\\/g,'\\');
    str = str.replace(/\\0/g,'\0');
    
    return str;
}

function saveDraft() {		
	var title = $('#title').val();
	var tags = $('#tags').val();
	var code = $('#code').val();
	var buffer = $('#buffer').val();
	var pwd = $('#pwd').val();	
	var postID = $('#ID_Post').val();
	var language = $('#language').val();
	var situation = $('#situation').val();
	var temp_title = stripslashes($('#temp_title').val());
	var temp_tags = stripslashes($('#temp_tags').val());
	var content = $(".cke_wysiwyg_frame").contents().find('body').html();

	if(title.length > 5 && content.length > 30) {	
		$.ajax({
			type: 'POST',
			url:   PATH + '/blog/cpanel/draft',
			data: 'title=' + title + '&content=' + content + '&tags=' + tags + '&language=' + language + '&buffer=' + buffer + '&code=' + code + '&postID=' + postID + '&situation=' + situation,
			success: function(response) {
				$('#alert-message').show();
				$('#alert-message').removeClass('no-display');
				$('#alert-message').html(response);
				$('#alert-message').fadeOut('slow');
				$('#temp_title').val(addslashes(title));
				$('#temp_tags').val(addslashes(tags));
			}
		});
	}		
}

/*setInterval(function() {
    saveDraft();
}, 3 * 60 * 1000);*/