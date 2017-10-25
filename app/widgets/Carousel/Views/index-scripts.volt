<script>
    $(document).ready(function(){
        carouselConfig();
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