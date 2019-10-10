<?php
namespace App\Http\Controllers;

use App\Consumer;
use App\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsumerController extends Controller
{
    /**
     * @var Consumer
     */
    private $consumer;

    /**
     * @var User
     */
    private $user;

    public function __construct(
        Consumer $consumer,
        User $user
    )
    {
        $this->consumer = $consumer;
        $this->user = $user;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), Consumer::$rules);
        if ($validator->fails())
            throw new ValidationException($validator);

        if(!$this->user->where('id', $request->input('user_id'))->exists())
            throw new \InvalidArgumentException('[user_id] não localizado');

        $consumer = new Consumer();
        $consumer->fill($request->all());
        $consumer->save();

        return response()->json($consumer);
    }
}