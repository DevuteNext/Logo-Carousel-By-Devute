jQuery(document).ready(function($) {
    // Media uploader
    var mediaUploader;
    
    $('#add_slider_images').on('click', function(e) {
        e.preventDefault();

        // If uploader exists, reopen
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        // Create media uploader
        mediaUploader = wp.media({
            title: 'Select Images for Slider',
            button: {
                text: 'Add to Slider'
            },
            multiple: true
        });

        // When images selected
        mediaUploader.on('select', function() {
            var attachments = mediaUploader.state().get('selection').toJSON();
            var html = '';
            
            attachments.forEach(function(attachment) {
                html += `
                    <div class="image-item" data-id="${attachment.id}">
                        <div class="image-preview">
                            <img src="${attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url}">
                        </div>
                        <div class="image-info">
                            <input type="text" 
                                   name="image_alt[${attachment.id}]" 
                                   value="${attachment.alt || ''}" 
                                   placeholder="Enter Alt Text"
                                   class="widefat">
                            <button type="button" class="button remove-image">Remove</button>
                            <input type="hidden" name="image_ids[]" value="${attachment.id}">
                        </div>
                    </div>
                `;
            });
            
            $('#logo_images_container').append(html);
        });

        mediaUploader.open();
    });

    // Remove image
    $('#logo_images_container').on('click', '.remove-image', function() {
        $(this).closest('.image-item').fadeOut(300, function() {
            $(this).remove();
        });
    });

    // Make images sortable
    $('#logo_images_container').sortable({
        items: '.image-item',
        cursor: 'move',
        opacity: 0.6,
        handle: '.image-preview',
        placeholder: 'image-item ui-sortable-placeholder'
    });
});