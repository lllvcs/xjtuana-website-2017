<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CampusRepository as Repo;

class CampusController extends Controller
{
    protected $repo;

    public function __construct(Repo $repo) {
        $this->repo = $repo;
    }
    
    public function index(Request $request) {
        $data = $this->repo->all();
        return $this->jsonResponse('OK', 0, $data);
    }

    public function show($id, Request $request) {
        $data = $this->repo->find($id);
        return $this->jsonResponse('OK', 0, $data);
    }
}
