<?php
/* Smarty version 5.5.1, created on 2025-10-18 07:45:22
  from 'file:captcha.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_68f2e32215e8e1_63819599',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e17cb4c144b5650799f6cb8fdd3519dd61097778' => 
    array (
      0 => 'captcha.tpl',
      1 => 1708522918,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_68f2e32215e8e1_63819599 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\theme\\default\\templates';
if ($_smarty_tpl->getValue('captcha')['check'][$_smarty_tpl->getValue('action')]) {?>

<?php if ($_smarty_tpl->getValue('captcha')['type'] == '1') {?>

<div class="form-group mb-3">
    <div class="">
      <input class="form-control" placeholder="Captcha" type="text" name="captcha" value="" autocomplete="off" required>
        <label class="form-check-label ms-2" for="checkbox-signin"><img src='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("?p=captcha&rand=".((string)$_smarty_tpl->getValue('rand')));?>
'></label>
    </div>
</div>
<?php }?>

<?php if ($_smarty_tpl->getValue('captcha')['type'] == '2') {
echo '<script'; ?>
 src='https://www.google.com/recaptcha/api.js'><?php echo '</script'; ?>
>
<tr>
 <td class=menutxt colspan=2>
<div class="g-recaptcha" data-sitekey="<?php echo $_smarty_tpl->getValue('settings')['captcha_recaptcha']['recaptcha_site_key'];?>
"></div>
 </td>
</tr>
<?php }?>

<?php if ($_smarty_tpl->getValue('captcha')['type'] == '3') {
echo '<script'; ?>
 src="https://www.google.com/recaptcha/api.js?render=<?php echo $_smarty_tpl->getValue('settings')['captcha_recaptcha']['recaptcha_site_key'];?>
"><?php echo '</script'; ?>
>

  <?php echo '<script'; ?>
>
  grecaptcha.ready(function() {
      grecaptcha.execute('<?php echo $_smarty_tpl->getValue('settings')['captcha_recaptcha']['recaptcha_site_key'];?>
', {action: '<?php echo $_smarty_tpl->getValue('action');?>
'}).then(function (token) {
                var rinput = document.getElementById('g-recaptcha');
                rinput.value = token;
            });
  });
  <?php echo '</script'; ?>
>

<tr>
 <td class=menutxt colspan=2>
<input type="hidden" name="g-recaptcha-response" id="g-recaptcha">
 </td>
</tr>
<?php }?>

<?php }
}
}
