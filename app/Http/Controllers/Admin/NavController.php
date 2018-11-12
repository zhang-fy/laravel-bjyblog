<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Nav\Store;
use App\Models\Nav;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cache;

class NavController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nav = Nav::withTrashed()->get();
        $assign = compact('nav');
        return view('admin.nav.index', $assign);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.nav.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request, Nav $navModel)
    {
        $data = $request->except('_token');
        $result = $navModel->storeData($data);
        if ($result) {
            // 更新缓存
            Cache::forget('common:nav');
        }
        return redirect(url('admin/nav/index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $nav = Nav::find($id);
        $assign = compact('nav');
        return view('admin.nav.edit', $assign);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, Nav $navModel)
    {
        $map = compact('id');
        $data = $request->except('_token');
        $result = $navModel->updateData($map, $data);
        if ($result) {
            // 更新缓存
            Cache::forget('common:nav');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Nav $navModel)
    {
        $map = [
            'id' => $id
        ];
        $result = $navModel->destroyData($map);
        if ($result) {
            // 更新缓存
            Cache::forget('common:nav');
        }
        return redirect('admin/nav/index');
    }

    /**
     * 恢复删除的菜单
     *
     * @param      $id
     * @param Nav $navModel
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restore($id, Nav $navModel)
    {
        $map = [
            'id' => $id
        ];
        $result = $navModel->restoreData($map);
        if ($result) {
            // 更新缓存
            Cache::forget('common:nav');
        }
        return redirect('admin/nav/index');
    }

    /**
     * 彻底删除菜单
     *
     * @param      $id
     * @param Nav $navModel
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function forceDelete($id, Nav $navModel)
    {
        $map = compact('id');
        $navModel->forceDeleteData($map);
        return redirect('admin/nav/index');
    }
}
