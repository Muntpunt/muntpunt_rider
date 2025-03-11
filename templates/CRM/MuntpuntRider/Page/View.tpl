<a href="/civicrm/muntpunt-rider-view?datum={$previousDay}">&lt; Vorige dag</a> | <a href="/civicrm/muntpunt-rider-view?datum={$nextDay}">Volgende dag &gt;</a>
{foreach from=$events item=event}
  <h2>{$event.title}</h2>
  <p>Van {$event.start_hour} tot {$event.end_hour}, Zalen: {$event.zalen}</p>
  <p>Aanspreekpersoon: {$event.aanspreekpersoon}</p>

  {if count($event.meubilair) > 0}
    <h3>Meubilair</h3>
    <table class="form-layout-compressed">
      <tbody>
      {foreach from=$event.meubilair key=label item=value}
        <tr class="{cycle values="odd-row,even-row"}">
            <td>{$label}</td>
            <td>{$value}</td>
        </tr>
      {/foreach}
      </tbody>
    </table>
  {/if}

  {if count($event.catering) > 0}
    <h3>Catering</h3>
    <table class="form-layout-compressed">
      <tbody>
      {foreach from=$event.catering key=label item=value}
        <tr class="{cycle values="odd-row,even-row"}">
          <td>{$label}</td>
          <td>{$value}</td>
        </tr>
      {/foreach}
      </tbody>
    </table>
  {/if}

  {if count($event.technisch_materiaal) > 0}
    <h3>Technisch materiaal</h3>
    <table class="form-layout-compressed">
      <tbody>
      {foreach from=$event.technisch_materiaal key=label item=value}
        <tr class="{cycle values="odd-row,even-row"}">
          <td>{$label}</td>
          <td>{$value}</td>
        </tr>
      {/foreach}
      </tbody>
    </table>
  {/if}

{/foreach}
