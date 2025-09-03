// Simplified Typeform Embed Implementation using data attributes
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
    
    // Initialize Typeform embeds using data attributes
    function initTypeformEmbed() {
        $('.typeform-embed-container').each(function() {
            const container = $(this);
            const typeformId = parseTypeformId(container.data('typeform-id'));
            
            if (!typeformId) {
                console.error('Typeform ID not found');
                return;
            }
            
            const embedType = container.data('embed-type');
            const opacity = parseInt(container.data('opacity')) || 100;
            const hideHeaders = container.data('hide-headers') === 'true';
            const hideFooter = container.data('hide-footer') === 'true';
            const disableTracking = container.data('disable-tracking') === 'true';
            
            switch(embedType) {
                case 'widget':
                    initInlineWidget(container, typeformId, {
                        opacity, hideHeaders, hideFooter, disableTracking
                    });
                    break;
                case 'popup':
                case 'slider':
                case 'popover':
                    initButtonEmbed(container, typeformId, embedType, {
                        opacity, hideHeaders, hideFooter, disableTracking
                    });
                    break;
                case 'sidetab':
                    initSidetab(container, typeformId, {
                        opacity, hideHeaders, hideFooter, disableTracking
                    });
                    break;
            }
        });
    }
    
    // Initialize inline widget using data attributes
    function initInlineWidget(container, typeformId, options) {
        const widgetElement = container.find('.typeform-embed-widget');
        
        if (widgetElement.length && !widgetElement.attr('data-tf-widget')) {
            // Set data attributes for Typeform SDK
            widgetElement.attr({
                'data-tf-widget': typeformId,
                'data-tf-opacity': options.opacity,
                'data-tf-hide-headers': options.hideHeaders,
                'data-tf-hide-footer': options.hideFooter,
                'data-tf-disable-tracking': options.disableTracking,
                'data-tf-iframe-props': 'title="Typeform"',
                'data-tf-medium': 'snippet'
            });
            
            // Reload Typeform embeds
            if (window.tf && window.tf.load) {
                window.tf.load();
            }
        }
    }
    
    // Initialize button-triggered embeds
    function initButtonEmbed(container, typeformId, type, options) {
        const button = container.find('.typeform-embed-button');
        
        if (button.length && !button.attr('data-tf-' + type)) {
            // Set data attributes for Typeform SDK
            const attrName = 'data-tf-' + type;
            const attrs = {
                [attrName]: typeformId,
                'data-tf-opacity': options.opacity,
                'data-tf-hide-headers': options.hideHeaders,
                'data-tf-hide-footer': options.hideFooter,
                'data-tf-disable-tracking': options.disableTracking,
                'data-tf-iframe-props': 'title="Typeform"',
                'data-tf-medium': 'snippet'
            };
            
            // Add close on ESC option
            if (container.data('close-on-escape') === 'true') {
                attrs['data-tf-disable-close'] = 'false';
            }
            
            // Add auto close options
            if (container.data('auto-close') === 'true') {
                attrs['data-tf-auto-close'] = container.data('auto-close-delay') || 3000;
            }
            
            // Add keep session option - this keeps the form progress when reopening
            if (container.data('keep-session') === 'true') {
                attrs['data-tf-keep-session'] = 'true';
                // Also set a unique session ID based on the form ID and page
                const sessionId = 'tf-session-' + typeformId + '-' + window.location.pathname.replace(/\//g, '-');
                attrs['data-tf-session-id'] = sessionId;
            }
            
            // Enable click on overlay to close (this is default for slider)
            if (type === 'slider') {
                attrs['data-tf-on-close'] = 'close-slider';
            }
            
            button.attr(attrs);
            
            // Reload Typeform embeds
            if (window.tf && window.tf.load) {
                window.tf.load();
            }
            
            // Add custom close handling for better UX
            if (type === 'slider' || type === 'popup') {
                button.on('click', function() {
                    setTimeout(function() {
                        // Add event listener for overlay clicks
                        const overlay = document.querySelector('.tf-v1-' + type);
                        if (overlay) {
                            overlay.addEventListener('click', function(e) {
                                // Check if click was on the overlay (not the form itself)
                                if (e.target === overlay || e.target.classList.contains('tf-v1-' + type + '-wrapper')) {
                                    // Close the typeform
                                    if (window.tf && window.tf.closeAll) {
                                        window.tf.closeAll();
                                    }
                                }
                            });
                            
                            // Position close button correctly for slider
                            if (type === 'slider') {
                                // Simple positioning - just set right: 20px
                                const positionCloseButton = () => {
                                    const closeButton = document.querySelector('.tf-v1-slider .tf-v1-close, .tf-v1-slider button.tf-v1-close');
                                    
                                    if (closeButton) {
                                        // Simple fixed positioning
                                        closeButton.style.position = 'fixed';
                                        closeButton.style.top = '20px';
                                        closeButton.style.right = '20px';
                                        closeButton.style.left = 'auto';
                                        closeButton.style.zIndex = '999999';
                                    }
                                };
                                
                                // Try positioning after short delay for DOM to be ready
                                setTimeout(positionCloseButton, 100);
                                setTimeout(positionCloseButton, 500);
                            }
                        }
                    }, 500);
                });
                
                // Store reference to the form for session management
                if (container.data('keep-session') === 'true') {
                    // The Typeform SDK automatically handles session persistence
                    // when data-tf-keep-session is set
                    button.attr('data-tf-remember-user', 'true');
                    
                    // Handle reset on submit if enabled
                    if (container.data('reset-on-submit') === 'true') {
                        // Listen for form submission
                        window.addEventListener('message', function(event) {
                            if (event.data && event.data.type === 'form-submit') {
                                // Clear the session storage for this form
                                const sessionId = 'tf-session-' + typeformId + '-' + window.location.pathname.replace(/\//g, '-');
                                if (window.localStorage) {
                                    window.localStorage.removeItem(sessionId);
                                }
                            }
                        });
                    }
                }
            }
        }
    }
    
    // Initialize sidetab
    function initSidetab(container, typeformId, options) {
        const buttonText = container.find('.typeform-embed-sidetab').data('button-text') || 'Feedback';
        
        // Create a hidden div with sidetab attributes
        if (!$('#typeform-sidetab-' + typeformId).length) {
            $('<div>')
                .attr({
                    'id': 'typeform-sidetab-' + typeformId,
                    'data-tf-sidetab': typeformId,
                    'data-tf-opacity': options.opacity,
                    'data-tf-hide-headers': options.hideHeaders,
                    'data-tf-hide-footer': options.hideFooter,
                    'data-tf-disable-tracking': options.disableTracking,
                    'data-tf-button-text': buttonText,
                    'data-tf-iframe-props': 'title="Typeform"',
                    'data-tf-medium': 'snippet'
                })
                .appendTo('body');
            
            // Reload Typeform embeds
            if (window.tf && window.tf.load) {
                window.tf.load();
            }
        }
    }
    
    // Wait for Typeform SDK to load
    function waitForTypeformSDK() {
        if (window.tf && window.tf.load) {
            initTypeformEmbed();
        } else {
            setTimeout(waitForTypeformSDK, 100);
        }
    }
    
    // Initialize on document ready
    $(document).ready(function() {
        waitForTypeformSDK();
    });
    
    // Reinitialize for Elementor editor
    $(window).on('elementor/frontend/init', function() {
        if (window.elementorFrontend) {
            elementorFrontend.hooks.addAction('frontend/element_ready/typeform-embed.default', function($scope) {
                waitForTypeformSDK();
            });
        }
    });
    
})(jQuery);