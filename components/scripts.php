<!-- JAVASCRIPT -->
<script src="<?= $domain  ?>/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= $domain  ?>/assets/libs/simplebar/simplebar.min.js"></script>
<script src="<?= $domain  ?>/assets/libs/node-waves/waves.min.js"></script>
<script src="<?= $domain  ?>/assets/libs/feather-icons/feather.min.js"></script>
<script src="<?= $domain  ?>/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="<?= $domain  ?>/assets/js/plugins.js"></script>

<!-- password-addon init -->
<script src="<?= $domain  ?>/assets/libs/sweetalert2/sweetalert2.min.js"></script>
<!-- <script src="<?= $domain  ?>/assets/js/pages/sweetalerts.init.js"></script> -->

<script src="<?= $domain  ?>/assets/js/app.js"></script>

<!-- import axios from dns -->
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<!-- import jquery from dns -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
$(document).ready(() => {
 //prevent
        let currentModeInit = localStorage.getItem("data-layout-mode");
// if current mode is dark
// set session mode to dark
if (currentModeInit == "dark" && sessionStorage.getItem("data-layout-mode") == 'light') {
   $(".light-dark-mode").trigger("click")
} 
   

    $(".light-dark-mode").click(function(event) {

        let currentMode = $("html").attr("data-layout-mode");
            localStorage.setItem("data-layout-mode",currentMode);
       
    })
})
</script>

