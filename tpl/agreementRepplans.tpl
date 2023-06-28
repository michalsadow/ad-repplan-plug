{include file='elements/header'}

<div class="row mt-3 mb-3">
  <div class="col">
    <h1>Wygenerowane harmonogramy spłat do umowy</h1>
  </div>
</div>

<div class="row">
  <div class="col">

    <p>Poniżej przedstawiona jest lista wygenerowanych w programie harmonogramów spłat do:</p>

    <p><strong>Klient: <span data-fill-extra="name">[extra:name]</span></strong></p>
    <p><strong>Umowa: <span data-fill-extra="agreement">[extra:agreement]</span></strong></p>

    <div class="alerts"></div>

    <a class="btn mb-3 btn-primary" data-fill-extra="nameLinkSafe,agreementLinkSafe" href="/dane/harmonogramy/dodaj?name=[extra:nameLinkSafe]&agreement=[extra:agreementLinkSafe]">dodaj nowy harmonogram spłat</a>

    <table class="table table-sm" data-rowsApiUri="/repplangen/agreements/{$aggrId}/repplans" data-callAfterRowsApi="distributeExtraValues">
      <thead>
        <tr>
          <th data-key="id" scope="col">ID<a class="sA"></a><a class="sD"></a></th>
          <th data-key="defaultSort" scope="col">klient<a class="sA"></a><a class="sD"></a></th>
          <th data-key="agreementSort" scope="col">umowa<a class="sA"></a><a class="sD"></a></th>
          <th data-key="generationDate" scope="col">wygenerowany<a class="sA"></a><a class="sD"></a></th>
          <th data-key="sumOfPayments" scope="col">suma wypłat<a class="sA"></a><a class="sD"></a></th>
          <th data-key="minRate" scope="col">min. st. proc.<a class="sA"></a><a class="sD"></a></th>
          <th data-key="maxRate" scope="col">max. st. proc.<a class="sA"></a><a class="sD"></a></th>
          <th data-key="inForceLoc" scope="col">obow.?<a class="sA"></a><a class="sD"></a></th>
          <th class="text-center" scope="col">operacje</th>
        </tr>
        <tr class="js-ad-list-specimen" data-rowId="{ajax id}">
          <th scope="row" style="text-align:center;">{ajax id}</th>
          <td>{ajax name}</td>
          <td>{ajax agreement}</td>
          <th scope="row" style="text-align:center;">{ajax generationDate}</th>
          <td style="text-align:right;">{ajax sumOfPaymentsFormatted}</td>
          <td style="text-align:right;">{ajax minRateFormatted}</td>
          <td style="text-align:right;">{ajax maxRateFormatted}</td>
          <td class="redNoGreenYes{ajax inForce}" style="cursor:pointer;"><a href="/dane/harmonogramy-po-umowach/{$aggrId}/{ajax id}/{ajax possibleReactionLoc}">{ajax inForceLoc}</a></td>
          <td class="align-middle text-center ad-operations">
            <a href="/dane/harmonogramy-po-umowach/{$aggrId}/{ajax id}/edytuj" class="btn btn-success btn-sm">edytuj</a>
            <a href="{ajax xlsxUri}" target="_blank" class="btn btn-success btn-sm">XLSX</a>
            <a href="/dane/harmonogramy-po-umowach/{$aggrId}/{ajax id}/powiel" class="btn btn-success btn-sm">powiel</a>
            <a href="/dane/harmonogramy-po-umowach/{$aggrId}/{ajax id}/kasuj" class="btn btn-danger btn-sm">usuń</a>
          </td>
        </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
        <th colspan="666">wyświetlam wyniki od <strong class="setFrom">0</strong> do <strong class="setTo">0</strong> z <strong class="setAll">0</strong><span class="js-ad-set-page">, przełącz do strony: <select class="js-ad-set-page form-select"></select></span></th>
      </tfoot>
    </table>

  </div>
</div>

{include file='elements/footer'}
