{foreach from=$events item=event}
  <h2>{$event.title}</h2>

  <h3>Meubilair</h3>
  {foreach from=$event.meubilair item=subsection}
    <table class="form-layout-compressed">
      <tbody>
      {foreach from=$subsection item=value key=label}
        <tr class="{cycle values="odd-row,even-row"}">
            <td>{$label}</td>
            <td>{$value}</td>
        </tr>
      {/foreach}
      </tbody>
    </table>
  {/foreach}

  <h3>Catering</h3>
  {foreach from=$event.catering item=subsection}
    <table class="form-layout-compressed">
      <tbody>
      {foreach from=$subsection item=value key=label}
        <tr class="{cycle values="odd-row,even-row"}">
          <td>{$label}</td>
          <td>{$value}</td>
        </tr>
      {/foreach}
      </tbody>
    </table>
  {/foreach}

  <h3>Technisch materiaal</h3>
  {foreach from=$event.technisch_materiaal item=subsection}
    <table class="form-layout-compressed">
      <tbody>
      {foreach from=$subsection item=value key=label}
        <tr class="{cycle values="odd-row,even-row"}">
          <td>{$label}</td>
          <td>{$value}</td>
        </tr>
      {/foreach}
      </tbody>
    </table>
  {/foreach}

{/foreach}
