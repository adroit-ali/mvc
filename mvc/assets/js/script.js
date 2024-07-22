jQuery(document).ready(function($) {
	// $(document).on('click', '#edit_btn', function() {
    //     $.ajax({
    //         type: 'POST',
    //         url: 'https://ali.axonteam.pk/test01/?action=edit',
    //         data: {
    //             id: '3'
    //         },
    //         success: function(response) {
    //             console.log(response);
    //         }
    //     });
	// });
    $(document).on('click', '#edit_btn', function() {
        var currentUrl = window.location.href;
        var currentUrl = new URL(currentUrl);
        var params = new URLSearchParams(currentUrl.search);
        params.set('action', 'edit');
        var url = currentUrl.origin + currentUrl.pathname + '?' + params.toString();
        // var url = 'https://ali.axonteam.pk/test01/?action=edit';
        var params = {
            id: $(this).data("id")
        };
    
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(params)
        }).then(response => response.text())
          .then(data => {
            //! new window opens
            var newWindow = window.open();
            newWindow.document.write(data);
            newWindow.history.pushState(null, '', url);
            //! same window changes
            // document.open();
            // document.write(data);
            // document.close();
            // window.history.pushState(null, '', url);
          }).catch(error => {
              console.error('Error:', error);
          });
    });
    
	$(document).on('submit', '#edit_my_custom_table_form_id', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
		console.log(formData);
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'edit_button_ajax',
                data: formData
            },
            success: function(response) {
                console.log(response);
            }
        });
	});
	$(document).on('submit', '#add_my_custom_table_form_id', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
		console.log(formData);
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'insert_button_ajax',
                data: formData
            },
            success: function(response) {
                console.log(response);
            }
        });
	});
	$(document).on('click', '#delete_btn', function() {
		console.log($(this).data("id"));
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'delete_button_ajax',
                id: $(this).data("id")
            },
            success: function(response) {
                console.log(response);
            }
        });
	});
});