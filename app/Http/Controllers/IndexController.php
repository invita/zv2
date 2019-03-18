<?php

namespace App\Http\Controllers;

use App\Helpers\ContentBuilder;
use App\Helpers\ElasticHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class IndexController extends LayoutController
{
    public function index(Request $request)
    {
        $lang = App::getLocale();
        $menuId = $request->input('menuId');
        $contentHtml = "";
        if ($menuId)
            $contentHtml = ContentBuilder::getHtmlForMenuId($menuId);
        else
            $contentHtml = ContentBuilder::getHtmlForFirstPage();

        $froms = ChartController::dateInterval($lang, '1939-01', '1945-12');
        $tos = ChartController::dateInterval($lang, '1939-01', '1945-12');
        $chartDataElastic = ElasticHelpers::searchChartData(null, null, null, null);
        $chartData = ChartController::fillMissingKeysAndSort(null, null, $froms, $tos, $chartDataElastic);

        $viewData = $this->getLayoutData($request, [
            "zrtve" => [],
            "contentHtml" => $contentHtml,
            "chartData" => $chartData,
        ]);

        return view('index', $viewData);
    }
}
