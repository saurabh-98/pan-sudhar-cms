<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsRequest;
use App\DTO\NewsDTO;
use App\Models\News;
use App\Services\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $service;

    public function __construct(NewsService $service)
    {
        $this->service = $service;
    }

    /* =========================
       LIST (DATATABLE JSON)
    ========================= */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json($this->service->getAll());
        }

        return view('admin.news.index');
    }

    /* =========================
       STORE
    ========================= */
    public function store(NewsRequest $request)
    {
        $dto = NewsDTO::fromRequest($request);

        $this->service->store($dto, $request->file('image'));

        return response()->json(['message' => 'Created']);
    }

    /* =========================
       UPDATE
    ========================= */
    public function update(NewsRequest $request, News $news)
    {
        $dto = NewsDTO::fromRequest($request);

        $this->service->update($news, $dto, $request->file('image'));

        return response()->json(['message' => 'Updated']);
    }

    /* =========================
       DELETE
    ========================= */
    public function destroy(News $news)
    {
        $this->service->delete($news);

        return response()->json(['message' => 'Deleted']);
    }
}