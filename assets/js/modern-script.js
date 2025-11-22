// Modern JavaScript for Nomad Visa Hub Plugin Frontend

class NomadVisaFrontend {
    constructor() {
        this.init();
    }

    init() {
        this.initChecklistToggle();
        this.initFAQToggle();
        this.initCountryCards();
        this.initSmoothScrolling();
        this.initAnimations();
    }

    // Initialize checklist checkbox functionality
    initChecklistToggle() {
        const checkboxes = document.querySelectorAll('.checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('click', (e) => {
                e.preventDefault();
                checkbox.classList.toggle('checked');
                
                // Save state to localStorage
                const checklistItem = checkbox.closest('.checklist-item');
                const itemId = checklistItem.dataset.itemId || 'unknown';
                const isChecked = checkbox.classList.contains('checked');
                localStorage.setItem(`checklist-${itemId}`, isChecked);
                
                // Update progress if progress element exists
                this.updateChecklistProgress();
            });

            // Load saved state
            const checklistItem = checkbox.closest('.checklist-item');
            const itemId = checklistItem.dataset.itemId || 'unknown';
            const savedState = localStorage.getItem(`checklist-${itemId}`);
            
            if (savedState === 'true') {
                checkbox.classList.add('checked');
            }
        });

        this.updateChecklistProgress();
    }

    // Update checklist progress indicator
    updateChecklistProgress() {
        const checkboxes = document.querySelectorAll('.checkbox');
        const progressElements = document.querySelectorAll('.checklist-progress');
        
        if (checkboxes.length === 0 || progressElements.length === 0) return;

        const checked = document.querySelectorAll('.checkbox.checked').length;
        const total = checkboxes.length;
        const percentage = Math.round((checked / total) * 100);

        progressElements.forEach(el => {
            el.textContent = `${checked}/${total} (${percentage}%)`;
            const bar = el.parentElement.querySelector('.progress-bar');
            if (bar) {
                bar.style.width = `${percentage}%`;
            }
        });
    }

    // Initialize FAQ toggle functionality
    initFAQToggle() {
        const faqQuestions = document.querySelectorAll('.faq-question');
        
        faqQuestions.forEach(question => {
            question.addEventListener('click', () => {
                const faqItem = question.closest('.faq-item');
                const answer = faqItem.querySelector('.faq-answer');
                const toggle = question.querySelector('.faq-toggle');
                
                // Close other FAQs
                faqQuestions.forEach(otherQuestion => {
                    if (otherQuestion !== question) {
                        const otherItem = otherQuestion.closest('.faq-item');
                        const otherAnswer = otherItem.querySelector('.faq-answer');
                        const otherToggle = otherQuestion.querySelector('.faq-toggle');
                        
                        otherAnswer.classList.remove('active');
                        otherToggle.classList.remove('active');
                    }
                });
                
                // Toggle current FAQ
                answer.classList.toggle('active');
                toggle.classList.toggle('active');
            });
        });
    }

    // Initialize country card interactions
    initCountryCards() {
        const countryCards = document.querySelectorAll('.nvb-country-card');
        
        countryCards.forEach(card => {
            // Add hover effect enhancement
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
            });

            // Card click tracking
            card.addEventListener('click', (e) => {
                if (!e.target.closest('.btn')) {
                    const countryName = card.querySelector('h3').textContent;
                    console.log('Country card clicked:', countryName);
                }
            });
        });
    }

    // Initialize smooth scrolling for anchor links
    initSmoothScrolling() {
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        
        anchorLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                const targetId = link.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    // Initialize scroll animations
    initAnimations() {
        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe elements for animation
        const animatedElements = document.querySelectorAll(`
            .nvb-country-card,
            .visa-program-card,
            .nvb-step,
            .faq-item,
            .detail-section
        `);

        animatedElements.forEach(el => observer.observe(el));
    }

    // Utility method to format currency
    static formatCurrency(amount, currency) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency || 'USD',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    // Utility method to format numbers
    static formatNumber(number) {
        return new Intl.NumberFormat('en-US').format(number);
    }

    // Utility method to copy text to clipboard
    static copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                this.showNotification('Copied to clipboard!', 'success');
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            this.showNotification('Copied to clipboard!', 'success');
        }
    }

    // Show notification
    static showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        // Style the notification
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Export checklist functionality
    static exportChecklist() {
        const checkedItems = document.querySelectorAll('.checkbox.checked');
        const checklistData = Array.from(checkedItems).map(checkbox => {
            const item = checkbox.closest('.checklist-item');
            return {
                title: item.querySelector('h4')?.textContent || '',
                description: item.querySelector('p')?.textContent || '',
                required: item.querySelector('.required')?.textContent || ''
            };
        });

        // Convert to CSV
        const csvContent = [
            ['Title', 'Description', 'Required'],
            ...checklistData.map(item => [item.title, item.description, item.required])
        ].map(row => row.map(field => `"${field}"`).join(',')).join('\n');

        // Download CSV
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'visa-checklist.csv';
        a.click();
        URL.revokeObjectURL(url);
        
        this.showNotification('Checklist exported successfully!', 'success');
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new NomadVisaFrontend();
});

// Export for global use
window.NomadVisaFrontend = NomadVisaFrontend;

// Add some additional utility functions
document.addEventListener('DOMContentLoaded', () => {
    // Add loading states to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Don't add loading to anchor links
            if (this.tagName === 'A') return;
            
            this.style.position = 'relative';
            this.style.pointerEvents = 'none';
            
            const originalText = this.textContent;
            this.innerHTML = '<span class="spinner" style="width: 1rem; height: 1rem;"></span> Loading...';
            
            setTimeout(() => {
                this.textContent = originalText;
                this.style.pointerEvents = '';
            }, 1000);
        });
    });

    // Add search functionality if search input exists
    const searchInput = document.querySelector('#country-search');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const countryCards = document.querySelectorAll('.nvb-country-card');
            
            countryCards.forEach(card => {
                const countryName = card.querySelector('h3').textContent.toLowerCase();
                const countryMeta = card.querySelector('.country-meta').textContent.toLowerCase();
                
                if (countryName.includes(searchTerm) || countryMeta.includes(searchTerm)) {
                    card.style.display = 'block';
                    card.classList.add('fade-in');
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // Add print functionality
    const printButton = document.querySelector('#print-country-detail');
    if (printButton) {
        printButton.addEventListener('click', () => {
            window.print();
        });
    }

    // Add bookmark functionality
    const bookmarkButtons = document.querySelectorAll('.bookmark-btn');
    bookmarkButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const countryName = button.dataset.countryName;
            const isBookmarked = button.classList.contains('bookmarked');
            
            if (isBookmarked) {
                localStorage.removeItem(`bookmark-${countryName}`);
                button.classList.remove('bookmarked');
                button.innerHTML = '☆ Bookmark';
                NomadVisaFrontend.showNotification('Bookmark removed', 'info');
            } else {
                localStorage.setItem(`bookmark-${countryName}`, 'true');
                button.classList.add('bookmarked');
                button.innerHTML = '★ Bookmarked';
                NomadVisaFrontend.showNotification('Country bookmarked!', 'success');
            }
        });
    });
});