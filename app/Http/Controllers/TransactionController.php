<?php
namespace App\Http\Controllers;

use App\Consumer;
use App\Helpers\DateTimeHelper;
use App\Seller;
use App\Transaction;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var Consumer
     */
    private $consumer;

    /**
     * @var Seller
     */
    private $seller;

    public function __construct(
        Transaction $transaction,
        Consumer $consumer,
        Seller $seller
    )
    {
        $this->transaction = $transaction;
        $this->consumer = $consumer;
        $this->seller = $seller;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), Transaction::$rules);
        if ($validator->fails())
            throw new ValidationException($validator);

        if(!$this->consumer->where('id', $request->input('payee_id'))->exists() &&
            !$this->seller->where('id', $request->input('payee_id'))->exists()
        )
            throw new \InvalidArgumentException('[payee_id] não localizado');

        if(!$this->consumer->where('id', $request->input('payer_id'))->exists() &&
            !$this->seller->where('id', $request->input('payer_id'))->exists()
        )
            throw new \InvalidArgumentException('[payer_id] não localizado');

        $value = $request->input('value');

        /**
         * Validar valor em mecanismo externo
         *
         * 1. A transação poderia ser inserida com o status "pendente de autorização",
         *          ou seja, a autorização aconteceria de forma assíncrona,
         *          onde callbakcs de atualizações poderiam ser disparados
         *
         * 2. Em caso de vários autorizadores, poderíamos criar uma integração através de Factory,
         *          ou dependendo das regras de negócio o Strategy.
         *          Em caso de apenas 1 autorizador, talvez uma Trait.
         *
         * 3. E sempre utilizando componentes (SDK) mantidos pelo autorizador,
         *          e em caso da não existência de um SDK, criamos um para reutilização nos demais projetos.
         *
         * 4. Na dúvida da solução e para evitar implementações desnecessárias, incluirei neste controller. ok?
         */

        if ($value > 100)
            throw new \InvalidArgumentException('[value] acima do limite', 401);

        $transaction = new Transaction();
        $transaction->transaction_date = date("Y-m-d\TH:i:s\Z");
        $transaction->fill($request->all());
        $transaction->save();

        return response()->json($transaction);
    }

    /**
     * @param $transaction_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function load($transaction_id)
    {
        $result = $this->transaction->find($transaction_id);
        if(empty($result))
            throw new \InvalidArgumentException('[transaction_id] não localizado');

        return response()->json($result);
    }
}