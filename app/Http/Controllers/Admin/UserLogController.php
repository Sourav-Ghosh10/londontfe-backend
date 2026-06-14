<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserLog;
use Illuminate\Http\Request;

class UserLogController extends Controller
{
    private $typeMap = [
        'quick-enquiry' => ['type' => 'quickenquery', 'title' => 'Quick Enquiry Event'],
        'download-outline' => ['type' => 'outline', 'title' => 'Download Full Outline'],
        'details-checkout' => ['type' => 'dtlschk', 'title' => 'Details Checkout'],
        'cart' => ['type' => 'cart', 'title' => 'Cart'],
        'before-payment' => ['type' => 'beforepay', 'title' => 'Before Payment'],
        'after-checkout' => ['type' => 'aftercheckout', 'title' => 'After Checkout'],
        'coupon' => ['type' => 'coupon', 'title' => 'Coupon'],
    ];

    public function index(Request $request, $slug)
    {
        if (!array_key_exists($slug, $this->typeMap)) {
            abort(404);
        }

        $logConfig = $this->typeMap[$slug];
        $dbType = $logConfig['type'];
        $logTitle = $logConfig['title'];

        $query = UserLog::where('log_type', $dbType);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('ip', 'like', '%' . $search . '%')
                  ->orWhere('country', 'like', '%' . $search . '%')
                  ->orWhere('phone_no', 'like', '%' . $search . '%');
            });
        }

        $sortCol = $request->input('sort', 'created_dt');
        $sortDir = $request->input('dir', 'desc');

        // Whitelist columns for sorting
        $allowedSorts = ['crm_ids', 'crm_update_dt', 'name', 'email', 'phone_no', 'ip', 'country', 'created_dt'];
        if (in_array($sortCol, $allowedSorts)) {
            $query->orderBy($sortCol, $sortDir);
        }

        $entries = $request->input('entries', 100);
        $logs = $query->paginate($entries)->withQueryString();

        return view('admin.logs.index', compact('logs', 'logTitle', 'slug', 'entries', 'sortCol', 'sortDir'));
    }
}
