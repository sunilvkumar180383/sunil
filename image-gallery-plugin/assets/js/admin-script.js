jQuery(document).ready(function($) {
    
    // Initialize sortable
    $("#igm-sortable-list").sortable({
        update: function(event, ui) {
            saveImageOrder();
        }
    });
    
    // Upload button
    $("#igm-upload-btn").on("click", function(e) {
        e.preventDefault();
        
        var frame = wp.media({
            title: "Select Images",
            multiple: true,
            library: {
                type: "image"
            },
            button: {
                text: "Select"
            }
        });
        
        frame.on("select", function() {
            var selection = frame.state().get("selection");
            selection.each(function(attachment) {
                showUploadForm(attachment.id);
            });
        });
        
        frame.open();
    });
    
    // Show upload form
    function showUploadForm(attachmentId) {
        $("#igm-attachment-id").val(attachmentId);
        $("#igm-image-form").show();
        $("#igm-image-title").focus();
    }
    
    // Add image button
    $("#igm-add-image-btn").on("click", function(e) {
        e.preventDefault();
        
        var attachmentId = $("#igm-attachment-id").val();
        var title = $("#igm-image-title").val();
        var description = $("#igm-image-description").val();
        
        if (!attachmentId) {
            alert("Please select an image");
            return;
        }
        
        $.ajax({
            type: "POST",
            url: igmData.ajaxUrl,
            data: {
                action: "igm_add_image",
                nonce: igmData.nonce,
                attachment_id: attachmentId,
                title: title,
                description: description
            },
            success: function(response) {
                if (response.success) {
                    alert("Image added successfully");
                    location.reload();
                } else {
                    alert("Error: " + response.data);
                }
            },
            error: function() {
                alert("An error occurred");
            }
        });
    });
    
    // Delete image
    $(document).on("click", ".igm-delete-btn", function(e) {
        e.preventDefault();
        
        if (!confirm("Are you sure you want to delete this image?")) {
            return;
        }
        
        var imageId = $(this).data("image-id");
        var $item = $(this).closest(".igm-gallery-list-item");
        
        $.ajax({
            type: "POST",
            url: igmData.ajaxUrl,
            data: {
                action: "igm_delete_image",
                nonce: igmData.nonce,
                image_id: imageId
            },
            success: function(response) {
                if (response.success) {
                    $item.fadeOut(function() {
                        $(this).remove();
                    });
                } else {
                    alert("Error: " + response.data);
                }
            },
            error: function() {
                alert("An error occurred");
            }
        });
    });
    
    // Save image order
    function saveImageOrder() {
        var order = [];
        $("#igm-sortable-list li").each(function() {
            order.push($(this).data("image-id"));
        });
        
        $.ajax({
            type: "POST",
            url: igmData.ajaxUrl,
            data: {
                action: "igm_reorder_images",
                nonce: igmData.nonce,
                order: order
            },
            success: function(response) {
                if (!response.success) {
                    alert("Error saving order: " + response.data);
                }
            }
        });
    }
});