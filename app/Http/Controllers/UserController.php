<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @var User
     */
    private $user;

    public function __construct(
        User $user
    )
    {
        $this->user = $user;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $queryFilter = $request->input('q');

        $result = $this->user
            ->Where('full_name', 'LIKE', "{$queryFilter}%")
            ->orWhereHas('consumer', function ($query) use ($queryFilter) {
                $query->where('username', 'LIKE', "{$queryFilter}%");
            })->orWhereHas('seller', function ($query) use ($queryFilter) {
                $query->where('username', 'LIKE', "{$queryFilter}%");
            })->get();
        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), User::$rules);
        if ($validator->fails())
            throw new ValidationException($validator);

        $user = new User();
        $user->fill($request->all());
        $user->save();

        return response()->json($user);
    }

    /**
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function load($user_id)
    {
        $result = $this->user->with(['consumer', 'seller'])->find($user_id);
        if(empty($result))
            throw new \InvalidArgumentException('[user_id] não localizado');

        $result['accounts']  = [
            'consumer' => $result['consumer'],
            'seller' => $result['seller']
        ];
        unset($result['consumer'], $result['seller']);

        return response()->json($result);
    }
}