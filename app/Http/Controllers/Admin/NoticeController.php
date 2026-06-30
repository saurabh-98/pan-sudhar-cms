<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoticeRequest;
use App\Services\NoticeService; 
use App\DTO\NoticeDTO;

use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function __construct(protected NoticeService $service){}

    public function index()
    {
        if(request()->ajax()){
            $data = $this->service->getAll();

            return response()->json([
                'data'=>$data->items(),
                'recordsTotal'=>$data->total(),
                'recordsFiltered'=>$data->total()
            ]);
        }

        return view('admin.notice.index');
    }

    public function store(NoticeRequest $request)
    {
        $this->service->store(NoticeDTO::fromRequest($request));
        return response()->json(['success'=>true]);
    }

    public function update(NoticeRequest $request,$id)
    {
        $this->service->update($id, NoticeDTO::fromRequest($request));
        return response()->json(['success'=>true]);
    }

    public function delete($id)
    {
        $this->service->delete($id);
        return response()->json(['success'=>true]);
    }
}