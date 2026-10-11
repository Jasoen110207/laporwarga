<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OtherCategoryController extends Controller
{
    private const DAYS = 7;
    private const ALERT_KEYWORD = 5;
    private const ALERT_PERCENT = 30;

    public function index(Request $request)
    {
        abort_unless($request->user()->isStaff(), 403);

        $otherId = Category::where('slug', 'lainnya')->value('id');
        $since = now()->subDays(self::DAYS);

        $keywords = Report::where('category_id', $otherId)
            ->whereNotNull('other_category')
            ->selectRaw('other_category, COUNT(*) as total, SUM(created_at >= ?) as recent', [$since])
            ->groupBy('other_category')
            ->orderByDesc('recent')
            ->orderByDesc('total')
            ->get();

        $allRecent = Report::where('created_at', '>=', $since)->count();
        $otherRecent = Report::where('category_id', $otherId)->where('created_at', '>=', $since)->count();
        $percent = $allRecent > 0 ? round($otherRecent / $allRecent * 100) : 0;

        return view('admin.other-categories', [
            'keywords' => $keywords,
            'days' => self::DAYS,
            'percent' => $percent,
            'otherRecent' => $otherRecent,
            'allRecent' => $allRecent,
            'alertKeyword' => self::ALERT_KEYWORD,
            'alertPercent' => self::ALERT_PERCENT,
        ]);
    }

    public function promote(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403);

        $request->validate(['keyword' => 'required|string|max:80']);

        $otherId = Category::where('slug', 'lainnya')->value('id');

        $category = Category::firstOrCreate(
            ['slug' => Str::slug($request->keyword)],
            ['name' => $request->keyword]
        );

        $moved = Report::where('category_id', $otherId)
            ->where('other_category', $request->keyword)
            ->update(['category_id' => $category->id, 'other_category' => null]);

        return back()->with('success', "{$moved} laporan dipindahkan ke kategori \"{$category->name}\".");
    }
}