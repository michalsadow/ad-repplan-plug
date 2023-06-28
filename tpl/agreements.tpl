{include file='elements/header'}

<div class="row mt-3 mb-3">
  <div class="col">
    <h1>Umowy z harmonogramami spłat</h1>
  </div>
</div>

<div class="row">
  <div class="col">

    <p>Poniżej przedstawiona jest lista umów, do których istnieją wygenerowane w programie harmonogramy spłat.</p>

    <div class="alerts"></div>

    <a class="btn mb-3 btn-primary" href="/dane/harmonogramy/dodaj">dodaj nowy harmonogram spłat</a>

    <table class="table table-sm" data-rowsApiUri="/repplangen/agreements">
      <thead>
        <tr>
          <th data-key="fullName" scope="col">klient<a class="sA"></a><a class="sD"></a></th>
          <th data-key="agreement" scope="col">umowa<a class="sA"></a><a class="sD"></a></th>
          <th data-key="count" scope="col">planów (w tym obowiązujących)<a class="sA"></a><a class="sD"></a></th>
          <th data-key="latest" scope="col">najnowszy<a class="sA"></a><a class="sD"></a></th>
          <th data-key="latestInForce" scope="col">najnowoszy obowiązujący<a class="sA"></a><a class="sD"></a></th>
          <th class="text-center" scope="col">operacje</th>
        </tr>
        <tr class="js-ad-list-specimen" data-rowId="{ajax id}">
          <td style="font-weight:bold;">{ajax name}</td>
          <td>{ajax agreement}</td>
          <td style="text-align:center;">{ajax count} ({ajax countInForce})</td>
          <td style="text-align:center;">{ajax latest}</td>
          <td style="text-align:center;">{ajax latestInForce}</td>
          <td class="align-middle text-center ad-operations">
            <a href="/dane/harmonogramy-po-umowach/{ajax id}" class="btn btn-success btn-sm">wyświetl</a>
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
