<div class="newsfeed-wrapper">
    <h1 class="title">{{ params['widget'] }}</h1>
    <div class="news-container container">
        <div class="news-wrapper">
            {% for item in model %}
                {% set images = item.content.getImagesArray() %}
                <div class="news-box">
                    <div class="overlay"></div>
                    <a class="wrap" href="">
                        {% if images and images.introImage %}
                        <div class="news-image">
                            <img src="{{ images.introImage }}">
                        </div>
                        {% endif %}
                        <div class="text-wrapper">
                            <h3>{{ item.content.title }}</h3>

                            <div class="news-content">
                                {{ helper.htmlDecode(item.content.introtext) }}
                            </div>
                        </div>
                    </a>
                </div>
            {% endfor %}
        </div>
    </div>
    <h4 class="title"><a href="{{ url("/articles") }}">SEE ALL ARTICLES</a></h4>
</div>