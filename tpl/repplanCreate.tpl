{include file='elements/header'}

<div class="row mt-3 mb-3">
  <div class="col">
    <h1>Generacja nowego harmonogramu spłat</h1>
  </div>
</div>
<div class="row">
  <div class="col">

    <div class="alerts"></div>
    <div class="js-ad-form-groups"></div>

    <input type="hidden" id="hidden_name" value="{getHtml name}">
    <input type="hidden" id="hidden_agreement" value="{getHtml agreement}">

    {if present $aggrId}
      <form data-fieldsApiUri="/repplangen/form-fields/repplan" data-formApiUri="/repplangen/repplans" data-successUri="/dane/harmonogramy-po-umowach/{$aggrId}">
    {fi}
    {if empty $aggrId}
      <form data-fieldsApiUri="/repplangen/form-fields/repplan" data-formApiUri="/repplangen/repplans" data-successUri="/dane/harmonogramy">
    {fi}
        <div class="form mb-3">
        </div>

        {if present $aggrId}
          <a href="/dane/harmonogramy-po-umowach/{$aggrId}" type="button" class="btn btn-secondary" onClick="return confirm('Mogą występować niezapisane zmiany. Na pewno chcesz anulować?');">&lt;&lt; anuluj</a>
        {fi}
        {if empty $aggrId}
          <a href="/dane/harmonogramy" type="button" class="btn btn-secondary" onClick="return confirm('Mogą występować niezapisane zmiany. Na pewno chcesz anulować?');">&lt;&lt; anuluj</a>
        {fi}
        <button type="button" class="btn btn-success" data-send-method="POST">dodaj i wyjdź</button>
        <button type="button" class="btn btn-success" data-send-method="POST" data-redirect="false">dodaj i pozostań</button>
    </form>

  </div>
</div>

{include file='elements/footer'}
