<script>
    $(document).ready(function(){
        initializeArticlesGrid();
    });

    function initializeArticlesGrid() {
        var articlesList = $('.articles-list-wrapper');
        var setting = {
            gap: 5,
            gridWidth: [0,400,600,1200],
            refresh: 500,
            scrollbottom : {
                ele: articlesList,
                endtxt : 'No More~~',
                gap: 300
            }
        };

        articlesList.waterfall(setting);
    }
</script>