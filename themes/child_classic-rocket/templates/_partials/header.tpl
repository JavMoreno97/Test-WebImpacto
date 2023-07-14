{extends file='parent:_partials/header.tpl'}

{block name='header_nav'}
  <div class="header-nav u-bor-bot">
      <div class="header__container container">
          <div class="u-a-i-c d--flex-between visible--desktop">
              <div class="small">
                  {hook h='displayNav1'}
              </div>
              <div class="header-nav__center">
                  {hook h='displayNavWeather'}
              </div>
              <div class="header-nav__right">
                  {hook h='displayNav2'}
              </div>
          </div>
      </div>
  </div>
{/block}