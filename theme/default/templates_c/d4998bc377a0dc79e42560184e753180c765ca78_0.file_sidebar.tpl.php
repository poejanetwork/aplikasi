<?php
/* Smarty version 5.5.1, created on 2026-01-27 14:21:08
  from 'file:sidebar.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69786764140b48_05194473',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd4998bc377a0dc79e42560184e753180c765ca78' => 
    array (
      0 => 'sidebar.tpl',
      1 => 1769498466,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69786764140b48_05194473 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\theme\\default\\templates';
?>
<div class="col-md-4">
    <div class="news_1_right">
        <div class="news_1_right1">
            <ul class="mb-0 bg_violet d-flex justify-content-between">
                <li><a class="bg_violet_dark social_icon d-inline-block text-center text-white"
                        href="<?php echo $_smarty_tpl->getValue('settings')['FACEBOOK'];?>
" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                <li class="lh-1 pt-3 text-uppercase">
                    <a class="text-white" href="<?php echo $_smarty_tpl->getValue('settings')['FACEBOOK'];?>
" target="_blank">
                        <span class="font_13">
                            <b>Like Our Facebook Page </b><br>
                            <span class="font_11">86500 Likes</span>
                        </span>
                    </a>
                </li>
                <li class="pt-3 pe-3"><a
                        class="rounded-circle plus_icon rounded-circle text-white d-inline-block font_14 text-center" href="<?php echo $_smarty_tpl->getValue('settings')['FACEBOOK'];?>
" target="_blank"><i class="fa-brands fa-plus"></i></a></li>
            </ul>
            <ul class="mb-0 bg-primary d-flex justify-content-between mt-3">
                <li><a class="bg_primary_dark social_icon d-inline-block text-center text-white"
                        href="<?php echo $_smarty_tpl->getValue('settings')['TWITTER'];?>
" target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
                <li class="lh-1 pt-3 text-uppercase">
                    <a class="text-white" href="<?php echo $_smarty_tpl->getValue('settings')['TWITTER'];?>
" target="_blank">
                        <span class="font_13">
                            <b>Follow us twitter Page </b><br>
                            <span class="font_11">58500 Followers</span>
                        </span>
                    </a>
                </li>
                <li class="pt-3 pe-3"><a
                        class="rounded-circle plus_icon rounded-circle text-white d-inline-block font_14 text-center"
                        href="<?php echo $_smarty_tpl->getValue('settings')['TWITTER'];?>
" target="_blank"><i class="fa-brands fa-plus"></i></a></li>
            </ul>
            <ul class="mb-0 bg-danger d-flex justify-content-between mt-3">
                <li><a class="bg_danger_dark social_icon d-inline-block text-center text-white"
                        href="<?php echo $_smarty_tpl->getValue('settings')['YOUTUBE'];?>
" target="_blank"><i class="fa-brands fa-youtube"></i></a></li>
                <li class="lh-1 pt-3 text-uppercase">
                    <a class="text-white" href="<?php echo $_smarty_tpl->getValue('settings')['YOUTUBE'];?>
" target="_blank">
                        <span class="font_13">
                            <b>Follow us youtube Page </b><br>
                            <span class="font_11">105500 Subscriber</span>
                        </span>
                    </a>
                </li>
                <li class="pt-3 pe-3"><a
                        class="rounded-circle plus_icon rounded-circle text-white d-inline-block font_14 text-center"
                        href="<?php echo $_smarty_tpl->getValue('settings')['YOUTUBE'];?>
" target="_blank"><i class="fa-brands fa-plus"></i></a></li>
            </ul>
            <ul class="mb-0 bg-primary d-flex justify-content-between mt-3">
                <li><a class="bg_primary_dark social_icon d-inline-block text-center text-white"
                        href="<?php echo $_smarty_tpl->getValue('settings')['INSTAGRAM'];?>
" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                <li class="lh-1 pt-3 text-uppercase">
                    <a class="text-white" href="<?php echo $_smarty_tpl->getValue('settings')['INSTAGRAM'];?>
" target="_blank">
                        <span class="font_13">
                            <b>Follow us instagram Page </b><br>
                            <span class="font_11">58500 Followers</span>
                        </span>
                    </a>
                </li>
                <li class="pt-3 pe-3"><a
                        class="rounded-circle plus_icon rounded-circle text-white d-inline-block font_14 text-center"
                        href="<?php echo $_smarty_tpl->getValue('settings')['INSTAGRAM'];?>
" target="_blank"><i class="fa-brands fa-plus"></i></a></li>
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
                
                <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('sidebar_data')['popular_news'], 'item');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('item')->value) {
$foreach0DoElse = false;
?>
                <li class="d-flex border-bottom  pb-3 mb-3">
                    <span class="ps-3"><a href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("news/read/".((string)$_smarty_tpl->getValue('item')['id'])."/".((string)$_smarty_tpl->getValue('item')['slug']));?>
'><img width="70" alt="<?php echo $_smarty_tpl->getValue('item')['title'];?>
" src="<?php echo $_smarty_tpl->getValue('item')['thumbnail'];?>
"></a></span>
                    <span class="flex-column mx-3">
                        <b
                            class="d-inline-block bg_violet text-white p-1 px-3 font_10 text-uppercase rounded-1"><?php echo $_smarty_tpl->getValue('item')['category'];?>
</b>
                        <b class="d-block font_13 text-uppercase mt-1"><a href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("news/read/".((string)$_smarty_tpl->getValue('item')['id'])."/".((string)$_smarty_tpl->getValue('item')['slug']));?>
'><?php echo $_smarty_tpl->getSmarty()->getModifierCallback('truncate')($_smarty_tpl->getValue('item')['title'],50);?>
</a></b>
                        <span class="light_gray font_10 fw-bold  text-uppercase"> <i class="fa fa-clock me-1 text-warning align-middle"></i> <?php echo $_smarty_tpl->getValue('item')['created_at'];?>
</span>
                    </span>
                </li>
                <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
            </ul>
        </div>

        <div class="news_1_right1 bg-white mt-3 border_light pb-3">
            <b class="d-block text-uppercase p-3 border_thick">Popular tags</b>
            <ul class="mb-0 d-flex flex-wrap text-uppercase font_11 tags border-top px-3 pt-3">
                <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('sidebar_data')['popular_tags'], 'tag');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('tag')->value) {
$foreach1DoElse = false;
?>
                <li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3 tag-size-<?php echo $_smarty_tpl->getValue('tag')['total'];?>
" href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("news/tag/search?q=".((string)$_smarty_tpl->getValue('tag')['slug']));?>
'><?php echo $_smarty_tpl->getValue('tag')['name'];?>
</a></li>
                <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
            </ul>
        </div>
        <div class="news_1_right1 bg-white mt-3 border_light pb-4">
            <b class="d-block text-uppercase p-3 border_thick">Our Newsletter</b>
            <b class="px-3 text-uppercase font_11 border-top pt-3 d-block">Subscribe Now!</b>
            <p class="px-3 mt-2 mb-3">Read our latest news.</p>
            <form id="newsletterForm">
                <?php echo $_smarty_tpl->getSmarty()->getFunctionHandler('csrf')->handle(array(), $_smarty_tpl);?>

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
</div><?php }
}
