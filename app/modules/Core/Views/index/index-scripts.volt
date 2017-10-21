<script>
    $(document).ready(function(){
        carouselConfig();
        $('#first-tab').addClass('in');
    });

    function carouselConfig() {
        $('.owl-carousel').owlCarousel({
            navigation : true,
            dots: true,
            pagination: true,
            items: 1
        });
    }
</script>