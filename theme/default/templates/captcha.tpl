{if $captcha.check[$action]}

{if $captcha.type == '1'}

<div class="form-group mb-3">
    <div class="">
      <input class="form-control" placeholder="Captcha" type="text" name="captcha" value="" autocomplete="off" required>
        <label class="form-check-label ms-2" for="checkbox-signin"><img src='{"?p=captcha&rand={$rand}"|surl}'></label>
    </div>
</div>
{/if}

{if $captcha.type == '2'}
<script src='https://www.google.com/recaptcha/api.js'></script>
<tr>
 <td class=menutxt colspan=2>
<div class="g-recaptcha" data-sitekey="{$settings.captcha_recaptcha.recaptcha_site_key}"></div>
 </td>
</tr>
{/if}

{if $captcha.type == '3'}
<script src="https://www.google.com/recaptcha/api.js?render={$settings.captcha_recaptcha.recaptcha_site_key}"></script>
{literal}
  <script>
  grecaptcha.ready(function() {
      grecaptcha.execute('{/literal}{$settings.captcha_recaptcha.recaptcha_site_key}{literal}', {action: '{/literal}{$action}{literal}'}).then(function (token) {
                var rinput = document.getElementById('g-recaptcha');
                rinput.value = token;
            });
  });
  </script>
{/literal}
<tr>
 <td class=menutxt colspan=2>
<input type="hidden" name="g-recaptcha-response" id="g-recaptcha">
 </td>
</tr>
{/if}

{/if}