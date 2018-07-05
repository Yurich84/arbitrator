<div class="form-group">
    <div class="toggle-flip">
        <label>
            {!! Form::hidden('inter_active', 0) !!}
            {!! Form::checkbox('inter_active', 1, null, ['id' => 'inter_active']) !!}
            <span class="flip-indecator" data-toggle-on="Online" data-toggle-off="Offline"></span>
        </label>
    </div>
</div>

<div class="form-group">
    {!! Form::hidden('favorite', 0) !!}
    {!! Form::checkbox('favorite', 1, null, ['id' => 'favorite']) !!}
    {!! Form::label('favorite') !!}
</div>
<div class="form-group">
    {!! Form::label('fee') !!}
    {!! Form::text('fee', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('timeout') !!}
    {!! Form::text('timeout', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('comment') !!}
    {!! Form::textarea('comment', null, ['class' => 'form-control', 'rows' => 3]) !!}
</div>

<hr/>

<div class="form-group">
    {!! Form::label('pub_key') !!}
    {!! Form::text('pub_key', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('pub_secret') !!}
    {!! Form::text('pub_secret', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('pub_uid') !!}
    {!! Form::text('pub_uid', null, ['class' => 'form-control']) !!}
</div>