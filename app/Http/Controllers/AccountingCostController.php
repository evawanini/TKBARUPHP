<?php

namespace App\Http\Controllers;

use App\Model\AccountingCostCategory;

use Auth;
use Validator;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class AccountingCostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('accounting.cost.index');
    }

    public function categoryIndex()
    {
        $costcat = AccountingCostCategory::paginate(10);

        return view('accounting.cost.category.index', compact('costcat'));
    }

    public function categoryShow($id)
    {
        $cc = AccountingCostCategory::find($id);

        return view('accounting.cost.category.show', compact('cc'));
    }

    public function categoryCreate()
    {
        $groupdistinct = AccountingCostCategory::select('group')->groupBy('group')->get();

        return view('accounting.cost.category.create', compact('groupdistinct'));
    }

    public function categoryStore(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect(route('db.acc.cost.category.create'))->withInput()->withErrors($validator);
        } else {
            AccountingCostCategory::create([
                'store_id' => Auth::user()->store->id,
                'name' => $data['name'],
                'group' => empty($data['group_select']) ? $data['group_text']:'',
            ]);

            return redirect(route('db.acc.cost.category'));
        }
    }

    public function categoryEdit($id)
    {
        $cc = AccountingCostCategory::find($id);
        $groupdistinct = AccountingCostCategory::select(['group', 'group'])->groupBy('group')->get();

        return view('accounting.cost.category.edit', compact('cc', 'groupdistinct'));
    }

    public function categoryUpdate($id, Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect(route('db.acc.cost.category.edit', Hashids::encode($id)))->withInput()->withErrors($validator);
        } else {
            $acc = AccountingCostCategory::find($id);
            $acc->group = empty($data['group_select']) ? $data['group_text']:'';
            $acc->name = $data['name'];

            $acc->save();

            return redirect(route('db.acc.cost.category'));
        }
    }

    public function categoryDelete($id)
    {
        AccountingCostCategory::find($id)->delete();
        return redirect(route('db.acc.cost.category'));
    }
}
