<div class="carousel-wrapper">
    <div id="ontop-carousel" class="carousel slide" data-ride="carousel">
        <div class="owl-carousel owl-theme">
            {% for index, image in params['_images'] %}
                <div class="item img {% if index is 0 %} {{ "active" }} {% endif %}"
                         style="background-image: url('{{ url(image) }}')">
                    {% if params['_text'] is defined and params['_text'][index] is defined and params['_text'][index] is not empty %}
                        <div class="carousel-headline">
                            {{ params['_text'][index] }}
                        </div>
                    {% endif %}
                </div>
            {% endfor %}
        </div>
    </div>
</div>

{% do assets.collection('header-css').addCss("https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css") %}
{% do assets.collection('header-css').addCss("https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.theme.default.min.css") %}
{% do assets.collection('footer-js').addJs("https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js") %}
{% do addViewWidgetJs('index-scripts') %}