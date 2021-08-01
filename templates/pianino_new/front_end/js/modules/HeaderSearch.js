const HeaderSearch = {
	init: () => {
		const inputSearch = $('#search_input')
		const searchResult = $('#search_result')

		if (inputSearch) {
			inputSearch.on('keyup', function () {
				HeaderSearch.HELPERS.initSearch($(this), searchResult)
			})
		}

		if (searchResult) {
			inputSearch.on('focus', function () {
				inputSearch.addClass("is--focus")
				HeaderSearch.HELPERS.showSearchResult(searchResult)
			})

			inputSearch.on('blur', function () {
				inputSearch.removeClass("is--focus")
				if (!searchResult.hasClass('is--hover')) {
					HeaderSearch.HELPERS.hideSearchResult(searchResult)
				}
			})

			searchResult.hover(
				function () {
					searchResult.addClass('is--hover')
				},
				function () {
					searchResult.removeClass('is--hover')
					
					if ( !inputSearch.hasClass("is--focus") ) {
						HeaderSearch.HELPERS.hideSearchResult(searchResult)
					}
				}
			)
		}
	},

	HELPERS: {
		initSearch: (el, container) => {
			$.ajax({
				type: 'get',
				url: '/components/com_jshopping/finder.php',
				data: {
					word: el.val(),
				},

				beforeSend: function () {
					el.parents("form").append(Loader.html)
				},

				success: function (response) {
					if ( container ) {
						container.html(response)
					}
				},

				complete: function () {
					el.parents("form").find(".preloader-wrap").remove();
				},

				error: function (xhr) {
					alert('Error occured.please try again')
				},
			})
		},

		showSearchResult: el => {
			$(el).show()
		},

		hideSearchResult: el => {
			$(el).hide()
		},
	},
}

$(document).ready(HeaderSearch.init)
