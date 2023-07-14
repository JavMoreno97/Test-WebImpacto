<div class="col-md-12">
    <p>{l s='Enter here the customized text that will be shown in the product information section.' mod='customproducttext'}</p>
    <div class="col-md-6">
      {foreach from=$languages item=$language}
        <fieldset class="form-group translatable-field lang-{$language.id_lang}" {if $language.id_lang != $id_language}style="display:none;"{/if}>
            <div class="row">
              <div class="col-lg-9">
                <label class="form-control-label">{l s='Custom text' mod='customproducttext'}</label>
                <input type="text" 
                  class="form-control" 
                  name="customtext[{$language.id_lang}]" 
                  value="{foreach from=$customTextLang item=$customText}{if $customText.id_lang == $language.id_lang}{$customText.custom_text}{/if}{/foreach}"
                  >
              </div>
              <div class="col-lg-2" style="margin-top: auto;">
                <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                  {$language.iso_code}
                  <i class="icon-caret-down"></i>
                </button>
                <ul class="dropdown-menu align-bottom">
                  {foreach from=$languages item=$languageTemp}
                    <li><a href="javascript:hideOtherLanguage({$languageTemp.id_lang});" tabindex="-1">{$languageTemp.name}</a></li>
                  {/foreach}
                </ul>
              </div>
            </div>
        </fieldset>
      {/foreach}
    </div>
</div>