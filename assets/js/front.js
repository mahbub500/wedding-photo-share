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
	    // If gallery is visible, hide it
	    if ($('#image-gallery').is(':visible')) {
	        $('#image-gallery').hide().empty();
	    }

	    // Open file selector
	   $('<input type="file" multiple accept="image/*">').on('change', function(e) {
	        let files = e.target.files;
	        let formData = new FormData();
	        $.each(files, function(i, file) {
	            formData.append('file[]', file);
	        });
	        formData.append('action', 'image_upload');
	        formData.append('_wpnonce', WEADDING_PHOTO_SHARE._wpnonce);

	        $('#upload-progress').show();
	        wps_modal();
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
	                    // Toast message
	                    let toast = $('<div class="my-toast">Upload complete!</div>').appendTo('body');
	                    toast.css({
	                        position: 'fixed',
	                        bottom: '20px',
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
	                        $('#upload-progress').hide();
	                        $('#upload-progress progress').val(0);
	                        $('#upload-percent').text('0%');
	                    }, 2000);

	                    wps_modal( false );
	                }
	            }
	        });
	    }).click();
	});


	$('#show-images-btn').on('click', function () {
	    $.post(WEADDING_PHOTO_SHARE.ajaxurl, { action: 'get_images', _wpnonce: WEADDING_PHOTO_SHARE._wpnonce }, function (res) {
	        if (res.success) {
	            let images = res.data;


	            if (images.length > 10) {
    $('#image-container').empty();
    $('#pagination-container').empty();

    $('#pagination-container').pagination({
        dataSource: images,
        pageSize: 10,
        callback: function (data, pagination) {
            let html = '';
            data.forEach(function (img) {
                html += `
                    <div class="image-box">
                        <img src="${img}" alt="Uploaded Image">
                        <a href="${img}" download class="download-btn">Download</a>
                    </div>
                `;
            });
            $('#image-container').html(html);
        }
    });

} else {
    // If less than or equal to 10 images, just render directly without pagination
    let html = '';
    images.forEach(function (img) {
        html += `
            <div class="image-box">
                <img src="${img}" alt="Uploaded Image">
                <a href="${img}" download class="download-btn">Download</a>
            </div>
        `;
    });
    $('#image-container').html(html);
}


	            $('#image-gallery').show();
	        }
	    });
	});

function renderImages(images) {
    let html = '';
    images.forEach(function(img) {
        html += `
            <div class="image-box">
                <img src="${img}" alt="Uploaded Image">
                <a href="${img}" download class="download-btn">Download</a>
            </div>
        `;
    });
    $('#image-container').html(html); // keep the parent .image-grid intact
}

});

