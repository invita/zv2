@extends("layout")

@section("content")

<main>

    <div id="langSelect">
        <a href="/?lang=sl">Slovensko</a>
        <a href="/?lang=en">English</a>
    </div>

    <div id="searchbar" class="mb15">
        <div class="row">

            <div class="large-2 medium-2 small-12 columns">
                <a href="/" title="Žrtve 1. svetovne vojne" style="border:0;">
                    <img id="logoImg" alt="Zgodovina Slovenije - SIstory" src="/img/logo-{{$lang}}.png">
                </a>
            </div>

            <div class="search large-10 medium-10 small-12 columns" style="height: 7em;">
                <div class="searchTitle">
                    <div class="row collapse searchInputTabs">
                        <a class="tab" href="http://www.sistory.si/">PUBLIKACIJE</a>
                        <a class="tab" href="http://zv1.sistory.si/?lang={{$lang}}">ŽRTVE I.SV</a>
                        <a class="tab active" href="http://zv2.sistory.si/?lang={{$lang}}">ŽRTVE II.SV</a>
                        <a class="tab" href="http://www.sistory.si/popis">POPISI</a>
                        <a class="tab" href="http://www.sistory.si/zic">ZIC</a>
                    </div>
                </div>
                <div class="content katSearch active" id="pnlZrtve">
                    <form id="searchFormZrtve">
                        <div class="row collapse searchInputRow">
                            <div class="large-11 medium-11 small-10 columns">
                                <input id="searchInput" name="search" value="{{$search}}" type="text"
                                  class="translatePlaceholder" data-translatePlaceholder="text_searchString">
                            </div>
                            <div class="large-1 medium-1 small-2 columns">
                                <a onclick="$('#searchFormZrtve').submit()" id="searchButtonZrtve" class="postfix button button_small">
                                    <div class="searchicon"></div>
                                </a>
                            </div>
                        </div>
                    </form>
                    <div class="row collapse searchInputRow" style="margin-top: 5px;">
                        <div class="large-11 medium-11 small-10 columns">&nbsp;</div>
                        <div class="large-1 medium-1 small-2 columns">
                            <a class="translateHtml" data-translateHtml="text_vseZrtve"
                                onclick="$('#searchInput').val('*'); $('#searchFormZrtve').submit();"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div id="pageHolder" class="large-12 medium-12 small-12 columns">
            <div id="initView">
                <div class="large-7 medium-12 small-12 columns">
                    <?php echo $contentHtml; ?>
                </div>

                <div class="large-5 medium-12 small-12 columns" style="margin-top:20px;">
                    <pre id="chartData" style="display:none;">
                        {{json_encode([ "chartData" => $chartData,"qType" => "bar" ])}}
                    </pre>
                    <canvas id="chartCanvas" width="500" height="400"></canvas>
                    <script>
                        var mdDataEl = document.getElementById("chartData");
                        var mdData = JSON.parse(mdDataEl.innerText);
                        var chartData = mdData.chartData;
                        console.log(mdData);

                        var canvas = document.getElementById("chartCanvas");
                        var ctx = canvas.getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: mdData.qType || 'bar',
                            data: {
                                labels: Object.keys(chartData),
                                datasets: [{
                                    label: '# of deaths',
                                    data: Object.values(chartData),
                                    backgroundColor: Object.keys(chartData).map(function(x) { return 'rgba(255, 99, 132, 0.2)' }),
                                    borderColor: Object.keys(chartData).map(function(x) { return 'rgba(255,99,132,1)' }),
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero:true
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                    <div class="text-center">
                        <a href="/chart" class="translateHtml" data-translateHtml="chart_checkItOut"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <footer id="bottom">
        <div class="row">
            <div class="large-3 medium-3 columns hide-for-small">
                <a href="/" title="SIstory Zgodovina Slovenije" style="border:0;">
                    <img id="logoImg1" alt="SIstory Zgodovina Slovenije" src="/img/logo-bw.png" style="border:0px; width: auto;">
                </a>
            </div>
            <div class="large-5 medium-5 small-12 columns" style="float:left">
                <div class="row">
                    <?php echo $footerHtml; ?>
                </div>
                <hr class="show-for-small-only">
            </div>

<!--
            <div class="large-4 medium-4 small-12 columns">
                <div class="row collapse">
                    <ul class="firstUL" style="margin:0;">
                        <li><h4 class="title translateHtml" data-translateHtml="text_newsletterSignup"></h4></li>
                    </ul>
                    <div class="small-10 medium-10 large-10 columns">
                        <input id="newsSubscribeEmail" type="email" placeholder="mojmail@posta.com">
                    </div>
                    <div class="small-2 medium-2 large-2 columns">
                        <input id="newsSubscribe" type="submit" href="#" class="postfix button yesicon_small" value="">
                    </div>
                </div>

                <ul class="home-social inline-list">
                    <li><a href="https://www.facebook.com/Zgodovina-Slovenije-SIstory-220693498040267/" style="border:0px;" class="facebook"><img style="height: 40px;" src="/img/footer/ico-fb_circle.png"></a></li>
                    <li><a href="http://www.sistory.si/kontakt" style="border:0px;"><img style="height: 40px;" src="/img/footer/ico-mail_circle.png"></a></li>
                </ul>
            </div>
-->
        </div>


    <div id="bottomfoot">
        <div class="row">
            <hr/>
            <div class="large-9 medium-9 small-12 columns">
                <p>
                    <span>© Copyright 2011-<?php echo date('Y'); ?> INZ, </span>
                    <span class="translateHtml" data-translateHtml="text_sistory"></span> <?php /* echo $HEAD_TITLE */ ?>
                </p>
            </div>
            <div class="large-3 medium-3 small-12 columns"></div>
        </div>

    </div>

    </footer>

</main>

@endsection
