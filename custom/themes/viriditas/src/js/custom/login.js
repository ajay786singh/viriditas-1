jQuery(document).ready(function($) {

    // Show the login dialog box on click 
    $('a#show_login').on('click', function(e){
        $('.login-box').toggle();
        e.preventDefault();
    });

    // Perform AJAX login on form submit
    $('form#login-form').on('submit', function(e){
        $('form#login-form .status').show().text('Sending info, please wait...');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajaxurl,
            data: { 
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#login-form #username').val(), 
                'password': $('form#login-form #password').val(),
				'security': $('form#login-form #security').val() 
			},
            success: function(data){
                $('form#login-form .status').text(data.message);
                if (data.loggedin == true){
					document.location.href = AfterLoginRedirect;
                }
            }
        });
        e.preventDefault();
    });

});