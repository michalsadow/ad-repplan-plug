{include file='elements/header'}

<div class="row mt-3 mb-3">
  <div class="col">
    <h1>Usuwanie harmonogramu spłat <strong>{$id}</strong></h1>
  </div>
</div>
<div class="row">
  <div class="col">

    <div class="alerts"></div>

    {if present $aggrId}
      <form data-fieldsApiUri="/form-fields/delete" data-formApiUri="/repplangen/repplans/{$id}" data-successUri="/dane/harmonogramy-po-umowach/{$aggrId}">
    {fi}
    {if empty $aggrId}
      <form data-fieldsApiUri="/form-fields/delete" data-formApiUri="/repplangen/repplans/{$id}" data-successUri="/dane/harmonogramy">
    {fi}
        <div class="form mb-3">
        </div>
        {if present $aggrId}
          <a href="/dane/harmonogramy-po-umowach/{$aggrId}" type="button" class="btn btn-secondary">&lt;&lt; anuluj</a>
        {fi}
        {if empty $aggrId}
          <a href="/dane/harmonogramy" type="button" class="btn btn-secondary">&lt;&lt; anuluj</a>
        {fi}
        <button type="button" class="btn btn-success" data-send-method="DELETE">skasuj harmonogram</button>
    </form>

  </div>
</div>

{include file='elements/footer'}
