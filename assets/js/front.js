let wps_modal = ( show = true ) => {
	if(show) {
		jQuery('#weadding-photo-share-modal').show();
	}
	else {
		jQuery('#weadding-photo-share-modal').hide();
	}
}

jQuery(function($) {
    $('#upload-btn').on('click', function() {
        $('<input type="file" multiple>').on('change', function(e) {
            let files = e.target.files;
            let formData = new FormData();
            $.each(files, function(i, file) {
                formData.append('file[]', file);
            });
            formData.append('action', 'image_upload');

            $('#upload-progress').show();
            $.ajax({
		    url: WEADDING_PHOTO_SHARE.ajaxurl,
		    type: 'POST',
		    data: formData,
		    processData: false,
		    contentType: false,
		    xhr: function() {
		        let xhr = new window.XMLHttpRequest();
		        xhr.upload.addEventListener("progress", function(evt) {
		            if (evt.lengthComputable) {
		                let percent = Math.round((evt.loaded / evt.total) * 100);
		                $('#upload-progress progress').val(percent);
		                $('#upload-percent').text(percent + '%');
		            }
		        }, false);
		        return xhr;
		    },
		    success: function(res) {
		        if (res.success) {
		            // Show toast
		            let toast = $('<div class="my-toast">Upload complete!</div>').appendTo('body');
		            toast.css({
		                position: 'fixed',
		                top: '40px',
		                right: '20px',
		                background: '#4caf50',
		                color: '#fff',
		                padding: '10px 15px',
		                borderRadius: '5px',
		                zIndex: 9999,
		                fontSize: '14px',
		                boxShadow: '0 2px 6px rgba(0,0,0,0.2)'
		            });

		            setTimeout(function() {
		                toast.fadeOut(400, function() {
		                    $(this).remove();
		                });
		                // Hide progress bar
		                $('#upload-progress').hide();
		                $('#upload-progress progress').val(0);
		                $('#upload-percent').text('0%');
		            }, 2000); // 2 seconds
		        }
		    }
		});
        }).click();
    });

    $('#show-images-btn').on('click', function() {
        $.post(WEADDING_PHOTO_SHARE.ajaxurl, { action: 'get_images' }, function(res) {
            if (res.success) {
                let html = '';
                res.data.forEach(function(img) {
                    html += `<div style="display:inline-block;margin:5px;">
                                <a href="${img}" download>
                                    <img src="${img}" style="width:100px;height:auto;">
                                </a>
                             </div>`;
                });
                $('#image-gallery').html(html).show();
            }
        });
    });
});
