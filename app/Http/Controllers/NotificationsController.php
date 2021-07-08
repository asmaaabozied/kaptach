<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class NotificationsController extends BaseController
{
    public function index(Request $request)
    {
        if ($request->all()) {
            $admin_id = auth('admin')->user()->id;
            $admin = Admin::findOrFail($admin_id);
            $notifications = $admin->notifications;
            return Datatables::of($notifications)
                ->make(true);
        }
        return view('companies.push-notifications.index');
    }

    public function create()
    {
        return view('companies.push-notifications.create');
    }

    public function store(Request $request)
    {

    }

    public function markAsRead(Request $request)
    {
        auth('admin')->user()
            ->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

    }
}
