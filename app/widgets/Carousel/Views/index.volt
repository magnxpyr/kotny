<div class="carousel-wrapper ">
    <div id="ontop-carousel" class="carousel slide" data-ride="carousel">
        <div class="jumbotron">
            <h1>This is the innovation</h1>
            <p>This is some text.</p>
        </div>
        <div class="owl-carousel owl-theme">
            {% for index, image in images %}
                <div class="item img {% if index is 0 %} {{ "active" }} {% endif %}"
                     style="background-image: url('{{ image }}')"></div>
            {% endfor %}
        </div>
    </div>
</div>