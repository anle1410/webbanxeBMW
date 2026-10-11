// BMW Main JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Loader hide
    const loader = document.getElementById('loader');
    if (loader) {
        setTimeout(() => {
            loader.classList.add('hidden');
        }, 1000);
    }

    // Header scroll
    const header = document.querySelector('.header');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Mobile menu
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const navLinks = document.querySelector('.nav-links');
    
    if (hamburgerBtn && navLinks) {
        hamburgerBtn.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });
    }

    // Hero slider
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');
    const prevBtn = document.getElementById('slider-prev');
    const nextBtn = document.getElementById('slider-next');
    let currentSlide = 0;
    let slideInterval;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        slides[index].classList.add('active');
        dots[index].classList.add('active');
        currentSlide = index;
    }

    function nextSlide() {
        let next = currentSlide + 1;
        if (next >= slides.length) next = 0;
        showSlide(next);
    }

    function prevSlide() {
        let prev = currentSlide - 1;
        if (prev < 0) prev = slides.length - 1;
        showSlide(prev);
    }

    if (slides.length > 0) {
        slideInterval = setInterval(nextSlide, 5000);
    }

    if (dots.length > 0) {
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                showSlide(index);
                clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 5000);
            });
        });
    }

    if (prevBtn) prevBtn.addEventListener('click', function() {
        prevSlide();
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 5000);
    });

    if (nextBtn) nextBtn.addEventListener('click', function() {
        nextSlide();
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 5000);
    });

    // Back to top
    const backToTop = document.getElementById('back-to-top');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTop.classList.add('visible');
        } else {
            backToTop.classList.remove('visible');
        }
    });

    if (backToTop) {
        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Reveal animations
    const reveals = document.querySelectorAll('.reveal');
    
    function checkReveal() {
        reveals.forEach(element => {
            const windowHeight = window.innerHeight;
            const elementTop = element.getBoundingClientRect().top;
            const revealPoint = 150;
            
            if (elementTop < windowHeight - revealPoint) {
                element.classList.add('visible');
            }
        });
    }

    window.addEventListener('scroll', checkReveal);
    checkReveal();

    // Statistics counter
    const counters = document.querySelectorAll('.stat-number');
    
    function animateCounter(counter) {
        const target = parseInt(counter.getAttribute('data-count'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const timer = setInterval(function() {
            current += step;
            if (current >= target) {
                counter.textContent = target.toLocaleString();
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current).toLocaleString();
            }
        }, 16);
    }

    const statSection = document.querySelector('.statistics');
    if (statSection) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    counters.forEach(counter => animateCounter(counter));
                    observer.unobserve(entry.target);
                }
            });
        });
        observer.observe(statSection);
    }

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Image gallery for vehicle detail
    const mainImage = document.getElementById('main-image');
    const thumbnails = document.querySelectorAll('.thumbnail');

    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            const src = this.getAttribute('data-src');
            if (mainImage) {
                mainImage.src = src;
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    // Add to cart button
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
    
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const vehicleId = this.getAttribute('data-id');
            
            fetch('index.php?page=ajax&action=add_cart&id=' + vehicleId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('cart-count').textContent = data.cart_count;
                        alert('Đã thêm vào giỏ hàng!');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Quick view button
    const quickViewBtns = document.querySelectorAll('.quick-view-btn');
    
    quickViewBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const vehicleId = this.getAttribute('data-id');
            window.location.href = 'index.php?page=chitietxe&id=' + vehicleId;
        });
    });

    // Filter vehicles
    const categoryFilter = document.getElementById('category-filter');
    const sortFilter = document.getElementById('sort-filter');
    const searchInput = document.getElementById('search-input');
    const vehicleGrid = document.getElementById('vehicle-grid');

    function filterVehicles() {
        const category = categoryFilter ? categoryFilter.value : '';
        const sort = sortFilter ? sortFilter.value : '';
        const search = searchInput ? searchInput.value : '';

        // AJAX filter
        fetch('index.php?page=ajax&action=filter_vehicles&category=' + category + '&sort=' + sort + '&search=' + search)
            .then(response => response.text())
            .then(html => {
                if (vehicleGrid) {
                    vehicleGrid.innerHTML = html;
                }
            });
    }

    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterVehicles);
    }
    if (sortFilter) {
        sortFilter.addEventListener('change', filterVehicles);
    }
    if (searchInput) {
        searchInput.addEventListener('input', debounce(filterVehicles, 500));
    }
});

// Debounce function
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