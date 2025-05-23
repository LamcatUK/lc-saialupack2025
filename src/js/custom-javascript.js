// Add your custom JS here.
document.addEventListener('DOMContentLoaded', function() {

    AOS.init({
        duration: 700,           // slightly slower than default (400) for a smoother feel
        easing: 'ease-out-cubic', // gentle deceleration (feels more natural than 'ease-in')
        once: true,               // animations happen once per element
        mirror: false,            // do not animate when scrolling back up
        offset: 100,              // triggers animations 100px before the element enters view
        delay: 0,                 // let individual elements set their own delay via data-aos-delay
    });

    (function() {
    // Hide header on scroll
    var doc = document.documentElement;
    var w = window;

    var prevScroll = w.scrollY || doc.scrollTop;
    var curScroll;
    var direction = 0;
    var prevDirection = 0;

    var header = document.getElementById('wrapper-navbar');

    if (!header) {
        console.error('Element with ID "wrapper-navbar" not found.');
        return;
    }

    var checkScroll = function() {
        // Find the direction of scroll (0 - initial, 1 - up, 2 - down)
        curScroll = w.scrollY || doc.scrollTop;
        if (curScroll > prevScroll) {
            // Scrolled down
            direction = 2;
        } else if (curScroll < prevScroll) {
            // Scrolled up
            direction = 1;
        }

        if (direction !== prevDirection) {
            toggleHeader(direction, curScroll);
        }

        prevScroll = curScroll;
    };

    var toggleHeader = function(direction, curScroll) {
        if (direction === 2 && curScroll > 100) {
            // Replace 52 with the height of your header in px
            if ( ! document.getElementById('wrapper-navbar').classList.contains('show')) {
                header.classList.add('hide');
                prevDirection = direction;
            }
        } else if (direction === 1) {
            header.classList.remove('hide');
            prevDirection = direction;
        }
    };

    window.addEventListener('scroll', checkScroll);

    //   // Header background
    //   document.addEventListener('scroll', function() {
    //       document.querySelectorAll('.dropdown-menu').forEach(function(dropdown) {
    //           dropdown.classList.remove('show');
    //       });
    //       document.querySelectorAll('.dropdown-toggle').forEach(function(toggle) {
    //           toggle.classList.remove('show');
    //           toggle.blur();
    //       });
    //     });


    })();
});