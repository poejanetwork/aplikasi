
<div class="col-md-4">
    <div class="news_1_right">
        <div class="news_1_right1">
            <ul class="mb-0 bg_violet d-flex justify-content-between">
                <li><a class="bg_violet_dark social_icon d-inline-block text-center text-white"
                        href="{$settings.FACEBOOK}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                <li class="lh-1 pt-3 text-uppercase">
                    <a class="text-white" href="{$settings.FACEBOOK}" target="_blank">
                        <span class="font_13">
                            <b>Like Our Facebook Page </b><br>
                            <span class="font_11">86500 Likes</span>
                        </span>
                    </a>
                </li>
                <li class="pt-3 pe-3"><a
                        class="rounded-circle plus_icon rounded-circle text-white d-inline-block font_14 text-center" href="{$settings.FACEBOOK}" target="_blank"><i class="fa-brands fa-plus"></i></a></li>
            </ul>
            <ul class="mb-0 bg-primary d-flex justify-content-between mt-3">
                <li><a class="bg_primary_dark social_icon d-inline-block text-center text-white"
                        href="{$settings.TWITTER}" target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
                <li class="lh-1 pt-3 text-uppercase">
                    <a class="text-white" href="{$settings.TWITTER}" target="_blank">
                        <span class="font_13">
                            <b>Follow us twitter Page </b><br>
                            <span class="font_11">58500 Followers</span>
                        </span>
                    </a>
                </li>
                <li class="pt-3 pe-3"><a
                        class="rounded-circle plus_icon rounded-circle text-white d-inline-block font_14 text-center"
                        href="{$settings.TWITTER}" target="_blank"><i class="fa-brands fa-plus"></i></a></li>
            </ul>
            <ul class="mb-0 bg-danger d-flex justify-content-between mt-3">
                <li><a class="bg_danger_dark social_icon d-inline-block text-center text-white"
                        href="{$settings.YOUTUBE}" target="_blank"><i class="fa-brands fa-youtube"></i></a></li>
                <li class="lh-1 pt-3 text-uppercase">
                    <a class="text-white" href="{$settings.YOUTUBE}" target="_blank">
                        <span class="font_13">
                            <b>Follow us youtube Page </b><br>
                            <span class="font_11">105500 Subscriber</span>
                        </span>
                    </a>
                </li>
                <li class="pt-3 pe-3"><a
                        class="rounded-circle plus_icon rounded-circle text-white d-inline-block font_14 text-center"
                        href="{$settings.YOUTUBE}" target="_blank"><i class="fa-brands fa-plus"></i></a></li>
            </ul>
            <ul class="mb-0 bg-primary d-flex justify-content-between mt-3">
                <li><a class="bg_primary_dark social_icon d-inline-block text-center text-white"
                        href="{$settings.INSTAGRAM}" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                <li class="lh-1 pt-3 text-uppercase">
                    <a class="text-white" href="{$settings.INSTAGRAM}" target="_blank">
                        <span class="font_13">
                            <b>Follow us instagram Page </b><br>
                            <span class="font_11">58500 Followers</span>
                        </span>
                    </a>
                </li>
                <li class="pt-3 pe-3"><a
                        class="rounded-circle plus_icon rounded-circle text-white d-inline-block font_14 text-center"
                        href="{$settings.INSTAGRAM}" target="_blank"><i class="fa-brands fa-plus"></i></a></li>
            </ul>
            <ul class="mb-0 bg_yellow d-flex justify-content-between mt-3">
                <li><a class="bg_warning_dark social_icon d-inline-block text-center text-white"
                        href="javascript:void(0)"><i class="fa fa-rss"></i></a></li>
                <li class="lh-1 pt-3 text-uppercase">
                    <a class="text-white" href="javascript:void(0)">
                        <span class="font_13">
                            <b>Subscribe to our rss </b><br>
                            <span class="font_11">585 Subscribers</span>
                        </span>
                    </a>
                </li>
                <li class="pt-3 pe-3"><a
                        class="rounded-circle plus_icon rounded-circle text-white d-inline-block font_14 text-center"
                        href="javascript:void(0)"><i class="fa-brands fa-plus"></i></a></li>
            </ul>
        </div>
        <div class="news_1_right1 bg-white mt-3 border_light">
            <b class="d-block text-uppercase p-3 border_thick">Popular News</b>
            <ul class="mb-0 border-top pt-3">
                
                {foreach $sidebar_data.popular_news as $item}
                <li class="d-flex border-bottom  pb-3 mb-3">
                    <span class="ps-3"><a href='{"news/read/{$item.id}/{$item.slug}"|surl}'><img width="70" alt="{$item.title}" src="{$item.thumbnail}"></a></span>
                    <span class="flex-column mx-3">
                        <b
                            class="d-inline-block bg_violet text-white p-1 px-3 font_10 text-uppercase rounded-1">{$item.category}</b>
                        <b class="d-block font_13 text-uppercase mt-1"><a href='{"news/read/{$item.id}/{$item.slug}"|surl}'>{$item.title|truncate:50}</a></b>
                        <span class="light_gray font_10 fw-bold  text-uppercase"> <i class="fa fa-clock me-1 text-warning align-middle"></i> {$item.created_at}</span>
                    </span>
                </li>
                {/foreach}
            </ul>
        </div>

        <div class="news_1_right1 bg-white mt-3 border_light pb-3">
            <b class="d-block text-uppercase p-3 border_thick">Popular tags</b>
            <ul class="mb-0 d-flex flex-wrap text-uppercase font_11 tags border-top px-3 pt-3">
                {foreach $sidebar_data.popular_tags as $tag}
                <li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3 tag-size-{$tag.total}" href='{"news/tag/search?q={$tag.slug}"|surl}'>{$tag.name}</a></li>
                {/foreach}
            </ul>
        </div>
        <div class="news_1_right1 bg-white mt-3 border_light pb-4">
            <b class="d-block text-uppercase p-3 border_thick">Our Newsletter</b>
            <b class="px-3 text-uppercase font_11 border-top pt-3 d-block">Subscribe Now!</b>
            <p class="px-3 mt-2 mb-3">Read our latest news.</p>
            <form id="newsletterForm">
                {csrf}
                <div class="input-group px-3">
                    <input type="email" name="email" class="form-control font_11" placeholder="Your Email address..." required>
                    <span class="input-group-btn">
                        <button class="btn btn-primary bg-dark border-0 rounded-0 p-3 px-4 font_11"
                            type="submit">
                            SEND </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>