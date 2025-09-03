// Main JavaScript for Typeform Embed Widget
(function($) {
    'use strict';
    
    // Helper function to parse Typeform ID from URL or ID
    function parseTypeformId(input) {
        if (!input) return null;
        
        // If it's a URL, extract the form ID
        if (input.includes('typeform.com')) {
            const matches = input.match(/\/to\/([a-zA-Z0-9]+)/);
            return matches ? matches[1] : input;
        }
        
        // Otherwise assume it's already an ID
        return input;
    }
    
    // Helper function to create embed options
    function createEmbedOptions(container) {
        const options = {
            opacity: parseInt(container.data('opacity')) || 100,
            hideHeaders: container.data('hide-headers') === 'true',
            hideFooter: container.data('hide-footer') === 'true',
            disableTracking: container.data('disable-tracking') === 'true',
            // Add iframe sandbox attributes to avoid permission issues
            iframeProps: {
                allow: 'camera; microphone; fullscreen; geolocation',
            },
            // Enable scrolling
            disableScroll: false,
        };
        
        // Add widget-specific options
        if (container.data('embed-type') === 'widget') {
            options.lazy = container.data('lazy') === 'true';
            
            if (container.data('auto-resize') === 'true') {
                options.autoResize = true;
            }
        }
        
        // Add dataLayer to hidden fields if enabled
        if (container.data('enable-datalayer') === 'true') {
            const fieldName = container.data('datalayer-field-name') || 'datalayer';
            
            // Check if window.dataLayer exists and is an array
            if (window.dataLayer && Array.isArray(window.dataLayer)) {
                // Initialize hidden fields object if it doesn't exist
                options.hidden = options.hidden || {};
                
                // Convert dataLayer array to JSON string
                try {
                    options.hidden[fieldName] = JSON.stringify(window.dataLayer);
                    console.log('DataLayer sent to Typeform hidden field:', fieldName);
                } catch (error) {
                    console.error('Error serializing dataLayer:', error);
                }
            } else {
                console.warn('window.dataLayer not found or is not an array');
            }
        }
        
        return options;
    }
    
    // Initialize Typeform embeds
    function initTypeformEmbed() {
        $('.typeform-embed-container').each(function() {
            const container = $(this);
            const typeformId = parseTypeformId(container.data('typeform-id'));
            
            if (!typeformId || !window.TypeformEmbed) {
                console.error('Typeform ID not found or Typeform Embed SDK not loaded');
                return;
            }
            
            const embedType = container.data('embed-type');
            const options = createEmbedOptions(container);
            
            switch(embedType) {
                case 'widget':
                    initWidget(container, typeformId, options);
                    break;
                case 'popup':
                    initPopup(container, typeformId, options);
                    break;
                case 'slider':
                    initSlider(container, typeformId, options);
                    break;
                case 'popover':
                    initPopover(container, typeformId, options);
                    break;
                case 'sidetab':
                    initSidetab(container, typeformId, options);
                    break;
            }
        });
    }
    
    // Initialize widget embed
    function initWidget(container, typeformId, options) {
        const widgetElement = container.find('.typeform-embed-widget')[0];
        if (widgetElement && !widgetElement.hasAttribute('data-tf-loaded')) {
            // Ensure container has proper height
            const computedHeight = window.getComputedStyle(widgetElement).height;
            if (computedHeight === '0px' || computedHeight === 'auto') {
                widgetElement.style.height = '500px';
            }
            
            const widget = window.TypeformEmbed.createWidget(typeformId, {
                container: widgetElement,
                ...options,
                onReady: () => {
                    // Remove loading state
                    widgetElement.classList.remove('tf-loading');
                },
                onSubmit: () => {
                    console.log('Typeform submitted');
                }
            });
            
            widgetElement.setAttribute('data-tf-loaded', 'true');
        }
    }
    
    // Initialize popup embed
    function initPopup(container, typeformId, options) {
        const button = container.find('.typeform-embed-button');
        
        if (button.length && !button.data('tf-initialized')) {
            const popup = window.TypeformEmbed.createPopup(typeformId, options);
            
            button.on('click', function(e) {
                e.preventDefault();
                popup.open();
            });
            
            button.data('tf-initialized', true);
        }
    }
    
    // Initialize slider embed
    function initSlider(container, typeformId, options) {
        const button = container.find('.typeform-embed-button');
        
        if (button.length && !button.data('tf-initialized')) {
            const slider = window.TypeformEmbed.createSlider(typeformId, options);
            
            button.on('click', function(e) {
                e.preventDefault();
                slider.open();
            });
            
            button.data('tf-initialized', true);
        }
    }
    
    // Initialize popover embed
    function initPopover(container, typeformId, options) {
        const button = container.find('.typeform-embed-button')[0];
        
        if (button && !button.hasAttribute('data-tf-initialized')) {
            options.container = button;
            options.autoClose = 3000; // Auto close after 3 seconds of inactivity
            
            window.TypeformEmbed.createPopover(typeformId, options);
            button.setAttribute('data-tf-initialized', 'true');
        }
    }
    
    // Initialize sidetab embed
    function initSidetab(container, typeformId, options) {
        const sidetabElement = container.find('.typeform-embed-sidetab')[0];
        
        if (sidetabElement && !sidetabElement.hasAttribute('data-tf-initialized')) {
            const buttonText = container.find('.typeform-embed-sidetab').data('button-text') || 'Feedback';
            
            options.buttonText = buttonText;
            
            window.TypeformEmbed.createSidetab(typeformId, options);
            sidetabElement.setAttribute('data-tf-initialized', 'true');
        }
    }
    
    // Initialize on document ready
    $(document).ready(function() {
        // Wait a bit for Typeform SDK to load
        setTimeout(initTypeformEmbed, 100);
    });
    
    // Reinitialize for Elementor editor
    $(window).on('elementor/frontend/init', function() {
        if (window.elementorFrontend) {
            elementorFrontend.hooks.addAction('frontend/element_ready/typeform-embed.default', function($scope) {
                setTimeout(function() {
                    initTypeformEmbed();
                }, 100);
            });
        }
    });
    
})(jQuery);