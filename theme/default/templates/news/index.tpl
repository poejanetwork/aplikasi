<section id="news" class="pt-5 pb-5 bg-light">
   <div class="container-xl">
     <div class="row news_1">
        <div class="col-md-8">
            <div class="news_1_left">

                <div class="news_1_left3">
                        {if $data}
                    <div class="row row-cols-1 row-cols-lg-2 row-cols-md-1">

                        {foreach from=$data item=item name=test}
                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-body p-0">
                                    <div class="blog_1 position-relative">
                                        <div class="blog_1_inner_top"> <a
                                                href='{"news/read/{$item.id}/{$item.slug}"|surl}'><img
                                                    src="{$item.thumbnail}" class="img-fluid"
                                                    alt="{$item.title}"></a></div>
                                        <div class="blog_1_inner position-absolute top-0 p-3">
                                            <b
                                                class="d-inline-block bg_blue text-white p-1 px-3 font_10 text-uppercase rounded-1">{$item.category|ucfirst}</b>
                                        </div>
                                        <div class="blog_1_inner_1 position-absolute text-end w-100 p-3">
                                            <ul class="mb-0">

                                                <li class="d-block fs-6 mt-2"><a
                                                        class="d-block bg-info text-white  rounded-circle text-center icon_1 icon_2"
                                                        href="news_detail.html"><i
                                                            class="fa-brands fa-facebook-f"></i></a></li>
                                                <li class="d-block fs-6 mt-2"><a
                                                        class="d-block bg-danger text-white  rounded-circle text-center icon_1 icon_2"
                                                        href="news_detail.html"><i
                                                            class="fa-brands fa-twitter"></i></a></li>
                                                <li class="d-block fs-6 mt-2"><a
                                                        class="d-block bg_violet text-white  rounded-circle text-center icon_1 icon_2"
                                                        href="news_detail.html"><i
                                                            class="fa-brands fa-instagram"></i></a></li>
                                                <li class="d-block fs-6 mt-2"><a
                                                        class="d-block bg_yellow text-white  rounded-circle text-center icon_1"
                                                        href="news_detail.html"><i class="fa fa-plus"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="blog_2 pt-3 pb-3">
                                        <span
                                            class="light_gray font_12 fw-bold px-3 text-uppercase">{$item.created_at}</span>
                                        <b class="d-block font_15 text-uppercase mt-2 px-3"><a
                                                href='{"news/read/{$item.id}/{$item.slug}"|surl}'>{$item.title}</a></b>
                                        <hr>
                                        <ul
                                            class="mb-0 px-3 font_11 fw-bold text-uppercase justify-content-between d-flex">
                                            <li>
                                                <a href="javascript:void(0)"><img class="rounded-circle" alt="Avatar"
                                                        src="{$theme}/assets/image/9.png"></a>
                                                <span class="light_gray ms-2">{$item.username}</span>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {/foreach}

                    </div>
                        {else}
                        
                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-body p-0">
                                    <div class="text-center">
                                    Tidak ada berita
                                    </div>
                                </div>
                            </div>
                        </div>
                        {/if}
                </div>

            </div>
        </div>
        
	  {include file=$sidebar}
	 </div>
   </div>
 </section>