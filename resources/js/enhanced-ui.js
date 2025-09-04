// ===== SIGAP KOMPLEK ENHANCED UI JAVASCRIPT =====

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== SMOOTH SCROLLING =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const navHeight = document.querySelector('nav').offsetHeight;
                const targetPosition = target.offsetTop - navHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ===== INTERSECTION OBSERVER FOR ANIMATIONS =====
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0) scale(1)';
                    entry.target.classList.add('loaded');
                }, index * 150); // Stagger animation
            }
        });
    }, observerOptions);

    // Observe all animated elements
    document.querySelectorAll('.feature-card, .benefit-card, .animate-fade-in, .animate-slide-up').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(50px) scale(0.95)';
        el.style.transition = 'all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        el.classList.add('loading');
        observer.observe(el);
    });

    // ===== ENHANCED NAVBAR SCROLL EFFECT =====
    let lastScrollY = window.scrollY;
    let ticking = false;

    function updateNavbar() {
        const navbar = document.querySelector('nav');
        const currentScrollY = window.scrollY;
        
        if (currentScrollY > 100) {
            navbar.classList.add('shadow-2xl');
            navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
            
            if (currentScrollY > lastScrollY && currentScrollY > 200) {
                // Scrolling down
                navbar.style.transform = 'translateY(-100%)';
            } else {
                // Scrolling up
                navbar.style.transform = 'translateY(0)';
            }
        } else {
            navbar.classList.remove('shadow-2xl');
            navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
            navbar.style.transform = 'translateY(0)';
        }
        
        lastScrollY = currentScrollY;
        ticking = false;
    }

    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(updateNavbar);
            ticking = true;
        }
    });

    // ===== PARALLAX EFFECT =====
    function updateParallax() {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.3;
        const header = document.querySelector('header');
        
        if (header && scrolled < window.innerHeight) {
            header.style.transform = `translateY(${rate}px)`;
        }
    }

    let parallaxTicking = false;
    window.addEventListener('scroll', () => {
        if (!parallaxTicking) {
            requestAnimationFrame(updateParallax);
            parallaxTicking = true;
            setTimeout(() => { parallaxTicking = false; }, 10);
        }
    });

    // ===== INTERACTIVE CARD TILT EFFECT =====
    document.querySelectorAll('.feature-card, .benefit-card').forEach(card => {
        card.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;
            
            this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px) scale(1.02)`;
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0) scale(1)';
        });
    });

    // ===== RIPPLE EFFECT FOR BUTTONS =====
    document.querySelectorAll('button, .feature-card-btn, .cta-btn-primary, .cta-btn-secondary').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // ===== COUNTER ANIMATION =====
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            element.textContent = Math.floor(current);
        }, 20);
    }

    // Trigger counter animation when stats section is visible
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counters = entry.target.querySelectorAll('.counter');
                
                counters.forEach(counter => {
                    const target = parseInt(counter.getAttribute('data-target'));
                    animateCounter(counter, target);
                });
                
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    const statsSection = document.querySelector('.grid.grid-cols-2.md\\:grid-cols-4');
    if (statsSection) {
        statsObserver.observe(statsSection);
    }

    // ===== MOBILE MENU TOGGLE =====
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('show');
            
            // Animate hamburger icon
            const icon = this.querySelector('svg');
            if (mobileMenu.classList.contains('show')) {
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                `;
            } else {
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                `;
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.remove('show');
                const icon = mobileMenuBtn.querySelector('svg');
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                `;
            }
        });
    }

    // ===== DYNAMIC GRADIENT ANIMATION =====
    let gradientAngle = 0;
    function animateGradient() {
        gradientAngle += 0.5;
        const hero = document.querySelector('header');
        if (hero) {
            const overlay = hero.querySelector('.hero-overlay');
            if (overlay) {
                overlay.style.background = `linear-gradient(${gradientAngle}deg, rgba(0,0,0,0.5), rgba(0,0,0,0.3), rgba(245,158,11,0.1))`;
            }
        }
        requestAnimationFrame(animateGradient);
    }
    animateGradient();

    // ===== INTERACTIVE HOVER EFFECTS =====
    document.querySelectorAll('.feature-card, .benefit-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            // Add glow effect
            this.style.boxShadow = '0 25px 50px -12px rgba(245, 158, 11, 0.25), 0 0 0 1px rgba(245, 158, 11, 0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });

    // ===== DYNAMIC BACKGROUND COLOR ON SCROLL =====
    window.addEventListener('scroll', () => {
        const scrollPercent = window.scrollY / (document.body.scrollHeight - window.innerHeight);
        const hue = Math.floor(scrollPercent * 60); // 0 to 60 degrees
        document.body.style.background = `hsl(${hue}, 10%, 98%)`;
    });

    // ===== LOADING ANIMATION =====
    window.addEventListener('load', () => {
        document.body.style.opacity = '0';
        document.body.style.transition = 'opacity 0.5s ease-in-out';
        
        setTimeout(() => {
            document.body.style.opacity = '1';
        }, 100);
    });

    // ===== SCROLL-TRIGGERED ANIMATIONS =====
    const scrollTriggers = document.querySelectorAll('[data-scroll]');
    
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    scrollTriggers.forEach(trigger => {
        scrollObserver.observe(trigger);
    });

    // ===== INTERACTIVE LOGO ANIMATION =====
    const heroLogo = document.querySelector('.hero-logo-main img');
    if (heroLogo) {
        heroLogo.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1) rotate(5deg)';
        });
        
        heroLogo.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    }

    // ===== STAGGERED CARD ANIMATIONS =====
    function staggerAnimation(elements, delay = 200) {
        elements.forEach((element, index) => {
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0) scale(1)';
            }, index * delay);
        });
    }

    // Apply staggered animation to feature cards
    const featureCards = document.querySelectorAll('.feature-card-wrapper');
    const benefitCards = document.querySelectorAll('.benefit-card-wrapper');

    const featureObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                staggerAnimation(featureCards, 200);
                featureObserver.disconnect();
            }
        });
    }, { threshold: 0.2 });

    const benefitObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                staggerAnimation(benefitCards, 150);
                benefitObserver.disconnect();
            }
        });
    }, { threshold: 0.2 });

    if (featureCards.length > 0) {
        featureObserver.observe(featureCards[0]);
    }
    
    if (benefitCards.length > 0) {
        benefitObserver.observe(benefitCards[0]);
    }

    // ===== ENHANCED BUTTON INTERACTIONS =====
    document.querySelectorAll('.feature-card-btn, .cta-btn-primary, .cta-btn-secondary').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.05)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // ===== DYNAMIC ICON ANIMATIONS =====
    document.querySelectorAll('.feature-icon, .benefit-icon').forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            const svg = this.querySelector('svg');
            if (svg) {
                svg.style.transform = 'scale(1.2) rotate(10deg)';
                svg.style.transition = 'transform 0.3s ease';
            }
        });
        
        icon.addEventListener('mouseleave', function() {
            const svg = this.querySelector('svg');
            if (svg) {
                svg.style.transform = 'scale(1) rotate(0deg)';
            }
        });
    });

    // ===== TYPING EFFECT FOR HERO SUBTITLE =====
    function typeWriter(element, text, speed = 100) {
        let i = 0;
        element.textContent = '';
        
        function type() {
            if (i < text.length) {
                element.textContent += text.charAt(i);
                i++;
                setTimeout(type, speed);
            }
        }
        
        setTimeout(type, 1000); // Start after 1 second
    }

    const heroSubtitle = document.querySelector('header p[style*="animation-delay: 0.6s"]');
    if (heroSubtitle) {
        const originalText = heroSubtitle.textContent;
        typeWriter(heroSubtitle, originalText, 50);
    }

    // ===== SCROLL PROGRESS INDICATOR =====
    function createScrollProgress() {
        const progressBar = document.createElement('div');
        progressBar.className = 'scroll-progress';
        progressBar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, #F59E0B, #F97316);
            z-index: 9999;
            transition: width 0.3s ease;
        `;
        document.body.appendChild(progressBar);

        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            progressBar.style.width = scrolled + '%';
        });
    }
    createScrollProgress();

    // ===== ENHANCED STATS COUNTER =====
    function enhancedCounterAnimation(element, target, suffix = '') {
        let current = 0;
        const duration = 2000; // 2 seconds
        const steps = 60;
        const increment = target / steps;
        const stepDuration = duration / steps;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            let displayValue = Math.floor(current);
            
            // Format based on target value
            if (target >= 1000) {
                displayValue = (current / 1000).toFixed(1) + 'K';
            } else if (target === 98) {
                displayValue = Math.floor(current);
            }
            
            element.textContent = displayValue + suffix;
            
            // Add bounce effect at milestones
            if (current >= target * 0.25 || current >= target * 0.5 || current >= target * 0.75 || current >= target) {
                element.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    element.style.transform = 'scale(1)';
                }, 150);
            }
        }, stepDuration);
    }

    // Enhanced stats observer
    const enhancedStatsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counters = entry.target.querySelectorAll('.counter');
                
                counters.forEach((counter, index) => {
                    const target = parseInt(counter.getAttribute('data-target'));
                    const suffix = target === 98 ? '%' : '+';
                    
                    setTimeout(() => {
                        enhancedCounterAnimation(counter, target, suffix);
                    }, index * 300);
                });
                
                enhancedStatsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    const statsContainer = document.querySelector('.grid.grid-cols-2.md\\:grid-cols-4');
    if (statsContainer) {
        enhancedStatsObserver.observe(statsContainer);
    }

    // ===== INTERACTIVE DASHBOARD MOCK =====
    const dashboardCharts = document.querySelectorAll('.chart-progress');
    dashboardCharts.forEach((chart, index) => {
        chart.addEventListener('mouseenter', function() {
            this.style.transform = 'scaleY(1.2)';
            this.style.filter = 'brightness(1.1)';
        });
        
        chart.addEventListener('mouseleave', function() {
            this.style.transform = 'scaleY(1)';
            this.style.filter = 'brightness(1)';
        });
    });

    // ===== LAZY LOADING FOR IMAGES =====
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.style.opacity = '0';
                img.style.transition = 'opacity 0.5s ease';
                
                setTimeout(() => {
                    img.style.opacity = '1';
                }, 100);
                
                imageObserver.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img').forEach(img => {
        imageObserver.observe(img);
    });

    // ===== PERFORMANCE OPTIMIZATION =====
    // Throttle resize events
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            // Recalculate animations on resize
            updateNavbar();
            updateParallax();
        }, 250);
    });

    // ===== ACCESSIBILITY ENHANCEMENTS =====
    // Skip to content link
    const skipLink = document.createElement('a');
    skipLink.href = '#fitur';
    skipLink.textContent = 'Skip to main content';
    skipLink.className = 'sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-sigap-yellow text-white px-4 py-2 rounded z-50';
    document.body.insertBefore(skipLink, document.body.firstChild);

    // Keyboard navigation for cards
    document.querySelectorAll('.feature-card, .benefit-card').forEach(card => {
        card.setAttribute('tabindex', '0');
        
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const link = this.querySelector('a');
                if (link) {
                    link.click();
                }
            }
        });
        
        card.addEventListener('focus', function() {
            this.style.outline = '2px solid #F59E0B';
            this.style.outlineOffset = '2px';
        });
        
        card.addEventListener('blur', function() {
            this.style.outline = 'none';
        });
    });

    // ===== ENHANCED USER DROPDOWN =====
    const userDropdowns = document.querySelectorAll('[x-data]');
    userDropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('button');
        const menu = dropdown.querySelector('[x-show]');
        
        if (button && menu) {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.02)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        }
    });

    // ===== ENHANCED LOGIN/REGISTER BUTTONS =====
    document.querySelectorAll('.btn-gradient, .btn-secondary').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            if (this.classList.contains('btn-gradient')) {
                this.style.boxShadow = '0 10px 25px -3px rgba(245, 158, 11, 0.4)';
            } else {
                this.style.boxShadow = '0 4px 12px -2px rgba(0, 0, 0, 0.1)';
            }
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });

    // ===== NOTIFICATION BADGE ANIMATION =====
    function addNotificationBadge(element, count) {
        const badge = document.createElement('span');
        badge.className = 'notification-badge';
        badge.style.cssText = `
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #EF4444, #DC2626);
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 9999px;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: bounce 1s infinite;
            z-index: 10;
        `;
        badge.textContent = count;
        
        element.style.position = 'relative';
        element.appendChild(badge);
    }

    // Example: Add notification badge to user avatar if there are pending notifications
    const userAvatar = document.querySelector('[x-data] button .w-8.h-8');
    if (userAvatar && window.pendingNotifications > 0) {
        addNotificationBadge(userAvatar, window.pendingNotifications);
    }

    // ===== ENHANCED MOBILE MENU =====
    function showLoadingSpinner(element) {
        const spinner = document.createElement('div');
        spinner.className = 'loading-spinner';
        spinner.style.cssText = `
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 32px;
            height: 32px;
            border: 3px solid rgba(245, 158, 11, 0.3);
            border-top: 3px solid #F59E0B;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        `;
        
        element.style.position = 'relative';
        element.appendChild(spinner);
        
        // Add spin animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: translate(-50%, -50%) rotate(0deg); }
                100% { transform: translate(-50%, -50%) rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }

    function hideLoadingSpinner(element) {
        const spinner = element.querySelector('.loading-spinner');
        if (spinner) {
            spinner.remove();
        }
    }

    // ===== TOAST NOTIFICATIONS =====
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10B981' : '#EF4444'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }

    // ===== ENHANCED FORM INTERACTIONS =====
    document.querySelectorAll('input, textarea, select').forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = '#F59E0B';
            this.style.boxShadow = '0 0 0 3px rgba(245, 158, 11, 0.1)';
        });
        
        input.addEventListener('blur', function() {
            this.style.borderColor = '#D1D5DB';
            this.style.boxShadow = 'none';
        });
    });

    // ===== MOUSE CURSOR TRAIL EFFECT =====
    const cursorTrail = [];
    const trailLength = 6;

    for (let i = 0; i < trailLength; i++) {
        const trail = document.createElement('div');
        trail.className = 'cursor-trail';
        trail.style.cssText = `
            position: fixed;
            width: ${8 - i}px;
            height: ${8 - i}px;
            background: rgba(245, 158, 11, ${0.8 - i * 0.1});
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            transition: all 0.1s ease;
        `;
        document.body.appendChild(trail);
        cursorTrail.push(trail);
    }

    let mouseX = 0, mouseY = 0;
    
    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    function updateCursorTrail() {
        cursorTrail.forEach((trail, index) => {
            setTimeout(() => {
                trail.style.left = mouseX + 'px';
                trail.style.top = mouseY + 'px';
            }, index * 50);
        });
        requestAnimationFrame(updateCursorTrail);
    }
    updateCursorTrail();

    // ===== EASTER EGG - KONAMI CODE =====
    let konamiCode = '';
    const konamiSequence = 'ArrowUpArrowUpArrowDownArrowDownArrowLeftArrowRightArrowLeftArrowRightKeyBKeyA';
    
    document.addEventListener('keydown', (e) => {
        konamiCode += e.code;
        
        if (konamiCode.length > konamiSequence.length) {
            konamiCode = konamiCode.slice(-konamiSequence.length);
        }
        
        if (konamiCode === konamiSequence) {
            // Activate special effects
            document.body.style.background = 'linear-gradient(45deg, #F59E0B, #F97316, #EF4444, #EC4899)';
            document.body.style.backgroundSize = '400% 400%';
            document.body.style.animation = 'gradient 2s ease infinite';
            
            showToast('🎉 Easter Egg Activated! SIGAP KOMPLEK Special Mode!', 'success');
            
            // Reset after 5 seconds
            setTimeout(() => {
                document.body.style.background = '';
                document.body.style.backgroundSize = '';
                document.body.style.animation = '';
            }, 5000);
            
            konamiCode = '';
        }
    });

    // ===== PERFORMANCE MONITORING =====
    if ('performance' in window) {
        window.addEventListener('load', () => {
            const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
            console.log(`🚀 SIGAP KOMPLEK loaded in ${loadTime}ms`);
            
            if (loadTime > 3000) {
                console.warn('⚠️ Slow loading detected. Consider optimizing images and assets.');
            }
        });
    }

    // ===== CLEANUP AND ERROR HANDLING =====
    window.addEventListener('beforeunload', () => {
        // Cleanup animations
        document.querySelectorAll('[style*="animation"]').forEach(el => {
            el.style.animation = 'none';
        });
    });

    // Global error handler
    window.addEventListener('error', (e) => {
        console.error('SIGAP KOMPLEK UI Error:', e.error);
        // Graceful degradation - remove problematic animations
        document.querySelectorAll('.animate-float, .animate-bounce-in').forEach(el => {
            el.style.animation = 'none';
        });
    });

    console.log('✅ SIGAP KOMPLEK Enhanced UI loaded successfully!');
});

// ===== UTILITY FUNCTIONS =====

// Debounce function for performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function for scroll events
function throttle(func, delay) {
    let timeoutId;
    let lastExecTime = 0;
    return function (...args) {
        const currentTime = Date.now();
        
        if (currentTime - lastExecTime > delay) {
            func.apply(this, args);
            lastExecTime = currentTime;
        } else {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                func.apply(this, args);
                lastExecTime = Date.now();
            }, delay - (currentTime - lastExecTime));
        }
    };
}

// Check if element is in viewport
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

// Smooth scroll to element
function scrollToElement(elementId, offset = 0) {
    const element = document.getElementById(elementId);
    if (element) {
        const navHeight = document.querySelector('nav').offsetHeight;
        const targetPosition = element.offsetTop - navHeight - offset;
        
        window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
        });
    }
}