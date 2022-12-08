<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransportController extends Controller
{
    public function index()
    {
        return Transport::query()
            ->select(['id', 'name', 'status'])
            ->where(Transport::TRANSPORT_USER_ID, Auth::id())
            ->get();
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'min:3', 'max:100'],
            'status' => ['required'],
        ];
        $validated = Validator::make($request->input(), $rules);

        if ($validated->fails()){
            return \response()->json($validated->errors(), Response::HTTP_BAD_REQUEST);
        }
        $newTransport = new Transport();
        $newTransport->name = $request->name;
        $newTransport->status = $request->status;
        $newTransport->user_id = auth()->id();
        $newTransport->save();

        return \response()->json(['status' => true], Response::HTTP_CREATED);
    }

    public function show(Transport $transport)
    {
        return response()->json($transport, Response::HTTP_OK);
    }

    public function update(Transport $transport, Request $request)
    {
        $rules = [
            'name' => ['required', 'min:3', 'max:100'],
            'status' => ['required'],
        ];
        $validated = Validator::make($request->input(), $rules);

        if ($validated->fails()){
            return \response()->json($validated->errors(), Response::HTTP_BAD_REQUEST);
        }
        
        $transport->update([
            Transport::TRANSPORT_STATUS => $request->status,
            Transport::TRANSPORT_NAME => $request->name
        ]);
    }

    public function statuses()
    {
        return \response()->json(Transport::TRANSPORT_STATUS_ALL);
    }

    public function destroy(Transport $transport)
    {
        $transport->delete();

        return \response()->json(['status' => true]);
    }
}
