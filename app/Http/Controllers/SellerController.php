<?php
namespace App\Http\Controllers;

use App\Seller;
use App\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SellerController extends Controller
{
    /**
     * @var Seller
     */
    private $seller;

    /**
     * @var User
     */
    private $user;

    public function __construct(
        Seller $seller,
        User $user
    )
    {
        $this->seller = $seller;
        $this->user = $user;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), Seller::$rules);
        if ($validator->fails())
            throw new ValidationException($validator);

        if(!$this->user->where('id', $request->input('user_id'))->exists())
            throw new \InvalidArgumentException('[user_id] não localizado');

        $seller = new Seller();
        $seller->fill($request->all());
        $seller->save();

        return response()->json($seller);
    }
}