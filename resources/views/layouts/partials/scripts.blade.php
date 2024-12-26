<script>
    var PATH_ROOT = '';
</script>

<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ asset('assets/js/plugins.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerIcon = document.getElementById('topnav-hamburger-icon');
        const customTextLogo = document.getElementById('custom-text-logo');

        hamburgerIcon.addEventListener('click', function() {
            if (customTextLogo.style.display === 'none') {
                customTextLogo.style.display = 'inline';
            } else {
                customTextLogo.style.display = 'none';
            }
        });
    });
</script>

<!-- App js -->
<script src="{{ asset('assets/js/app.js') }}"></script>
