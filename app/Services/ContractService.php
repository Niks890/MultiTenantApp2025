<?php
namespace App\Services;

use App\Repositories\Contracts\ContractRepositoryInterface;


class ContractService {
    protected $contractRepository;

    public function __construct(ContractRepositoryInterface $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    public function all(){
        return $this->contractRepository->all();
    }






}
