jQuery(document).ready(function($) {
    
    // Responsive grid columns
    adjustGridColumns();
    
    $(window).on("resize", function() {
        adjustGridColumns();
    });
    
    function adjustGridColumns() {
        var width = $(window).width();
        var columns = $(".igm-gallery-wrapper").data("columns") || 3;
        
        if (width < 480) {
            columns = 1;
        } else if (width < 768) {
            columns = 2;
        }
        
        $(".igm-gallery-grid").css("grid-template-columns", "repeat(" + columns + ", 1fr)");
    }
    
    // Lazy load images
    if ("IntersectionObserver" in window) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var img = entry.target;
                    img.src = img.dataset.src || img.src;
                    observer.unobserve(img);
                }
            });
        });
        
        $(".igm-gallery-image").each(function() {
            observer.observe(this);
        });
    }
});