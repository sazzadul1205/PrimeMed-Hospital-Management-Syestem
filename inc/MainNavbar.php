<!-- Premium Navigation -->
<nav class="navbar navbar-expand-lg navbar-premium " id="navbarPremium">
    <div class="container">
        <a class="nav-brand-premium" href="#home">
            <div class="nav-logo-icon-premium">
                <i class="fas fa-heartbeat"></i>
            </div>
            <div class="nav-logo-text-premium">PrimeMed</div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUltimate"
            aria-controls="navbarUltimate" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarUltimate">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link nav-link-premium active" href="#home">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-premium" href="#features">Features</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-premium" href="#services">Services</a>
                </li>


                <li class="nav-item">
                    <a class="nav-link nav-link-premium" href="#appointment">Appointment</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-premium" href="#contact">Contact</a>
                </li>
            </ul>

            <a href="user-login.php" class="btn btn-premium ms-lg-3">
                <i class="fas fa-user-circle me-2"></i>Patient Login
            </a>
        </div>
    </div>
</nav>

<!-- ScrollSpy JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navLinks = document.querySelectorAll('.nav-link-premium');
        const sections = Array.from(navLinks).map(link =>
            document.querySelector(link.getAttribute('href'))
        );

        function setActiveOnScroll() {
            const scrollPos = window.scrollY + window.innerHeight / 3;

            let currentSectionId = 'home';
            for (const section of sections) {
                if (section && section.offsetTop <= scrollPos) {
                    currentSectionId = section.id;
                }
            }

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + currentSectionId) {
                    link.classList.add('active');
                }
            });
        }

        setActiveOnScroll();
        window.addEventListener('scroll', setActiveOnScroll);
        window.addEventListener('hashchange', setActiveOnScroll);
    });
</script>