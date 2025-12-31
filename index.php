<?php
include 'include/config.php';
// include_once('include/config.php');
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($con, $_POST['fullname']);
    $email = mysqli_real_escape_string($con, $_POST['emailid']);
    $mobileno = mysqli_real_escape_string($con, $_POST['mobileno']);
    $dscrption = mysqli_real_escape_string($con, $_POST['description']);
    $query = mysqli_query($con, "insert into tblcontactus(fullname,email,contactno,message) values('$name','$email','$mobileno','$dscrption')");
    if ($query) {
        echo "<script>alert('Your information succesfully submitted');</script>";
        echo "<script>window.location.href ='index.php'</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again.');</script>";
    }
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrimeMed | Advanced Healthcare Management System</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/fav.jpg">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <link rel="stylesheet" href="./css/indexCss.css" />



</head>

<body>
    <?php include 'inc/MainNavbar.php'; ?>

    <!-- Ultimate Hero Section -->
    <section class="hero-ultimate" style="padding-top: 150px;" id="home">
        <!-- Video Background -->
        <div class="hero-video-container">
            <video autoplay muted loop playsinline poster="assets/images/slider/slider_3.jpg">
                <source src="assets/videos/hero-bg.mp4" type="video/mp4">
                <source src="assets/videos/hero-bg.webm" type="video/webm">
                <!-- Fallback -->
                <img src="assets/images/slider/slider_3.jpg" alt="Healthcare Background">
            </video>
            <div class="hero-gradient-overlay"></div>
            <div class="hero-particles" id="particles"></div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="hero-content-ultimate">
                        <div class="hero-badge-ultimate">
                            <i class="fas fa-award me-2"></i> Excellence in Healthcare Since 2010
                        </div>

                        <h1 class="hero-title-ultimate">
                            Redefining <span>Healthcare</span> With Technology & Compassion
                        </h1>

                        <p class="hero-subtitle-ultimate">
                            Experience world-class medical care powered by cutting-edge technology,
                            delivered by compassionate professionals dedicated to your well-being.
                        </p>

                        <div class="hero-cta-ultimate">
                            <a href="#appointment" class="btn btn-hero-primary">
                                <i class="fas fa-calendar-check me-2"></i>Book Appointment Now
                            </a>
                            <a href="#contact" class="btn btn-hero-secondary">
                                <i class="fas fa-play-circle me-2"></i>View Virtual Tour
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="hero-stats-ultimate">
                <div class="stat-card-ultimate animate-element">
                    <div class="stat-icon-ultimate">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="stat-number-ultimate" data-count="500">0</div>
                    <div class="stat-label-ultimate">Board Certified Specialists</div>
                </div>

                <div class="stat-card-ultimate animate-element">
                    <div class="stat-icon-ultimate">
                        <i class="fas fa-ambulance"></i>
                    </div>
                    <div class="stat-number-ultimate">24/7</div>
                    <div class="stat-label-ultimate">Emergency Response</div>
                </div>

                <div class="stat-card-ultimate animate-element">
                    <div class="stat-icon-ultimate">
                        <i class="fas fa-hospital-user"></i>
                    </div>
                    <div class="stat-number-ultimate" data-count="99.7">0</div>
                    <div class="stat-label-ultimate">Patient Satisfaction Rate</div>
                </div>

                <div class="stat-card-ultimate animate-element">
                    <div class="stat-icon-ultimate">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="stat-number-ultimate" data-count="50">0</div>
                    <div class="stat-label-ultimate">Medical Specialties</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Ultimate Emergency Banner -->
    <div class="container">
        <div class="emergency-ultimate animate-element">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3 class="mb-2"><i class="fas fa-ambulance me-2"></i>Emergency Medical Services Available 24/7</h3>
                    <p class="mb-0">Critical care when you need it most. Our emergency response team is always ready.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="tel:+1234567890" class="btn btn-light btn-premium">
                        <i class="fas fa-phone-alt me-2"></i>Emergency: +1 (234) 567-890
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Hospital Features Section -->
    <section class="section-hospital-features" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="mb-3">Our Hospital <span class="text-primary">Advantages</span></h2>
                <p class="text-muted">Quality healthcare services designed for your well-being</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="hospital-feature-card">
                        <div class="feature-icon-hospital">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h5 class="mt-3 mb-2">Expert Medical Team</h5>
                        <p class="text-muted small">Our board-certified doctors and specialists provide personalized care with years of experience.</p>
                        <div class="feature-badge">500+ Doctors</div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="hospital-feature-card">
                        <div class="feature-icon-hospital">
                            <i class="fas fa-ambulance"></i>
                        </div>
                        <h5 class="mt-3 mb-2">24/7 Emergency Care</h5>
                        <p class="text-muted small">Round-the-clock emergency services with rapid response and critical care facilities.</p>
                        <div class="feature-badge">Emergency Ready</div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="hospital-feature-card">
                        <div class="feature-icon-hospital">
                            <i class="fas fa-procedures"></i>
                        </div>
                        <h5 class="mt-3 mb-2">Advanced Facilities</h5>
                        <p class="text-muted small">State-of-the-art operation theaters, ICU, and diagnostic centers with modern equipment.</p>
                        <div class="feature-badge">Modern Infrastructure</div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="hospital-feature-card">
                        <div class="feature-icon-hospital">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <h5 class="mt-3 mb-2">Comprehensive Diagnostics</h5>
                        <p class="text-muted small">Advanced laboratory, imaging, and diagnostic services for accurate medical assessments.</p>
                        <div class="feature-badge">Accurate Results</div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="hospital-feature-card">
                        <div class="feature-icon-hospital">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h5 class="mt-3 mb-2">Patient-Centered Care</h5>
                        <p class="text-muted small">Personalized treatment plans and compassionate care focused on individual patient needs.</p>
                        <div class="feature-badge">99% Satisfaction</div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="hospital-feature-card">
                        <div class="feature-icon-hospital">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h5 class="mt-3 mb-2">Easy Appointment System</h5>
                        <p class="text-muted small">Quick online booking, minimal waiting time, and flexible scheduling options.</p>
                        <div class="feature-badge">Quick Access</div>
                    </div>
                </div>
            </div>

            <!-- Hospital Stats -->
            <div class="row mt-5 pt-4">
                <div class="col-12">
                    <div class="hospital-stats">
                        <div class="row text-center">
                            <div class="col-md-3 mb-4 mb-md-0">
                                <div class="stat-number">98%</div>
                                <div class="stat-label">Treatment Success Rate</div>
                            </div>
                            <div class="col-md-3 mb-4 mb-md-0">
                                <div class="stat-number">15min</div>
                                <div class="stat-label">Average Waiting Time</div>
                            </div>
                            <div class="col-md-3 mb-4 mb-md-0">
                                <div class="stat-number">500+</div>
                                <div class="stat-label">Successful Surgeries</div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-number">1000+</div>
                                <div class="stat-label">Happy Patients Monthly</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

   

    <!-- Ultimate Services Section -->
    <section class="section-ultimate bg-light" id="services">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title-ultimate center">Advanced Medical <span class="text-gradient-primary">Services</span></h2>
                    <p class="section-subtitle-ultimate">Comprehensive healthcare solutions delivered by world-class specialists.</p>
                </div>
            </div>

            <div class="services-ultimate-grid mt-5">
                <?php
                $services = [
                    ['icon' => 'fas fa-heartbeat', 'title' => 'Cardiology', 'color' => '#FF6B6B'],
                    ['icon' => 'fas fa-brain', 'title' => 'Neurology', 'color' => '#4CC9F0'],
                    ['icon' => 'fas fa-bone', 'title' => 'Orthopedics', 'color' => '#2E7D32'],
                    ['icon' => 'fas fa-baby', 'title' => 'Pediatrics', 'color' => '#FF9800'],
                    ['icon' => 'fas fa-eye', 'title' => 'Ophthalmology', 'color' => '#9C27B0'],
                    ['icon' => 'fas fa-tooth', 'title' => 'Dentistry', 'color' => '#2196F3'],
                    ['icon' => 'fas fa-lungs', 'title' => 'Pulmonology', 'color' => '#00BCD4'],
                    ['icon' => 'fas fa-dna', 'title' => 'Genetics', 'color' => '#E91E63'],
                ];

                foreach ($services as $index => $service) {
                ?>
                    <div class="service-ultimate-card animate-element" style="animation-delay: <?php echo ($index * 0.1); ?>s">
                        <div class="service-icon-ultimate" style="background: linear-gradient(135deg, <?php echo $service['color']; ?>, <?php echo $service['color']; ?>80);">
                            <i class="<?php echo $service['icon']; ?>"></i>
                        </div>
                        <h4 class="mb-3"><?php echo $service['title']; ?></h4>
                        <p class="mb-4">Advanced treatment and care for comprehensive health solutions.</p>
                        <a href="#appointment" class="btn btn-outline-primary">Book Consultation</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Ultimate Appointment Section -->
    <section class="section-ultimate" id="appointment">
        <div class="container">
            <div class="appointment-ultimate animate-element">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h2 class="mb-4">Ready to Experience Premium Healthcare?</h2>
                        <p class="mb-4">Schedule your appointment with our world-class specialists. Fast, secure, and convenient.</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="user-login.php" class="btn btn-light btn-premium">
                                <i class="fas fa-calendar-check me-2"></i>Book Online Now
                            </a>
                            <a href="tel:+1234567890" class="btn btn-outline-light">
                                <i class="fas fa-phone me-2"></i>Call for Appointment
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="appointment-stats mt-4 mt-lg-0">
                            <div class="appointment-stat">
                                <div class="stat-number-ultimate" style="font-size: 2.8rem;">15min</div>
                                <div class="stat-label-ultimate">Avg. Response Time</div>
                            </div>
                            <div class="appointment-stat">
                                <div class="stat-number-ultimate" style="font-size: 2.8rem;">4.9★</div>
                                <div class="stat-label-ultimate">Patient Rating</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Ultimate Contact Section -->
    <section class="section-ultimate" id="contact">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title-ultimate center">Get In <span class="text-gradient-primary">Touch</span></h2>
                    <p class="section-subtitle-ultimate">Have questions? Our team is ready to assist you with any inquiries.</p>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="contact-ultimate-form animate-element">
                        <h3 class="mb-4">Send Your Message</h3>
                        <form method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-ultimate">
                                        <label class="form-label-ultimate">Full Name *</label>
                                        <input type="text" class="form-control-ultimate" name="fullname" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-ultimate">
                                        <label class="form-label-ultimate">Email Address *</label>
                                        <input type="email" class="form-control-ultimate" name="emailid" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group-ultimate">
                                <label class="form-label-ultimate">Mobile Number *</label>
                                <input type="tel" class="form-control-ultimate" name="mobileno" required>
                            </div>
                            <div class="form-group-ultimate">
                                <label class="form-label-ultimate">Your Message *</label>
                                <textarea class="form-control-ultimate" name="description" rows="5" required></textarea>
                            </div>
                            <button type="submit" name="submit" class="btn btn-premium w-100">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="row mt-5">
                <?php
                $sql = "SELECT * FROM tblpage WHERE PageType = 'contactus'";
                $result = mysqli_query($con, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="col-md-3 mb-4">
                            <h5><?= htmlspecialchars($row['PageTitle']) ?></h5>
                            <p><?= nl2br(htmlspecialchars($row['PageDescription'])) ?></p>
                        </div>
                <?php
                    }
                } else {
                    echo '<p class="text-muted">No contact information found.</p>';
                }
                ?>
            </div>

    </section>

    <?php include 'inc/MainFooter.php';  ?>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        // Navbar scroll effect
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('.navbar-premium').addClass('scrolled');
            } else {
                $('.navbar-premium').removeClass('scrolled');
            }
        });


        // Animated counters
        function animateCounter(element) {
            var $this = $(element);
            var countTo = $this.attr('data-count');
            if (countTo) {
                var count = parseFloat(countTo);
                var duration = 2000;
                var start = 0;
                var increment = count / (duration / 30);

                var current = 0;
                var timer = setInterval(function() {
                    current += increment;
                    if (current >= count) {
                        $this.text(countTo + (countTo == '99.7' ? '%' : '+'));
                        clearInterval(timer);
                    } else {
                        $this.text(Math.floor(current) + (countTo == '99.7' ? '%' : '+'));
                    }
                }, 30);
            }
        }

        // Scroll animations
        function animateOnScroll() {
            $('.animate-element').each(function() {
                var elementTop = $(this).offset().top;
                var elementBottom = elementTop + $(this).outerHeight();
                var viewportTop = $(window).scrollTop();
                var viewportBottom = viewportTop + $(window).height();

                if (elementBottom > viewportTop && elementTop < viewportBottom - 100) {
                    if (!$(this).hasClass('animated')) {
                        $(this).addClass('animated');

                        // Animate counters when in view
                        if ($(this).hasClass('stat-card-ultimate')) {
                            setTimeout(function() {
                                animateCounter($(this).find('.stat-number-ultimate'));
                            }.bind(this), 300);
                        }
                    }
                }
            });
        }

        // Create floating particles
        function createParticles() {
            var particlesContainer = $('#particles');
            if (particlesContainer.length) {
                for (var i = 0; i < 15; i++) {
                    var size = Math.random() * 100 + 50;
                    var posX = Math.random() * 100;
                    var posY = Math.random() * 100;
                    var duration = Math.random() * 20 + 10;
                    var delay = Math.random() * 10;

                    var particle = $('<div class="particle"></div>').css({
                        width: size + 'px',
                        height: size + 'px',
                        left: posX + '%',
                        top: posY + '%',
                        animationDelay: delay + 's',
                        animationDuration: duration + 's'
                    });

                    particlesContainer.append(particle);
                }
            }
        }

        // Form submission handling
        $('form').on('submit', function(e) {
            var submitBtn = $(this).find('button[type="submit"]');
            var originalText = submitBtn.html();

            submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');
            submitBtn.prop('disabled', true);

            // Simulate processing time
            setTimeout(function() {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }, 2000);
        });

        // Video autoplay handling
        $(document).ready(function() {
            var video = $('.hero-video-container video')[0];
            if (video) {
                var playPromise = video.play();
                if (playPromise !== undefined) {
                    playPromise.catch(function(error) {
                        console.log('Video autoplay prevented, showing fallback');
                    });
                }
            }

            // Create particles
            createParticles();

            // Initialize scroll animations
            animateOnScroll();
        });

        // Event listeners
        $(window).on('scroll', animateOnScroll);
        $(window).on('load', animateOnScroll);

        // Emergency banner animation restart
        setInterval(function() {
            $('.emergency-ultimate').css('animation', 'none');
            setTimeout(function() {
                $('.emergency-ultimate').css('animation', 'pulse-glow 2s infinite');
            }, 10);
        }, 4000);

        // Current year update
        $(document).ready(function() {
            var currentYear = new Date().getFullYear();
            $('.copyright-ultimate p:first-child').html('&copy; ' + currentYear + ' PrimeMed Healthcare Management System. All rights reserved.');
        });

        // Add hover effect to all interactive elements
        $('.btn-premium, .feature-ultimate-card, .portal-ultimate-card, .service-ultimate-card, .stat-card-ultimate')
            .on('mouseenter', function() {
                $(this).css('transition', 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)');
            })
            .on('mouseleave', function() {
                $(this).css('transition', 'var(--transition)');
            });
    </script>
</body>

</html>