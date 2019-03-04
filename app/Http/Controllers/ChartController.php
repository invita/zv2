<?php

namespace App\Http\Controllers;

use App\Helpers\ElasticHelpers;
use App\Helpers\FooterBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ChartController extends Controller
{
    public function index(Request $request)
    {
        $lang = App::getLocale();

        $lands = ElasticHelpers::distinctDezela();
        $munics = ElasticHelpers::distinctObcina();

        $types = [
            ["key" => "bar", "value" => "Bar chart"],
            ["key" => "line", "value" => "Line chart"],
        ];
        $froms = $this->dateInterval($lang, '1940-03', '1949-12');
        $tos = $this->dateInterval($lang, '1940-03', '1949-12');

        $qType = $request->input('type');
        $qFrom = $request->input('from');
        $qTo = $request->input('to');
        $qLand = $request->input('land');
        $qMunic = $request->input('munic');

        $chartData = [];

        if ($qType) {
            $chartDataElastic = ElasticHelpers::searchChartData($qFrom, $qTo, $qLand, $qMunic);
            $chartData = $this->fillMissingKeysAndSort($qFrom, $qTo, $froms, $tos, $chartDataElastic);
            //print_r($chartData);
        }


        $footerHtml = FooterBuilder::getHtml();

        $viewData = [
            "search" => "",
            "zrtve" => [],
            "lang" => $lang,
            "types" => $types,
            "froms" => $froms,
            "tos" => $tos,
            "lands" => $lands,
            "munics" => $munics,
            "qType" => $qType,
            "qFrom" => $qFrom,
            "qTo" => $qTo,
            "qLand" => $qLand,
            "qMunic" => $qMunic,
            "chartData" => $chartData,
            "footerHtml" => $footerHtml,
        ];
        return view('chart', $viewData);
    }

    private function dateInterval($lang, $from, $to) {
        $fromExpl = explode("-", $from);
        $fromYear = intval($fromExpl[0]);
        $fromMonth = intval($fromExpl[1]);

        $toExpl = explode("-", $to);
        $toYear = intval($toExpl[0]);
        $toMonth = intval($toExpl[1]);

        $result = [];

        for ($year = $fromYear; $year <= $toYear; $year++) {
            for ($month = $year == $fromYear ? $fromMonth : 1; $month <= ($year == $toYear ? $toMonth : 12); $month++) {
                $result[] = [
                    "key" => $year."-".str_pad($month, 2, "0", STR_PAD_LEFT),
                    "value" => $year."-".str_pad($month, 2, "0", STR_PAD_LEFT),
                ];
            }
        }

        return $result;
    }

    private function fillMissingKeysAndSort($qFrom, $qTo, $froms, $tos, $chartDataElastic) {
        $existingKeys = array_keys($chartDataElastic);

        foreach ($froms as $f) {
            $val = $f["key"];
            if ((!$qFrom || $val >= $qFrom) && (!$qTo || $val <= $qTo) && !in_array($val, $existingKeys)) {
                $chartDataElastic[$val] = 0;
            }
        }

        // Sort
        $chartDataKeys = array_keys($chartDataElastic);
        sort($chartDataKeys);
        $result = [];
        foreach ($chartDataKeys as $key) {
            $result[$key] = $chartDataElastic[$key];
        }

        return $result;
    }
}
