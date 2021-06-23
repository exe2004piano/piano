jQuery.noConflict();
jQuery(document).ready(function($){
	//get recent tags
	var tagName 		= $('.jgram').attr('data-tag');
	var accessToken 	= $('.jgram').attr('data-accessToken');
	var userID 		 	= $('.jgram').attr('data-userID');
	var limit 			= parseInt($('.jgram').attr('data-limit'));

	//stop link redirect
	$('body').on('click touchstart', '.jgram a', function(e){
		e.preventDefault();
	});

	//populate jgram overlay
	function jgramOverlay(){
		$('body').on('click touchstart', '.jgram .jgram-img', function (){
			$('body').addClass('jgram-body-active');
        	$('.jgram-overlay').fadeIn('fast');
        	$(this).addClass('jgram-active');
        	$(this).clone().appendTo('.jgram-overlay');
    	});
	}
	jgramOverlay();

	function jgramNext(){
		$('body').on('click touchstart', '#jgram-next', function(){
			$('.jgram-overlay .jgram-img').remove();
			$('.jgram .jgram-active').next().addClass('jgram-active');
			$('.jgram .jgram-active').prev().removeClass('jgram-active');
			$('.jgram .jgram-active').clone().appendTo('.jgram-overlay');
		});
	}
	jgramNext();

	function jgramPrev(){
		$('body').on('click touchstart', '#jgram-prev', function(){
			$('.jgram-overlay .jgram-img').remove();
			$('.jgram .jgram-active').prev().addClass('jgram-active');
			$('.jgram .jgram-active').next().removeClass('jgram-active');
			$('.jgram .jgram-active').clone().appendTo('.jgram-overlay');
		});
	}
	jgramPrev();

	function jgramClose(){
		$('#jgram-close').on('click', function(){
				$('.jgram div').removeClass('jgram-active');
        		$('body').removeClass('jgram-body-active');
        		$('.jgram-overlay').fadeOut(function(){
        		$('.jgram-overlay .jgram-img').remove();
        	});
        });
	}
	jgramClose();

	if($('div').hasClass('tagged')){

		//get tagged feed
		$.ajax({
				type: "GET",
				dataType: "jsonp",
				url: "https://api.instagram.com/v1/tags/"+tagName+"/media/recent/?access_token="+accessToken,
				success: function(data) {
					for (var i = 0; i < limit; i++) {
						if(data.data[i].type === "image"){
							$(".tagged-feed").append("<div class='jgram-img'><a href='"+data.data[i].link+"' target='_blank'><img src=" + data.data[i].images.standard_resolution.url+ " /></a></div>");
						}

						if(data.data[i].type === "video"){
							$(".tagged-feed").append("<div class='jgram-img'><a href='"+data.data[i].link+"' target='_blank'><video controls><source src=" + data.data[i].videos.standard_resolution.url+ " ></video></a></div>");
						}
					}
				}
		});
	}

	if($('div').hasClass('user')){

		//get user data
		$.ajax({
		  type: "GET",
		  dataType: "jsonp",
		  url: "https://api.instagram.com/v1/users/"+userID+"/?access_token="+accessToken,
		  success: function(data) {
		    $('.user-info .username').text(data.data.username);
			$('.user-info .fullname').text(data.data.full_name);
		    $('.user-info .tagline').text(data.data.bio);
			$('.profile-pic img').attr('src', data.data.profile_picture);
			$('.user-info .website').text(data.data.website);
			$('.user-info .website').attr('href', data.data.website);
			$('.user-info .posts').html('<strong>' + data.data.counts.media + '</strong>' + ' Posts');
			$('.user-info .followers').html('<strong>' + data.data.counts.followed_by + '</strong>'  + ' Followers');
			$('.user-info .following').html('<strong>' + data.data.counts.follows + '</strong>'  + ' Following');
		  }
		});

		//get user feed
		$.ajax({
		  type: "GET",
		  dataType: "jsonp",
		  url: "https://api.instagram.com/v1/users/"+userID+"/media/recent/?access_token="+accessToken,
		  	success: function(data) {
		    	for (var i = 0; i < limit; i++) {

		    		if(data.data[i].type === "image"){
						$(".jgram .user-feed").append("<div class='jgram-img'>" +
							"<a href='"+data.data[i].link+"' target='_blank'><img src=" + data.data[i].images.standard_resolution.url + " /></a><div>");
					}

					if(data.data[i].type === "video"){
						$(".jgram .user-feed").append("<div class='jgram-img'>" +
							"<a href='"+data.data[i].link+"' target='_blank'><video controls><source src=" + data.data[i].videos.standard_resolution.url + " ></video></a><div>");
					}
				}
		  	}
		});
	}

	if($('div').hasClass('search')){
		var search = $('#jgram-search input');

		$('#jgram-search #jgram-submit').on('click touchstart', function(e){
			e.preventDefault();
			$(".search-feed .jgram-img").remove();

			//get tagged feed
			$.ajax({
					type: "GET",
					dataType: "jsonp",
					url: "https://api.instagram.com/v1/tags/"+search.val()+"/media/recent/?access_token="+accessToken,
					success: function(data) {
						for (var i = 0; i < limit; i++) {

							if(data.data[i].type === "image"){
								$(".search-feed").append("<div class='jgram-img'><a href='"+data.data[i].link+"' target='_blank'><img src=" + data.data[i].images.standard_resolution.url+ " /></a></div>");
							}

							if(data.data[i].type === "video"){
								$(".search-feed").append("<div class='jgram-img'><a href='"+data.data[i].link+"' target='_blank'><video controls><source src=" + data.data[i].videos.standard_resolution.url+ " ></a></video>");
							}

						}

					}
			});
		});
	}

});
