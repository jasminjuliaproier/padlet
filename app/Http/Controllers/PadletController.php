<?php

namespace App\Http\Controllers;

use App\Models\Entrie;
use App\Models\Padlet;
use App\Models\User;
use App\Models\Userright;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PadletController extends Controller
{
    public function index():JsonResponse{
        $padlets = Padlet::with(['user','entries', 'userrights'])->get();
        return response()->json($padlets, 200);
    }

    //findbyid method
    public function findByID (string $id) : JsonResponse{
        $padlet = Padlet::where('id', $id)
            ->with(['user','entries', 'userrights'])->first();
        return $padlet != null ? response()->json($padlet, 200) : response()->json(null, 200);
    }

    //Check if id exist
    public function checkID(string $isbn) : JsonResponse{
        $padlet = Padlet::where('id', $isbn)->first();
        return $padlet != null ? response()->json(true, 200) : response()->json(false, 200);
    }

    //Find by SearchTerm
    public function findBySearchTerm (string $searchTerm) : JsonResponse {
        $padlets = Padlet::with(['user','entries', 'userrights', 'entries.comments','entries.ratings'])
            ->where('name', 'LIKE' , '%' . $searchTerm . '%')
            ->orWhereHas('user', function($query) use ($searchTerm){
                $query->where('firstName', 'LIKE','%' . $searchTerm . '%' )
                    ->orWhere('lastName', 'LIKE','%' . $searchTerm . '%' );
            })->get();
        return response()->json($padlets, 200);
    }

    //Save a padlet
    public function save(Request $request) : JsonResponse{

        $request = $this->parseRequest($request);
        DB::beginTransaction();

        try {
            $padlet = Padlet::create($request->all());

            DB::commit();
            return response()->json($padlet, 200);
        }

        catch (\Exception $e) {

            DB::rollBack();
            return response()->json("saving padlet failed: " . $e->getMessage(), 420);
        }

    }

    //delete a padlet with all comments and ratings
    public function delete(string $id) : JsonResponse {
        $padlet = Padlet::where('id', $id)->first();
        if ($padlet != null) {
            $padlet->delete();
            return response()->json('padlet (' . $id . ') successfully deleted', 200);
        }
        else
            return response()->json('padlet could not be deleted - it does not exist', 422);
    }



    //padlet updaten
    public function update(Request $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $padlet = Padlet::with(['user', 'entries', 'userrights'])
                ->where('id', $id)->first();
            if ($padlet != null) {
                $request = $this->parseRequest($request);
                $padlet->update($request->all());

                $padlet->userrights()->delete();

                //Update Userrights
                if (isset($request['userrights']) && is_array($request['userrights'])) {
                    foreach ($request['userrights'] as $userrights) {

                        $userrights = Userright::firstOrNew(
                            ['padlet_id' => $userrights['padlet_id'],
                                'user_id' => $userrights['user_id'],
                                'read'=>$userrights['read'],
                                'edit'=>$userrights['edit']]);
                        $padlet->userrights()->save($userrights);
                    }
                }

                $padlet->save();
            }
            DB::commit();
            $padlet1 = Padlet::with(['user', 'entries', 'userrights'])
                ->where('id', $id)->first(); // return a vaild http response
            return response()->json($padlet1, 201);
        } catch (\Exception $e) {

            // rollback all queries
            DB::rollBack();
            return response()->json("updating book failed: " . $e->getMessage(), 420);
        }
    }

    public function show($padlet)
    {
        $padlet = Padlet::find($padlet);
        return view('padlets.show', compact('padlet'));
    }

    private function parseRequest(Request $request) : Request {

        // convert date
        $date = new \DateTime($request->published);
        $request['published'] = $date;
        return $request;

    }

}
