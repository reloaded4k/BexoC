document.addEventListener('DOMContentLoaded', function() {
    // Get tracking form and result container
    const trackingForm = document.getElementById('tracking-form');
    const trackingResultContainer = document.getElementById('tracking-result');
    
    // Handle form submission
    if (trackingForm) {
        trackingForm.addEventListener('submit', function(e) {
            // Show loading indicator
            if (trackingResultContainer) {
                trackingResultContainer.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Searching for shipment...</p></div>';
            }
        });
    }
    
    // Initialize timeline animation
    const timelineItems = document.querySelectorAll('.timeline-item');
    if (timelineItems.length > 0) {
        // Add animation classes to timeline items
        timelineItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            // Stagger the animation
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 100 * index);
        });
    }
    
    // Track using URL parameters if present
    const urlParams = new URLSearchParams(window.location.search);
    const trackingNumber = urlParams.get('tracking_number');
    
    if (trackingNumber && trackingForm) {
        const trackingInput = trackingForm.querySelector('#tracking_number');
        if (trackingInput) {
            trackingInput.value = trackingNumber;
            trackingForm.dispatchEvent(new Event('submit'));
        }
    }
});
