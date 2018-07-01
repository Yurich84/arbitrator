<div class="form-group">
    {!! Form::select('stock_id',
    $stocks, old('stock_id'),
    ['class' => 'form-control', 'placeholder' => 'Виберите биржу']) !!}
</div>
<div class="form-group">
    {!! Form::label('key') !!}
    {!! Form::text('key', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('secret') !!}
    {!! Form::text('secret', null, ['class' => 'form-control']) !!}
</div>