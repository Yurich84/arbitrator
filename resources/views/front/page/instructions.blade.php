@extends('front.main')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="tile">
                <div class="tile-body">

                    <section id=timeline>
                        <h1>Как зарабатывать деньги на межбиржевом арбитраже?</h1>
                        <p class="leader">
                            По сути это купить монету там где она стоит дешевле и продать там где она стоит дороже. <br/>
                            Но чтобы это сделать надо знать: где какая монета стоит и сколько можно на ней заработать. Для этого и существует данный ресурс <br/>
                            Следуя простым инструкциям описанным ниже, ви не только заработаете деньги, но и убережетесь от всевозможных рисков их потерять.
                        </p>


                        <div class="row justify-content-center">
                            <div class="card text-center">
                                <div class="head p-3" style="background: #46b8e9; color: #fff;">
                                    <h2>START</h2>
                                </div>
                                <div class="body" style="background: #fff;">
                                    <br/>
                                    <p class="leader text-danger">Преддполагается что вы прошли регистрацию для использования всех функций сайта</p>
                                </div>
                            </div>
                        </div>


                        <div class="demo-card-wrapper">
                            <div class="demo-card demo-card--step1">
                                <div class="head">
                                    <div class="number-box">
                                        <span>1</span>
                                    </div>
                                    <h2><span class="small">Шаг</span> Арбитражные вилки</h2>
                                </div>
                                <div class="body">
                                    <p>На первой странице собраны все арбитражные ситуации по более чем 50 биржах (и их количество увеличиваеться).
                                        Здесь показаны последние цены на биржах, максимальная разница между ними в процентах без учета коммисий и объем торгов за 24 часа.</p>
                                    <a href="/imgs/instructions/inter_table.png" data-fancybox="gallery"><img src="/imgs/instructions/min/inter_table.png" alt="Graphic"></a>
                                </div>
                            </div>

                            <div class="demo-card demo-card--step2">
                                <div class="head">
                                    <div class="number-box">
                                        <span>2</span>
                                    </div>
                                    <h2><span class="small">Шаг</span> Фильтр</h2>
                                </div>
                                <div class="body">
                                    <p>Отфильтровываем ненужные. Ненадежные биржи, или быржи на которых вы не зарегестрированны.
                                        Выбираем профит и т.д. <br/>
                                    Можно его сохранить</p>
                                    <a href="/imgs/instructions/inter_filter.png" data-fancybox="gallery"><img src="/imgs/instructions/min/inter_filter.png" alt="Graphic"></a>
                                </div>
                            </div>

                            <div class="demo-card demo-card--step3">
                                <div class="head">
                                    <div class="number-box">
                                        <span>3</span>
                                    </div>
                                    <h2><span class="small">Шаг</span> Торговая пара</h2>
                                </div>
                                <div class="body">
                                    <p>Смотрим Comparision. Сдесь уже можна переходить на быржи и смотреть возможность торговать монету.</p>
                                    <a href="/imgs/instructions/inter_pair.png" data-fancybox="gallery"><img src="/imgs/instructions/min/inter_pair.png" alt="Graphic"></a>
                                </div>
                            </div>

                            {{--<div class="demo-card demo-card--step4">--}}
                                {{--<div class="head">--}}
                                    {{--<div class="number-box">--}}
                                        {{--<span>04</span>--}}
                                    {{--</div>--}}
                                    {{--<h2><span class="small">Шаг</span> Сравнительная таблица</h2>--}}
                                {{--</div>--}}
                                {{--<div class="body">--}}
                                    {{--<p>Для более детального изучения всех аозможностей по даной паре, соверуем перейди на страницу с таблицой.--}}
                                        {{--Сдесь использоуеться цена предложения (bid) для покупки монеты и цена спроса (ask) для ее продажи.--}}
                                    {{--</p>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="demo-card demo-card--step5">--}}
                                {{--<div class="head">--}}
                                    {{--<div class="number-box">--}}
                                        {{--<span>05</span>--}}
                                    {{--</div>--}}
                                    {{--<h2><span class="small">Шаг</span> История цен</h2>--}}
                                {{--</div>--}}
                                {{--<div class="body">--}}
                                    {{--<p>--}}
                                    {{--Для отслеживания динамики цен во времени используйте страницу истории цен.--}}
                                        {{--Восновном используеться для стратегии когда монеты не переводят между биржами, а покупають когда есть расхождение с другими биржами в минимальную сторону.--}}
                                    {{--</p>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="demo-card demo-card--step4">
                                <div class="head">
                                    <div class="number-box">
                                        <span>4</span>
                                    </div>
                                    <h2><span class="small">Шаг</span> Ввод/вывод на биржах</h2>
                                </div>
                                <div class="body">
                                    <p>Там где покупаем: там надо вывести монету, поэтому проверяем ее на вывод (withdraw)</p>
                                    <p>Там где продаем: туда надо завести монету, поэтому проверяем ее на ввод (deposit)</p>
                                    <a href="/imgs/instructions/inter_withdraw.png" data-fancybox="gallery"><img src="/imgs/instructions/min/inter_withdraw.png" alt="Graphic"></a>
                                </div>
                            </div>

                            <div class="demo-card demo-card--step5">
                                <div class="head">
                                    <div class="number-box">
                                        <span>5</span>
                                    </div>
                                    <h2><span class="small">Шаг</span> Проверяем обьем</h2>
                                </div>
                                <div class="body">
                                    <p>(чтоб цена не виросла во время покупки)</p>
                                    <p>(чтоб цена не упала во время продажи)</p>
                                    <a href="/imgs/instructions/inter_stakan.png" data-fancybox="gallery"><img src="/imgs/instructions/min/inter_stakan.png" alt="Graphic"></a>
                                </div>
                            </div>

                            <div class="demo-card demo-card--step3">
                                <div class="head">
                                    <div class="number-box">
                                        <span>6</span>
                                    </div>
                                    <h2><span class="small">Шаг</span> Советы перед торгом</h2>
                                </div>
                                <div class="body">
                                    <p>
                                        <br/>Учитиваем все коммисии
                                        <br/>Комисии биржи на вивод
                                        <br/>Комисии двух бирж на на обмен

                                        <br/>Учитиваем время транзакции
                                        <br/>Учитиваем время вывода/ввода монеты на биржах
                                        <br/>читаем чат на биржах
                                    </p>
                                    <a href="/imgs/instructions/inter_fee.png" data-fancybox="gallery"><img src="/imgs/instructions/min/inter_fee.png" alt="Graphic"></a>
                                </div>
                            </div>

                        </div>

                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="head p-3" style="background: #46b8e9; color: #fff;">
                                        <h2>FINISH</h2>
                                    </div>
                                    <div class="body" style="background: #fff;">
                                        <br/>
                                        <p class="leader text-success">Получаем прибыль</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </section>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link href="{{ asset('css/timeline.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/jquery.fancybox.min.css')}}" />
@endsection
@section('scripts')
    <script src="{{asset('js/jquery.fancybox.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $.fancybox.defaults.hash = false;min/
            $("[data-fancybox]").fancybox({

            });
        });
    </script>
@endsection